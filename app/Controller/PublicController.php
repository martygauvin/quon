<?php
/**
 * Public Controller
 * @package Controller
 */
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
App::uses('Survey', 'Model');
/**
 * Public Controller
 * @package Controller
 * @property Public $Public
 */
class PublicController extends AppController {
	/** The objects used.*/
	public $uses = array('Survey', 'SurveyInstance', 'SurveyObject', 'SurveyResult', 'SurveyInstanceObject', 'SurveyResultAnswer', 'SurveyObjectAttribute', 'Participant', 'SurveyAttribute');
	/** The helpers used.*/
	var $helpers = array('Html', 'Form', 'Question', 'Branding');

	/**
	 * Logs the partiicpant out.
	 */
	public function logout()
	{
		$this->Session->delete('Participant.username');

		$this->redirect(array('action' => 'index'));
	}

	/** 
	 * Logs the participant in.
	 * @param string $survey_short_name The short name of the survey to log the participant into
	 */
	public function login($survey_short_name = null)
	{
		//TODO: The participant login function needs a security review

		$survey = $this->Survey->find('first',
				array('conditions' => array('Survey.short_name' => $survey_short_name)));

		$surveyAttributes = $this->SurveyAttribute->find('all',
				array('conditions' => array('SurveyAttribute.survey_id' => $survey['Survey']['id'])));

		if (!$survey)
		{
			$this->Session->setFlash(__('Incorrect Survey Short Name'));
		}
		else if ($survey['Survey']['live_instance'] == NULL)
		{
			$this->Session->setFlash(__('No live instance of this survey available'));
		}

		if ($this->request->is('post'))
		{
			$auth = false;
				
			$username = $this->request->data['Public']['username'];
				
			if (isset($this->request->data['Public']['password']))
				$password = $this->request->data['Public']['password'];
			else
				$password = null;
				
			if ($survey['Survey']['type'] == Survey::type_anonymous)
			{
				$auth = true;
			}
			else if ($survey['Survey']['type'] == Survey::type_identified)
			{
				$user = $this->Participant->find('first',
						array('conditions' => array('username' => $username,
								'survey_id' => $survey['Survey']['id'])));

				if ($user)
				{
					$auth = true;
				}
			}
			else if ($survey['Survey']['type'] == Survey::type_autoidentified)
			{
				$user = $this->Participant->find('first',
						array('conditions' => array('username' => $username,
								'survey_id' => $survey['Survey']['id'])));

				if (!$user)
				{
					$this->Participant->create();
					$data = array();
					$data['Participant']['survey_id'] = $survey['Survey']['id'];
					$data['Participant']['username'] = $username;
						
					if ($this->Participant->save($data));
					{
						$auth = true;
					}
				}
				else
				{
					$auth = true;
				}
			}
			else
			{
				$user = $this->Participant->find('first', array('conditions' =>
						array('username' => $username,
						  'password' => AuthComponent::password($password),
						  'survey_id' => $survey['Survey']['id'])));

				if ($user)
				{
					$auth = true;
				}
			}
				
			if ($auth)
			{
				$this->Session->write('Participant.username', $username);
				$this->redirect(array('action' => 'index', $survey_short_name));
			}
			else
			{
				$this->Session->setFlash(__('Invalid participant credentials'));
			}
		}
		$this->set('survey', $survey);
		$this->set('surveyAttributes', $this->flatten_attributes($surveyAttributes));
	}

	/**
	 * index method.
	 * 
	 * Presents the user with whatever is required to start the survey with the given short name.
	 * This could be a log in page or the first question in the survey.
	 * 
	 * @param string $survey_short_name The short name of the survey to display
	 */
	public function index($survey_short_name = null) {
		$session_username = $this->Session->read('Participant.username');

		if ($survey_short_name)
		{
			$survey = $this->Survey->find('first',
					array('conditions' => array('Survey.short_name' => $survey_short_name)));
				
			if (!$survey)
			{
				$this->Session->setFlash(__('Incorrect Survey Short Name'));
			}
			else if ($survey['Survey']['live_instance'] == NULL)
			{
				$this->Session->setFlash(__('No live instance of this survey available'));
			}
			else
			{
				$this->set('survey_title', $survey['Survey']['name']);
				$this->Session->write('Participant.breadcrumb', array());

				if ($survey['Survey']['type'] == Survey::type_anonymous)
				{
					$this->SurveyResult->create();
					$data = array();
					$data['SurveyResult']['survey_instance_id'] = $survey['Survey']['live_instance'];
					$data['SurveyResult']['date'] = date('Y-m-d h:i:s');
					$data['SurveyResult']['test'] = false;
					$this->SurveyResult->save($data);
						
					$firstObject = $this->SurveyInstanceObject->find('first',
							array('conditions' => array('survey_instance_id' => $survey['Survey']['live_instance']),
							  'order' => 'SurveyInstanceObject.order'));
						
					$this->redirect(array('action' => 'question', $this->SurveyResult->getLastInsertID(), $firstObject['SurveyInstanceObject']['id']));
				}
				else if ($session_username)
				{
					// Handle identified/authenticated users starting a survey
						
					$session_user = $this->Participant->find('first', array('conditions' => array('username' => $session_username)));
						
					// Check for existing survey result set
					$existing = $this->SurveyResult->find('first', array('conditions' => array('participant_id' => $session_user['Participant']['id'],
							'survey_instance_id' => $survey['Survey']['live_instance'])));
						
					if ($existing)
					{
						// Redirect to last object of half-completed survey
						// TODO: Allow a survey to have a 'timeout' on half-completed instances
						// TODO: Optionally give the user a chance to either start again or continue a half-completed survey

						if ($existing['SurveyResult']['completed'])
						{
							if ($survey['Survey']['multiple_run'])
							{
								// Survey previously completed, but multiple run selected so create a new result set
								$this->SurveyResult->create();
								$data = array();
								$data['SurveyResult']['participant_id'] = $session_user['Participant']['id'];
								$data['SurveyResult']['survey_instance_id'] = $survey['Survey']['live_instance'];
								$data['SurveyResult']['date'] = date('Y-m-d h:i:s');
								$data['SurveyResult']['test'] = false;
								$this->SurveyResult->save($data);
									
								$firstObject = $this->SurveyInstanceObject->find('first',
										array('conditions' => array('survey_instance_id' => $survey['Survey']['live_instance']),
												'order' => 'SurveyInstanceObject.order'));

								$this->redirect(array('action' => 'question', $this->SurveyResult->getLastInsertID(), $firstObject['SurveyInstanceObject']['id']));

							}
							else
							{
								$this->redirect(array('action' => 'complete', $existing['SurveyResult']['id']));

							}
						}
						else
						{
							$lastObject = $this->SurveyResultAnswer->find('first', array('order' => 'SurveyResultAnswer.id DESC', 'conditions' => array('survey_result_id' => $existing['SurveyResult']['id'])));

							if (!$lastObject)
							{
								$firstObject = $this->SurveyInstanceObject->find('first',
										array('conditions' => array('survey_instance_id' => $survey['Survey']['live_instance']),
												'order' => 'SurveyInstanceObject.order'));
								$this->redirect(array('action' => 'question', $existing['SurveyResult']['id'], $firstObject['SurveyInstanceObject']['id']));

							}
							else
							{
								$this->redirect(array('action' => 'question', $existing['SurveyResult']['id'], $lastObject['SurveyResultAnswer']['survey_instance_object_id']));

							}
						}
					}
					else
					{
						// Create new instance
						$this->SurveyResult->create();
						$data = array();
						$data['SurveyResult']['participant_id'] = $session_user['Participant']['id'];
						$data['SurveyResult']['survey_instance_id'] = $survey['Survey']['live_instance'];
						$data['SurveyResult']['date'] = date('Y-m-d h:i:s');
						$data['SurveyResult']['test'] = false;
						$this->SurveyResult->save($data);
							
						$firstObject = $this->SurveyInstanceObject->find('first',
								array('conditions' => array('survey_instance_id' => $survey['Survey']['live_instance']),
										'order' => 'SurveyInstanceObject.order'));
							
						$this->redirect(array('action' => 'question', $this->SurveyResult->getLastInsertID(), $firstObject['SurveyInstanceObject']['id']));
					}
				}
				else
				{
					$this->redirect(array('action' => 'login', $survey_short_name));
				}
			}
		}
	}

	/**
	 * complete method.
	 * 
	 * Completes the run through the survey.
	 * 
	 * @param string $survey_result_id The id of the survey result to save the results into
	 */
	public function complete($survey_result_id = null) {
		$surveyResult = $this->SurveyResult->read(null, $survey_result_id);
		$participant = $this->Participant->read(null, $surveyResult['SurveyResult']['participant_id']);
		$surveyInstance = $this->SurveyInstance->read(null, $surveyResult['SurveyResult']['survey_instance_id']);
		$survey = $this->Survey->read(null, $surveyInstance['SurveyInstance']['survey_id']);
		$surveyAttributes = $this->SurveyAttribute->find('all',
				array('conditions' => array('SurveyAttribute.survey_id' => $survey['Survey']['id'])));

		if ($surveyResult['SurveyResult']['test'] == false)
		{
			// If authenticated/identified - check we still have a session
			$session_username = $this->Session->read('Participant.username');
			if (!$session_username && $survey['Survey']['type'] != Survey::type_anonymous)
			{
				$this->redirect(array('action' => 'index', $survey['Survey']['short_name']));
			}
				
			// If authenticated/identified - check this session has access to this result set
			if ($survey['Survey']['type'] != Survey::type_anonymous && $session_username != $participant['Participant']['username'])
			{
				$this->redirect(array('action' => 'index', $survey['Survey']['short_name']));
			}
				
			$surveyResult['SurveyResult']['completed'] = true;
			$this->SurveyResult->save($surveyResult);
				
			$this->Session->delete('Participant.username');
			$this->set('preview', false);
				
			$this->set('survey_title', $survey['Survey']['name']);
			$this->set('survey', $survey);
			$this->set('surveyAttributes', $this->flatten_attributes($surveyAttributes));
		}
		else
		{
			$this->set('preview', true);
			$this->set('surveyAttributes', $this->flatten_attributes($surveyAttributes));
		}

	}

	/**
	 * answer method.
	 * 
	 * Allows the participant to answer a question.
	 */
	public function answer() {
		$survey_result_id = $this->request->data['Public']['survey_result_id'];
		$surveyResult = $this->SurveyResult->read(null, $survey_result_id);
		$participant = $this->Participant->read(null, $surveyResult['SurveyResult']['participant_id']);
		$survey_instance_object_id = $this->request->data['Public']['survey_instance_object_id'];
		$surveyObjectInstance = $this->SurveyInstanceObject->read(null, $survey_instance_object_id);
		$surveyObject = $this->SurveyObject->read(null, $surveyObjectInstance['SurveyInstanceObject']['survey_object_id']);
		$survey = $this->Survey->read(null, $surveyObject['SurveyObject']['survey_id']);
		$direction = $this->request->data['Public']['direction'];
		$surveyObjectAttributes = $this->SurveyObjectAttribute->find('all',
				array('conditions' => array('survey_object_id' => $surveyObject['SurveyObject']['id'])));


		// TODO: Broken MVC - find a better way to access a helper from a controller
		$view = new View($this);
		$questionFactory = $view->loadHelper('Question');
		$questionHelper = $questionFactory->getHelper($surveyObject['SurveyObject']['type']);

		// If authenticated/identified - check we still have a session
		if ($surveyResult['SurveyResult']['test'] == false)
		{
			$session_username = $this->Session->read('Participant.username');
			if (!$session_username && $survey['Survey']['type'] != Survey::type_anonymous)
			{
				$this->redirect(array('action' => 'index', $survey['Survey']['short_name']));
			}

			// If authenticated/identified - check this session has access to this result set
			if ($survey['Survey']['type'] != Survey::type_anonymous && $session_username != $participant['Participant']['username'])
			{
				$this->redirect(array('action' => 'index', $survey['Survey']['short_name']));
			}
		}

		$existingAnswer = $this->SurveyResultAnswer->find('first', array('conditions' => array('survey_result_id' => $survey_result_id,
				'survey_instance_object_id' => $survey_instance_object_id)));
		$surveyResultAnswer = $this->SurveyResultAnswer->create();
		$surveyResultAnswer['SurveyResultAnswer']['survey_result_id'] = $survey_result_id;
		$surveyResultAnswer['SurveyResultAnswer']['survey_instance_object_id'] = $survey_instance_object_id;

		if ($existingAnswer)
		{
			$surveyResultAnswer['SurveyResultAnswer']['id'] = $existingAnswer['SurveyResultAnswer']['id'];
		}
			
		$surveyResultAnswer['SurveyResultAnswer']['answer'] = $questionHelper->serialise($this->request->data, $surveyObjectAttributes);

		if (!$this->SurveyResultAnswer->save($surveyResultAnswer)) {
			$this->Session->setFlash(__('The survey object could not be saved. Please, try again.'));
			$this->redirect(array('action' => 'question', $survey_result_id, $survey_object_instance_id));
		}

		if ($direction == 'next')
		{
			// Only move to next if validation passes
			if ($questionHelper->validate($this->request->data, $surveyObjectAttributes, $validationError)) {
				$next = $this->SurveyInstanceObject->find('first',
						array('conditions' => array('survey_instance_id' => $surveyObjectInstance['SurveyInstanceObject']['survey_instance_id'],
								'order >' => $surveyObjectInstance['SurveyInstanceObject']['order']),
								'order' => 'SurveyInstanceObject.order'));
			}
			else
			{
				if (isset($validationError) && $validationError != '') {
					$this->Session->setFlash($validationError);
				} else {
					$this->Session->setFlash('Error validating answer. Please try again.');
				}
				$next = $surveyObjectInstance;
			}
				
			// Remember last rendered question
			$breadcrumb = $this->Session->read('Participant.breadcrumb');
				
			if (($breadcrumb) == NULL)
				$breadcrumb = array();
				
			array_push($breadcrumb, $survey_instance_object_id);
			$this->Session->write('Participant.breadcrumb', $breadcrumb);
				
			if ($next)
				$this->redirect(array('action' => 'question', $survey_result_id, $next['SurveyInstanceObject']['id']));
			else
				$this->redirect(array('action' => 'complete', $survey_result_id));
		}
		else
		{
			$breadcrumb = $this->Session->read('Participant.breadcrumb');
			$next = array_pop($breadcrumb);
			$this->Session->write('Participant.breadcrumb', $breadcrumb);

			if ($next)
			{
				$this->redirect(array('action' => 'question', $survey_result_id, $next));
			}
			else
			{
				$this->redirect(array('action' => 'question', $survey_result_id, $survey_instance_object_id));
			}
		}
	}

	/**
	 * question method.
	 * 
	 * Displays a question to the user
	 * 
	 * @param int $survey_result_id The id of ant survey result to get previous answers from
	 * @param int $survey_object_instance_id The id of the survey object instance to show
	 */
	public function question($survey_result_id = null, $survey_object_instance_id = null) {
		// TODO: If this user, in this survey results, has answered this question before then we should pre-load the answer

		$surveyObjectInstance = $this->SurveyInstanceObject->read(null, $survey_object_instance_id);
		$surveyObject = $this->SurveyObject->read(null, $surveyObjectInstance['SurveyInstanceObject']['survey_object_id']);
		$surveyObjectAttributes = $this->SurveyObjectAttribute->find('all',
				array('conditions' => array('survey_object_id' => $surveyObject['SurveyObject']['id'])));
		$survey = $this->Survey->read(null, $surveyObject['SurveyObject']['survey_id']);
		$surveyResult = $this->SurveyResult->read(null, $survey_result_id);
		$surveyResultAnswer = $this->SurveyResultAnswer->find('first',
				array('conditions' => array('survey_result_id' => $survey_result_id, 'survey_instance_object_id' => $survey_object_instance_id)));
		$surveyAttributes = $this->SurveyAttribute->find('all',
				array('conditions' => array('SurveyAttribute.survey_id' => $surveyObject['Survey']['id'])));

		if ($surveyResult['SurveyResult']['test'] == false)
		{
			$participant = $this->Participant->read(null, $surveyResult['SurveyResult']['participant_id']);

			// If authenticated/identified - check we still have a session
			$session_username = $this->Session->read('Participant.username');
			if (!$session_username && $survey['Survey']['type'] != Survey::type_anonymous)
			{
				$this->redirect(array('action' => 'index', $survey['Survey']['short_name']));
			}
				
			// If authenticated/identified - check this session has access to this result set
			if ($survey['Survey']['type'] != Survey::type_anonymous && $session_username != $participant['Participant']['username'])
			{
				$this->redirect(array('action' => 'index', $survey['Survey']['short_name']));
			}
		}
		else
		{
			$participant = null;
		}


		$this->set('survey_title', $survey['Survey']['name']);

		$displayQuestion = true;

		// TODO: There has to be a nicer way to handle this hook into the branching logic
		if ($surveyObject['SurveyObject']['type'] == '8')
		{
			$displayQuestion = false;
			$regex = "/\[(.*?)\] (=|<|<=|>=|>|!=) \"(.*?)\"/";
			$rule = $surveyObjectAttributes[0]['SurveyObjectAttribute']['value'];
			$pos_redirect = $surveyObjectAttributes[1]['SurveyObjectAttribute']['value'];
			$neg_redirect = $surveyObjectAttributes[2]['SurveyObjectAttribute']['value'];
				
			$next = $this->SurveyInstanceObject->find('first',
					array('conditions' => array('survey_instance_id' => $surveyObjectInstance['SurveyInstanceObject']['survey_instance_id'],
							'order >' => $surveyObjectInstance['SurveyInstanceObject']['order']),
							'order' => 'SurveyInstanceObject.order'));
				
			if ($pos_redirect == "")
			{
				$posObjectInstance = $next;
			}
			else
			{
				$posObject = $this->SurveyObject->find('first', array('conditions' => array('SurveyObject.name' => $pos_redirect, 'SurveyObject.survey_id' => $surveyObject['Survey']['id'])));
				$posObjectInstance = $this->SurveyInstanceObject->find('first', array('conditions' => array('survey_object_id' => $posObject['SurveyObject']['id'],
						'survey_instance_id' => $surveyResult['SurveyResult']['survey_instance_id'])));
			}
				
			if ($neg_redirect == "")
			{
				$negObjectInstance = $next;
			}
			else
			{
				$negObject = $this->SurveyObject->find('first', array('conditions' => array('SurveyObject.name' => $neg_redirect, 'SurveyObject.survey_id' => $surveyObject['Survey']['id'])));
				$negObjectInstance = $this->SurveyInstanceObject->find('first', array('conditions' => array('survey_object_id' => $negObject['SurveyObject']['id'],
						'survey_instance_id' => $surveyResult['SurveyResult']['survey_instance_id'])));
			}
				
			$condition_true = true;
				
			if ($rule != "")
			{
				$conditions = split("&", $surveyObjectAttributes[0]['SurveyObjectAttribute']['value']);

				foreach ($conditions as $condition)
				{
					preg_match($regex, $condition, $matches);
						
					$resultObject = $this->SurveyObject->find('first', array('conditions' => array('SurveyObject.name' => $matches[1], 'SurveyObject.survey_id' => $surveyObject['Survey']['id'])));
					$resultObjectInstance = $this->SurveyInstanceObject->find('first', array('conditions' => array('survey_object_id' => $resultObject['SurveyObject']['id'],
							'survey_instance_id' => $surveyResult['SurveyResult']['survey_instance_id'])));
					$result = $this->SurveyResultAnswer->find('first', array('conditions' =>
							array('survey_instance_object_id' => $resultObjectInstance['SurveyInstanceObject']['id'],
						  	'survey_result_id' => $survey_result_id)));
						
					if ($matches[2] == "=")
					{
						if (strcasecmp(trim($matches[3]), trim($result['SurveyResultAnswer']['answer'])) == 0)
						{
							$condition_true = true;
						}
						else
						{
							$condition_true = false;
							break;
						}
					}
					else if ($matches[2] == "!=")
					{
						if (strcasecmp(trim($matches[3]), trim($result['SurveyResultAnswer']['answer'])) == 0)
						{
							$condition_true = false;
						}
						else
						{
							$condition_true = true;
							break;
						}
					}
					else if ($matches[2] == "<")
					{
						$match_value = intval($matches[3]);
						$result_value = intval($result['SurveyResultAnswer']['answer']);

						if ($result_value < $match_value)
						{
							$condition_true = true;
						}
						else
						{
							$condition_true = false;
							break;
						}
					}
					else if ($matches[2] == ">")
					{
						$match_value = intval($matches[3]);
						$result_value = intval($result['SurveyResultAnswer']['answer']);
							
						if ($result_value > $match_value)
						{
							$condition_true = true;
						}
						else
						{
							$condition_true = false;
							break;
						}
					}
					else if ($matches[2] == "<=")
					{
						$match_value = intval($matches[3]);
						$result_value = intval($result['SurveyResultAnswer']['answer']);
							
						if ($result_value <= $match_value)
						{
							$condition_true = true;
						}
						else
						{
							$condition_true = false;
							break;
						}
					}
					else if ($matches[2] == ">=")
					{
						$match_value = intval($matches[3]);
						$result_value = intval($result['SurveyResultAnswer']['answer']);
							
						if ($result_value >= $match_value)
						{
							$condition_true = true;
						}
						else
						{
							$condition_true = false;
							break;
						}
					}
				}

				if ($condition_true)
				{
					$this->redirect(array('action' => 'question', $survey_result_id, $posObjectInstance['SurveyInstanceObject']['id']));
				}
				else
				{
					$this->redirect(array('action' => 'question', $survey_result_id, $negObjectInstance['SurveyInstanceObject']['id']));
				}
			}
			else
			{
				$this->redirect(array('action' => 'question', $survey_result_id, $posObjectInstance['SurveyInstanceObject']['id']));
			}
		}
		// TODO: There has to be a nicer way to handle this hook into the calculator logic
		if ($surveyObject['SurveyObject']['type'] == '10')
		{
			$calculationString = $surveyObjectAttributes[0]['SurveyObjectAttribute']['value'];
			$displayString = $surveyObjectAttributes[1]['SurveyObjectAttribute']['value'];
			$errorString = $surveyObjectAttributes[2]['SurveyObjectAttribute']['value'];
			$decimalPlaces = $surveyObjectAttributes[3]['SurveyObjectAttribute']['value'];
			if (!$errorString) {
				$errorString = 'error';
			}
			$errorFound = false;
				
			$regex = '#(\[[^\]]*\])#';
				
			$splitCalculation = preg_split($regex, $calculationString, null, PREG_SPLIT_DELIM_CAPTURE);
			$calculationString = '';
			foreach ($splitCalculation as $split) {
				if (preg_match($regex, $split)) {
					$questionName = substr($split, 1, -1);
						
					$splitQuestionObject = $this->SurveyObject->find('first', array('conditions' => array('SurveyObject.name' => $questionName, 'SurveyObject.survey_id' => $surveyObject['Survey']['id'])));
					$splitQuestionObjectInstance = $this->SurveyInstanceObject->find('first', array('conditions' => array('survey_object_id' => $splitQuestionObject['SurveyObject']['id'],
							'survey_instance_id' => $surveyObjectInstance['SurveyInstanceObject']['survey_instance_id'])));
					$splitResult = $this->SurveyResultAnswer->find('first', array('conditions' =>
							array('survey_instance_object_id' => $splitQuestionObjectInstance['SurveyInstanceObject']['id'],
									'survey_result_id' => $survey_result_id)));
						
					$answerValue = $splitResult['SurveyResultAnswer']['answer'];
					// Check if answer is an "other" option
					if (strpos($answerValue, '|')) {
						$answerValue = substr($answerValue, 0, strpos($answerValue, '|'));
					}
					if (!is_numeric($answerValue)) {
						$answerValue = '0';
						$errorFound = true;
					}
						
					$calculationString = $calculationString.$answerValue;
				} else {
					$calculationString = $calculationString.$split;
				}
			}
				
			if (null != eval('$calculatedValue='.preg_replace('#[^0-9\+\-\*\/\(\)\.]#','',$calculationString).';')) {
				$errorFound = true;
			}
				
			if ($errorFound) {
				$calculatedValue = $errorString;
			} else if ($decimalPlaces && is_numeric($decimalPlaces)){
				$calculatedValue = round($calculatedValue, $decimalPlaces);
			}
				
			$existingAnswer = $this->SurveyResultAnswer->find('first', array('conditions' => array('survey_result_id' => $survey_result_id,
					'survey_instance_object_id' => $survey_object_instance_id)));
			$surveyResultAnswer = $this->SurveyResultAnswer->create();
			$surveyResultAnswer['SurveyResultAnswer']['survey_result_id'] = $survey_result_id;
			$surveyResultAnswer['SurveyResultAnswer']['survey_instance_object_id'] = $survey_object_instance_id;
			if ($existingAnswer)
			{
				$surveyResultAnswer['SurveyResultAnswer']['id'] = $existingAnswer['SurveyResultAnswer']['id'];
			}

			$surveyResultAnswer['SurveyResultAnswer']['answer'] = $calculatedValue;

			// TODO: Detmine sensible behaviour for when a calculation cannot be saved.
			// Currently just forwards to next question
			if (!$this->SurveyResultAnswer->save($surveyResultAnswer)) {
				$displayString = '';
			}
				
			if (!$displayString) {
				$displayQuestion = false;
				$next = $this->SurveyInstanceObject->find('first',
						array('conditions' => array('survey_instance_id' => $surveyObjectInstance['SurveyInstanceObject']['survey_instance_id'],
								'order >' => $surveyObjectInstance['SurveyInstanceObject']['order']),
								'order' => 'SurveyInstanceObject.order'));
				if ($next) {
					$this->redirect(array('action' => 'question', $survey_result_id, $next['SurveyInstanceObject']['id']));
				} else {
					$this->redirect(array('action' => 'complete', $survey_result_id));
				}
			}
		}

		if ($displayQuestion)
		{
			$this->set('survey', $survey);
			$this->set('surveyObject', $surveyObject);
			$this->set('surveyInstanceObject', $surveyObjectInstance);
			$this->set('surveyResultID', $survey_result_id);
			$this->set('surveyObjectAttributes', $surveyObjectAttributes);
			$this->set('surveyResultAnswer', $surveyResultAnswer);
			$this->set('surveyAttributes', $this->flatten_attributes($surveyAttributes));
		}
	}

	/**
	 * isAuthorized method.
	 * @param  user the logged in user, or null if unauthenticated
	 *
	 * @return boolean representing if a user can access this controller
	 */
	public function isAuthorized($user = null) {
		return true;
	}

	/**
	 * beforeFilter method.
	 *
	 * Opens this controller to the public.
	 */
	function beforeFilter(){
		$this->Auth->allow('*');
	}

}
?>
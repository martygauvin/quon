<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
App::uses('Survey', 'Model');
/**
 * Public Controller
 * @package Controller
 * @property Public $Public
 */
class PublicController extends AppController {
	public $uses = array('Survey', 'SurveyInstance', 'SurveyObject', 'SurveyResult', 'SurveyInstanceObject', 'SurveyResultAnswer', 'SurveyObjectAttribute', 'Participant', 'SurveyAttribute', 'SurveyTemplate', 'SurveyTemplateInstance');
	var $helpers = array('Html', 'Form', 'Question', 'Branding');
	var $components = array('SurveyObjectFactory', 'ExpressionParser');

	/**
	 * Logs the participant out.
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
				array('order' => array('SurveyAttribute.id'),
					   'conditions' => array('SurveyAttribute.survey_id' => $survey['Survey']['id'])));

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
				array('order' => array('SurveyAttribute.id'), 'conditions' => array('SurveyAttribute.survey_id' => $survey['Survey']['id'])));

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
		$surveyInstance = $this->SurveyInstance->read(null, $surveyObjectInstance['SurveyInstanceObject']['survey_instance_id']);
		$surveyObject = $this->SurveyObject->read(null, $surveyObjectInstance['SurveyInstanceObject']['survey_object_id']);
		$survey = $this->Survey->read(null, $surveyObject['SurveyObject']['survey_id']);
		$direction = $this->request->data['Public']['direction'];
		$surveyObjectAttributes = $this->SurveyObjectAttribute->find('all',
				array('order' => array('SurveyObjectAttribute.id'), 'conditions' => array('survey_object_id' => $surveyObject['SurveyObject']['id'])));

		// Check if this object requires a component assist
		$componentHelper = $this->SurveyObjectFactory->getComponent($surveyObject['SurveyObject']['type']);
		
		if ($componentHelper)
		{
			$surveyObjectAttributes = $componentHelper->augment($surveyObjectAttributes, $surveyObjectInstance, $surveyInstance, $surveyResult, $this);
		}

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

		if ($surveyObject['SurveyObject']['type'] == '8')
		{
			if ($direction == 'next')
			{
				$this->redirect(array('action' => 'question', $survey_result_id, $this->request->data['Public']['branchDestination']));	
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
		else
		{

		$existingAnswer = $this->SurveyResultAnswer->find('first', array('conditions' => array('survey_result_id' => $survey_result_id,
				'survey_instance_object_id' => $survey_instance_object_id)));
		$surveyResultAnswer = $this->SurveyResultAnswer->create();
		$surveyResultAnswer['SurveyResultAnswer']['survey_result_id'] = $survey_result_id;
		$surveyResultAnswer['SurveyResultAnswer']['survey_instance_object_id'] = $survey_instance_object_id;

		if ($existingAnswer)
		{
			$surveyResultAnswer['SurveyResultAnswer']['id'] = $existingAnswer['SurveyResultAnswer']['id'];
		}
		
		if ($surveyObject['SurveyObject']['type'] == '12')
		{
			$objects = $surveyObjectAttributes[0]['SurveyObjectAttribute']['value'];
				
			$metaAnswer = array();
				
			foreach (explode("|", $objects) as $object_name)
			{
				$metaSurveyObject = $this->SurveyObject->find('first', array('conditions' => array('SurveyObject.name' => $object_name, 'SurveyObject.survey_id' => $survey['Survey']['id'])));
			
				$metaSurveyObjectAttributes = $this->SurveyObjectAttribute->find('all',
						array('order' => array('SurveyObjectAttribute.id'), 'conditions' => array('survey_object_id' => $metaSurveyObject['SurveyObject']['id'])));

				$metaQuestionHelper = $questionFactory->getHelper($metaSurveyObject['SurveyObject']['type']);
				
				$data = $this->request->data;
                $field_name = $metaSurveyObject['SurveyObject']['id'].'_answer';

				if (isset($data['Public'][$field_name])) {
					$metaQuestionAnswer = $metaQuestionHelper->convert($this->request->data, $metaSurveyObjectAttributes);
				} else {
					$metaQuestionAnswer = array();
					$metaQuestionAnswer['type'] = $metaSurveyObject['SurveyObject']['type'];
					$metaQuestionAnswer['value'] = '';
				}
				$metaQuestionAnswer = $metaQuestionHelper->serialise($metaQuestionAnswer, $metaSurveyObjectAttributes);
				$metaAnswer[$object_name] = $metaQuestionAnswer;
			}
				
			$surveyResultAnswer['SurveyResultAnswer']['answer'] = $questionHelper->serialise($metaAnswer, $surveyObjectAttributes);			
		}
		else
		{
			$answer = $questionHelper->convert($this->request->data, $surveyObjectAttributes);
			$surveyResultAnswer['SurveyResultAnswer']['answer'] = $questionHelper->serialise($answer, $surveyObjectAttributes);
		}

		// Trim any extra whitespace
		$surveyResultAnswer['SurveyResultAnswer']['answer'] = trim($surveyResultAnswer['SurveyResultAnswer']['answer']);

		if (!$this->SurveyResultAnswer->save($surveyResultAnswer)) {
			$this->Session->setFlash(__('The survey object could not be saved. Please, try again.'));
			$this->redirect(array('action' => 'question', $survey_result_id, $survey_object_instance_id));
		}
				
		if ($direction == 'next')
		{
			$valid = true;
			
			if ($surveyObject['SurveyObject']['type'] == '12')
			{
				$objects = $surveyObjectAttributes[0]['SurveyObjectAttribute']['value'];
			
				$metaAnswer = "";
			
				foreach (explode("|", $objects) as $object_name)
				{
					$metaSurveyObject = $this->SurveyObject->find('first', array('conditions' => array('SurveyObject.name' => $object_name, 'SurveyObject.survey_id' => $survey['Survey']['id'])));
						
					$metaSurveyObjectAttributes = $this->SurveyObjectAttribute->find('all',
							array('order' => array('SurveyObjectAttribute.id'), 'conditions' => array('survey_object_id' => $metaSurveyObject['SurveyObject']['id'])));
						
					$metaQuestionHelper = $questionFactory->getHelper($metaSurveyObject['SurveyObject']['type']);
						
					$data = $this->request->data;
					$field_name = $metaSurveyObject['SurveyObject']['id'].'_answer';
					
					$valid = true;
					
					if (isset($data['Public'][$field_name])) {
						$answerValue = $metaQuestionHelper->convert($this->request->data, $metaSurveyObjectAttributes);
						$valid = $metaQuestionHelper->validate($answerValue, $metaSurveyObjectAttributes, $validationError);
					}
					if (!$valid)
					{
						break;
					}
				}
			}
			else
			{
				$answerValue = $questionHelper->convert($this->request->data, $surveyObjectAttributes);
				$valid = $questionHelper->validate($answerValue, $surveyObjectAttributes, $validationError);
			}
			
			// Only move to next if validation passes
			if ($valid) {
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
			
			// check if next is calculation - skip calculations if going backwards
			if ($next) {
				$nextSurveyObjectInstance = $this->SurveyInstanceObject->read(null, $next);
				$nextSurveyObject = $this->SurveyObject->read(null, $nextSurveyObjectInstance['SurveyInstanceObject']['survey_object_id']);
				while ($next && $nextSurveyObject['SurveyObject']['type'] == '10') {
					$next = array_pop($breadcrumb);
					$this->Session->write('Participant.breadcrumb', $breadcrumb);
					$nextSurveyObjectInstance = $this->SurveyInstanceObject->read(null, $next);
					$nextSurveyObject = $this->SurveyObject->read(null, $nextSurveyObjectInstance['SurveyInstanceObject']['survey_object_id']);
				}
			}

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
		$surveyObjectInstance = $this->SurveyInstanceObject->read(null, $survey_object_instance_id);
		$surveyObject = $this->SurveyObject->read(null, $surveyObjectInstance['SurveyInstanceObject']['survey_object_id']);
		$originalSurveyObject = $surveyObject;
		$surveyObjectAttributes = $this->SurveyObjectAttribute->find('all',
				array('order' => array('SurveyObjectAttribute.id'), 'conditions' => array('survey_object_id' => $surveyObject['SurveyObject']['id'])));
		$survey = $this->Survey->read(null, $surveyObject['SurveyObject']['survey_id']);
		$surveyResult = $this->SurveyResult->read(null, $survey_result_id);
		$surveyInstance = $this->SurveyInstance->read(null, $surveyResult['SurveyResult']['survey_instance_id']);
		$surveyResultAnswer = $this->SurveyResultAnswer->find('first',
				array('conditions' => array('survey_result_id' => $survey_result_id, 'survey_instance_object_id' => $survey_object_instance_id)));
		$surveyAttributes = $this->SurveyAttribute->find('all',
				array('order' => array('SurveyAttribute.id'), 'conditions' => array('SurveyAttribute.survey_id' => $surveyObject['Survey']['id'])));

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

			$condition_true = $this->ExpressionParser->parse($rule, $this, $survey_result_id, $survey_object_instance_id);

			if ($surveyResult['SurveyResult']['test'] == false)
			{
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
				$displayQuestion = true;

				$questionText = "<h3>Branch Preview - ".$surveyObject['SurveyObject']['name']."</h3>";
				$questionText .= "<br><br>Rule: ".$rule."<br>";
				$questionText .= "Positive Destination: ".$posObjectInstance['SurveyObject']['name']."<br>";
				$questionText .= "Negative Destination: ".$negObjectInstance['SurveyObject']['name']."<br><br>";

				if ($condition_true)
				{
					$questionText .= "Result: true";
					$surveyResultAnswer = $posObjectInstance['SurveyInstanceObject']['id'];
				}
				else
				{
					$questionText .= "Result: false";
					$surveyResultAnswer = $negObjectInstance['SurveyInstanceObject']['id'];
				}
				$surveyObjectAttributes[0]['SurveyObjectAttribute']['value'] = $questionText;
			}
		}
		// TODO: There has to be a nicer way to handle this hook into the calculator logic
		if ($surveyObject['SurveyObject']['type'] == '10')
		{
			$calculationString = $surveyObjectAttributes[0]['SurveyObjectAttribute']['value'];
			$errorString = $surveyObjectAttributes[2]['SurveyObjectAttribute']['value'];
			if (!$errorString) {
				$errorString = 'error';
			}

			$calculatedValue = $this->ExpressionParser->parse($calculationString, $this, $survey_result_id, $survey_object_instance_id);
			if ($calculatedValue === FALSE) {
				$calculatedValue = $errorString;
			}

			// create object to save answer
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
			$this->SurveyResultAnswer->save($surveyResultAnswer);

			// Only display question if javascript function name entered
			$displayQuestion = strlen($surveyObjectAttributes[1]['SurveyObjectAttribute']['value']) > 0;
			if (!$displayQuestion) {
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

		//TODO: There has to be a nicer way to handle this hook into meta question logic
		if ($surveyObject['SurveyObject']['type'] == '12')
		{
			$objects = $surveyObjectAttributes[0]['SurveyObjectAttribute']['value'];

			if (count($surveyObjectAttributes) > 1 && $surveyObjectAttributes[1]['SurveyObjectAttribute']['value'] != "")
				$conditions = explode("|", $surveyObjectAttributes[1]['SurveyObjectAttribute']['value']);
			else
				$conditions = false;

			$cnt = 0;
			
			$surveyObject = array();
			$surveyObjectAttributes = array();
			
			$at_least_one = false;
			
			foreach (explode("|", $objects) as $object_name)
			{			
				$metaSurveyObject = $this->SurveyObject->find('first', array('conditions' => array('SurveyObject.name' => $object_name, 'SurveyObject.survey_id' => $survey['Survey']['id'])));
				
				$metaSurveyObjectAttributes = $this->SurveyObjectAttribute->find('all',
						array('order' => array('SurveyObjectAttribute.id'), 'conditions' => array('survey_object_id' => $metaSurveyObject['SurveyObject']['id'])));			
			
				if ($conditions && count($conditions) > $cnt && $conditions[$cnt] != '')
				{
					$condition = $conditions[$cnt];
					
					$condition_true = $this->ExpressionParser->parse($condition, $this, $survey_result_id, $survey_object_instance_id);
				}
				else 
				{
					$condition_true = true;
				}
				
				if ($condition_true)
				{
					$surveyObject[] = $metaSurveyObject;
					$surveyObjectAttributes[] = $metaSurveyObjectAttributes;
					
					$at_least_one = true;
				}
				else
				{
					$surveyObject[] = false;
					$surveyObjectAttributes[] = false;
				}
				
				$cnt++;
			}
			
			if (!$at_least_one)
			{
				$displayQuestion = false;
				
				$next = $this->SurveyInstanceObject->find('first',
						array('conditions' => array('survey_instance_id' => $surveyObjectInstance['SurveyInstanceObject']['survey_instance_id'],
								'order >' => $surveyObjectInstance['SurveyInstanceObject']['order']),
								'order' => 'SurveyInstanceObject.order'));
				
				if ($next)
					$this->redirect(array('action' => 'question', $survey_result_id, $next['SurveyInstanceObject']['id']));
				else
					$this->redirect(array('action' => 'complete', $survey_result_id));
			}
			
			$this->set('meta', 'true');
		}
		
		// Check if this object requires a component assist
		$componentHelper = $this->SurveyObjectFactory->getComponent($originalSurveyObject['SurveyObject']['type']);
		
		if ($componentHelper)
		{
			$surveyObjectAttributes = $componentHelper->augment($surveyObjectAttributes, $surveyObjectInstance, $surveyInstance, $surveyResult, $this);
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

<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
App::uses('Survey', 'Model');
/**
 * Public Controller
 *
 * @property Public $Public
 */
class PublicController extends AppController {
	public $uses = array('Survey', 'SurveyObject', 'SurveyResult', 'SurveyInstanceObject', 'SurveyResultAnswer', 'SurveyObjectAttribute', 'Participant');
	var $helpers = array('Html', 'Form', 'Question');

/**
 * logout method
 * 
 * @return void
 */
	public function logout()
	{
		$this->Session->delete('Participant.username');
		
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * login method
 * 
 * @return void
 */
	public function login($survey_short_name = null)
	{
		//TODO: The participant login function needs a security review
		
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
	}
	
/**
 * index method
 *
 * @return void
 */
	public function index($survey_short_name = null) {
		// TODO: Implement support for authenticated and identified surveys in public controller
		
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
				if ($survey['Survey']['type'] == Survey::type_anonymous)
				{
					$this->SurveyResult->create();
					$data = array();
					$data['SurveyResult']['survey_instance_id'] = $survey['Survey']['live_instance'];
					// TODO: This is coming out as all 0's
					$data['SurveyResult']['date'] = date('Y-m-d h:i:s');
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
					
					$this->SurveyResult->create();
					$data = array();
					$data['SurveyResult']['participant_id'] = $session_user['Participant']['id'];
					$data['SurveyResult']['survey_instance_id'] = $survey['Survey']['live_instance'];
					// TODO: This is coming out as all 0's
					$data['SurveyResult']['date'] = date();
					$this->SurveyResult->save($data);
					
					$firstObject = $this->SurveyInstanceObject->find('first',
					array('conditions' => array('survey_instance_id' => $survey['Survey']['live_instance']),
											  'order' => 'SurveyInstanceObject.order'));
					
					$this->redirect(array('action' => 'question', $this->SurveyResult->getLastInsertID(), $firstObject['SurveyInstanceObject']['id']));
					
				}
				else
				{
					$this->redirect(array('action' => 'login', $survey_short_name));
				}
			}
		}
	}

/**
* answer method
*
* @return void
*/
	public function answer() {
		$surveyResultAnswer = $this->SurveyResultAnswer->create();
		$direction = $this->request->data['Public']['direction'];
		$data = array();
		$data['SurveyResultAnswer'] = $this->request->data['Public'];
		
		$survey_result_id = $this->request->data['Public']['survey_result_id'];
		$survey_instance_object_id = $this->request->data['Public']['survey_instance_object_id'];
		
		$surveyObjectInstance = $this->SurveyInstanceObject->read(null, $survey_instance_object_id);
		$surveyObject = $this->SurveyObject->read(null, $surveyObjectInstance['SurveyInstanceObject']['survey_object_id']);
		$surveyObjectAttributes = $this->SurveyObjectAttribute->find('all',
		array('conditions' => array('survey_object_id' => $surveyObject['SurveyObject']['id'])));
		
		// TODO: Broken MVC - find a better way to access a helper from a controller
		$view = new View($this);
		$questionFactory = $view->loadHelper('Question');
		$questionHelper = $questionFactory->getHelper($surveyObject['SurveyObject']['type']);
		
		$data['SurveyResultAnswer']['answer'] = $questionHelper->serialiseAnswer($this->request->data);
				
		if (!$this->SurveyResultAnswer->save($data)) {
			$this->Session->setFlash(__('The survey object could not be saved. Please, try again.'));
			$this->redirect(array('action' => 'question', $survey_result_id, $survey_object_instance_id));
		}	
		else
		{
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
			}
			else 
			{
				$next = $this->SurveyInstanceObject->find('first',
					array('conditions' => array('survey_instance_id' => $surveyObjectInstance['SurveyInstanceObject']['survey_instance_id'],
												'order <' => $surveyObjectInstance['SurveyInstanceObject']['order']), 
						  'order' => 'SurveyInstanceObject.order DESC'));				
			}
			
			$this->redirect(array('action' => 'question', $survey_result_id, $next['SurveyInstanceObject']['id']));
		}
	}
	
/**
* question method
* @param survey object instance ID of object to display
*
* @return void
*/
	public function question($survey_result_id = null, $survey_object_instance_id = null) {
		// TODO: Security check that if this survey is identified/authenticated that the user is still in the session, if not, login and redirect back
		// TODO: If this user, in this survey results, has answered this question before then we should pre-load the answer
		// TODO: Auto-generate a FINAL page. Remember to update the 'completed' flag and show a FINISH button that returns to their return URL
		
		$surveyObjectInstance = $this->SurveyInstanceObject->read(null, $survey_object_instance_id);
		$surveyObject = $this->SurveyObject->read(null, $surveyObjectInstance['SurveyInstanceObject']['survey_object_id']);
		$surveyObjectAttributes = $this->SurveyObjectAttribute->find('all', 
			array('conditions' => array('survey_object_id' => $surveyObject['SurveyObject']['id'])));
		$survey = $this->Survey->read(null, $surveyObject['SurveyObject']['survey_id']);
		
		$this->set('survey', $survey);
		$this->set('surveyObject', $surveyObject);
		$this->set('surveyInstanceObject', $surveyObjectInstance);
		$this->set('surveyResultID', $survey_result_id);
		$this->set('surveyObjectAttributes', $surveyObjectAttributes);
		
		$next = $this->SurveyInstanceObject->find('first',
		array('conditions' => array('survey_instance_id' => $surveyObjectInstance['SurveyInstanceObject']['survey_instance_id'],
														'order >' => $surveyObjectInstance['SurveyInstanceObject']['order']), 
								  'order' => 'SurveyInstanceObject.order'));
		
		$back = $this->SurveyInstanceObject->find('first',
		array('conditions' => array('survey_instance_id' => $surveyObjectInstance['SurveyInstanceObject']['survey_instance_id'],
														'order <' => $surveyObjectInstance['SurveyInstanceObject']['order']), 
								  'order' => 'SurveyInstanceObject.order DESC'));
		
		$this->set('hasNext', $next);
		$this->set('hasBack', $back);
		
	}
	
/**
* isAuthorized method
* @param  user the logged in user, or null if unauthenticated
*
* @return boolean representing if a user can access this controller
*/
	public function isAuthorized($user = null) {
		return true;
	}
	
/**
 * beforeFilter method
 * 
 * @return null
 */
	function beforeFilter(){
		$this->Auth->allow('*');
	}
}
?>
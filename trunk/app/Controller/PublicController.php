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
	public $uses = array('Survey', 'SurveyObject', 'SurveyResult', 'SurveyInstanceObject', 'SurveyResultAnswer', 'SurveyObjectAttribute');
	var $helpers = array('Html', 'Form', 'Question');
	
/**
 * index method
 *
 * @return void
 */
	public function index($survey_short_name = null) {
		// TODO: Implement support for authenticated and identified surveys in public controller
		
		$survey = $this->Survey->find('first', 
			array('conditions' => array('Survey.short_name' => $survey_short_name)));
		
		if (!$survey)
		{
			$this->Session->setFlash(__('Incorrect Survey Short Name'));
		}
		else
		{
			if ($survey['Survey']['type'] == Survey::type_anonymous)
			{
				$this->SurveyResult->create();
				$data = array();
				$data['SurveyResult']['survey_instance_id'] = $survey['Survey']['live_instance'];
				$data['SurveyResult']['date'] = date();
				$this->SurveyResult->save($data);
				
				$firstObject = $this->SurveyInstanceObject->find('first', 
					array('conditions' => array('survey_instance_id' => $survey['Survey']['live_instance']), 
						  'order' => 'SurveyInstanceObject.order'));
				
				$this->redirect(array('action' => 'question', $this->SurveyResult->getLastInsertID(), $firstObject['SurveyInstanceObject']['id']));
				
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
		$data = array();
		$data['SurveyResultAnswer'] = $this->request->data['Public'];
		
		$survey_result_id = $this->request->data['Public']['survey_result_id'];
		$survey_object_instance_id = $this->request->data['Public']['survey_object_instance_id'];
		
		$surveyObjectInstance = $this->SurveyInstanceObject->read(null, $survey_object_instance_id);
		$surveyObject = $this->SurveyObject->read(null, $surveyObjectInstance['SurveyInstanceObject']['survey_object_id']);
		
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
			$next = $this->SurveyInstanceObject->find('first', 
				array('conditions' => array('survey_instance_id' => $surveyObjectInstance['SurveyInstanceObject']['survey_instance_id'], 
											'order >' => $surveyObjectInstance['SurveyInstanceObject']['order']), 
					  'order' => 'SurveyInstanceObject.order'));
			
			if (!$next)
			{
				// TODO: Decide where to redirect the user after the survey is finished
				$this->redirect(array('controller' => 'public', 'action' => 'index'));
			}
			else
			{
				$this->redirect(array('action' => 'question', $survey_result_id, $next['SurveyInstanceObject']['id']));
			}
		}
	}
	
/**
* question method
* @param survey object instance ID of object to display
*
* @return void
*/
	public function question($survey_result_id = null, $survey_object_instance_id = null) {
		$surveyObjectInstance = $this->SurveyInstanceObject->read(null, $survey_object_instance_id);
		$surveyObject = $this->SurveyObject->read(null, $surveyObjectInstance['SurveyInstanceObject']['survey_object_id']);
		$surveyObjectAttributes = $this->SurveyObjectAttribute->find('all', 
			array('conditions' => array('survey_object_id' => $surveyObject['SurveyObject']['id'])));
		
		$this->set('surveyObject', $surveyObject);
		$this->set('surveyInstanceObject', $surveyObjectInstance);
		$this->set('surveyResultID', $survey_result_id);
		$this->set('surveyObjectAttributes', $surveyObjectAttributes);
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
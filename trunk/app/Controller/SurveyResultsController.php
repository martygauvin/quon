<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');


/**
 * SurveyResults Controller
 *
 * @property SurveyResult $SurveyResult
 */
class SurveyResultsController extends AppController {
	public $uses = array('SurveyResult', 'SurveyInstance', 'Survey', 'User', 'SurveyResultAnswer');
	
// TODO: Export to CSV
	
/**
 * index method
 *
 * @return void
 */
	public function index($survey_instance_id = null) {
		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyInstance = $this->SurveyInstance->read(null, $survey_instance_id);
		$survey = $this->Survey->read(null, $surveyInstance['SurveyInstance']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}	
		
		$this->SurveyResult->recursive = 0;
		$this->paginate = array('conditions' => array('SurveyInstance.id' => $survey_instance_id));
		
		$this->set('survey', $survey);
		$this->set('surveyInstance', $surveyInstance);
		$this->set('surveyResults', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->SurveyResult->id = $id;
		
		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyResult = $this->SurveyResult->read(null, $id);
		$surveyInstance = $this->SurveyInstance->read(null, $surveyResult['SurveyResult']['survey_instance_id']);
		$survey = $this->Survey->read(null, $surveyInstance['SurveyInstance']['survey_id']);
		$surveyResultAnswers = $this->SurveyResultAnswer->find('all', array('recursive' => 2, 'conditions' => array('survey_result_id' => $id)));
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}
		
		if (!$this->SurveyResult->exists()) {
			throw new NotFoundException(__('Invalid survey result'));
		}
		$this->set('surveyResult', $this->SurveyResult->read(null, $id));
		$this->set('surveyResultAnswers', $surveyResultAnswers);
	}

	
	/**
	* isAuthorized method
	* @param  user the logged in user, or null if unauthenticated
	*
	* @return boolean representing if a user can access this controller
	*/
	public function isAuthorized($user = null) {
		if ($user != null && $user['type'] == User::type_admin)
			return false;
		else if ($user != null && $user['type'] == User::type_researcher)
			return true;
		else
			return false;
	}
}

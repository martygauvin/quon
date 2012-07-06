<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');


/**
 * SurveyResults Controller.
 * @package Controller
 * @property SurveyResult $SurveyResult
 */
class SurveyResultsController extends AppController {
	public $uses = array('SurveyResult', 'SurveyInstance', 'SurveyInstanceObject', 'Survey', 'User', 'SurveyResultAnswer');

	/**
	 * export method.
	 * 
	 * Exports survey results to a file.
	 * 
	 * @param int $survey_instance_id The id of the SurveyInstance to export the results for
	 */
	public function export($survey_instance_id = null) {
		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyInstance = $this->SurveyInstance->read(null, $survey_instance_id);
		$survey = $this->Survey->read(null, $surveyInstance['SurveyInstance']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}

		// Disable layout engine and debugging
		$this->layout = "";

		$this->set('survey_instance_id', $survey_instance_id);

		$objects = $this->SurveyInstanceObject->find('all', array('recursive' => 2, 'order' => 'SurveyInstanceObject.order', 'conditions' => array('SurveyInstance.id' => $survey_instance_id)));
		$results = $this->SurveyResult->find('all', array('conditions' => array('SurveyInstance.id' => $survey_instance_id, 'SurveyResult.test' => false)));

		$resultSet = array();
		foreach ($results as $result)
		{
			$resultItem = $result;
			$resultItem['SurveyResultAnswers'] = $this->SurveyResultAnswer->find('all', array('recursive' => 2, 'order' => 'SurveyInstanceObject.order',
					'conditions' => array('SurveyResult.id' => $result['SurveyResult']['id'])));
				
			$resultSet[] = $resultItem;
		}

		$this->set('objects', $objects);
		$this->set('results', $resultSet);

	}
	
	/**
	 * index method.
	 * 
	 * Lists SurveyResults for the survey instance with the given id.
	 * 
	 * @param int $survey_instance_id The id of the survey instance to list results for
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
		$this->paginate = array('conditions' => array('SurveyInstance.id' => $survey_instance_id,
				'SurveyResult.test' => false));

		$this->set('survey', $survey);
		$this->set('surveyInstance', $surveyInstance);
		$this->set('surveyResults', $this->paginate());
	}

	/**
	 * view method.
	 * 
	 * Displays the survey result with the given id
	 * 
	 * @param int $id The id of the survey result to display
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
	 * isAuthorized method.
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

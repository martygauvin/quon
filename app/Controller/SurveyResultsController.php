<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');


/**
 * SurveyResults Controller
 *
 * @property SurveyResult $SurveyResult
 */
class SurveyResultsController extends AppController {
	public $uses = array('SurveyResult', 'SurveyInstance', 'Survey', 'User');
	

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
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}
		
		if (!$this->SurveyResult->exists()) {
			throw new NotFoundException(__('Invalid survey result'));
		}
		$this->set('surveyResult', $this->SurveyResult->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SurveyResult->create();
			if ($this->SurveyResult->save($this->request->data)) {
				$this->Session->setFlash(__('The survey result has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The survey result could not be saved. Please, try again.'));
			}
		}
		$surveyInstances = $this->SurveyResult->SurveyInstance->find('list');
		$participants = $this->SurveyResult->Participant->find('list');
		$this->set(compact('surveyInstances', 'participants'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->SurveyResult->id = $id;
		if (!$this->SurveyResult->exists()) {
			throw new NotFoundException(__('Invalid survey result'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SurveyResult->save($this->request->data)) {
				$this->Session->setFlash(__('The survey result has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The survey result could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->SurveyResult->read(null, $id);
		}
		$surveyInstances = $this->SurveyResult->SurveyInstance->find('list');
		$participants = $this->SurveyResult->Participant->find('list');
		$this->set(compact('surveyInstances', 'participants'));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->SurveyResult->id = $id;
		if (!$this->SurveyResult->exists()) {
			throw new NotFoundException(__('Invalid survey result'));
		}
		if ($this->SurveyResult->delete()) {
			$this->Session->setFlash(__('Survey result deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Survey result was not deleted'));
		$this->redirect(array('action' => 'index'));
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

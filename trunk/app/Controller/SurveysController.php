<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
/**
 * Surveys Controller
 *
 * @property Survey $Survey
 */
class SurveysController extends AppController {
	public $uses = array('Survey', 'SurveyInstance', 'User');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Survey->recursive = 0;
		$this->paginate = array(
			'conditions' => array('Survey.group_id IN (select User_Group.group_id from user_groups as User_Group where User_Group.user_id='.$this->Auth->user('id').')')		
		);
		$this->set('surveys', $this->paginate());
	}


/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			// Permission check to ensure a user is allowed to add a survey to this group
			$user = $this->User->read(null, $this->Auth->user('id'));
			if (!$this->SurveyAuthorisation->checkResearcherPermissionToGroup($user, $this->request->data['Survey']['group_id']))
			{
				$this->Session->setFlash(__('Permission Denied'));
				$this->redirect(array('action' => 'index'));
			}
			
			$success = true;
			
			$this->Survey->create();
			$this->request->data['Survey']['user_id'] = $this->Auth->user('id');
			if (!$this->Survey->save($this->request->data)) {
				$this->Session->setFlash(__('The survey could not be saved. Please, try again.'));
				$success = false;
			}
			
			$this->SurveyInstance->create();
			$surveyInstance = array();
			$surveyInstance['SurveyInstance']['survey_id'] = $this->Survey->getInsertId();
			$surveyInstance['SurveyInstance']['name'] = "1.0";
			
			if ($this->SurveyInstance->save($surveyInstance)) {
				$this->Session->setFlash(__('The survey has been saved'));
			} else {
				$this->Session->setFlash(__('The survey could not be saved. Please, try again.'));
				$success = false;
			}
						
			if ($success == true)
			{
				$this->redirect(array('action' => 'index'));
			}
		}
		$groups = $this->Survey->Group->find('list', array('conditions' => array('Group.id IN (select User_Group.group_id from user_groups as User_Group where User_Group.user_id='.$this->Auth->user('id').')')));
		$this->set(compact('groups'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Survey->id = $id;
		if (!$this->Survey->exists()) {
			throw new NotFoundException(__('Invalid survey'));
		}
		
		// Permission check to ensure a user is allowed to edit this survey 
		$user = $this->User->read(null, $this->Auth->user('id'));
		$survey = $this->Survey->read(null, $id);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Survey->save($this->request->data)) {
				$this->Session->setFlash(__('The survey has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The survey could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Survey->read(null, $id);
		}
		$groups = $this->Survey->Group->find('list');
		$this->set(compact('groups'));
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
		$this->Survey->id = $id;
		if (!$this->Survey->exists()) {
			throw new NotFoundException(__('Invalid survey'));
		}
		
		// Permission check to ensure a user is allowed to delete this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$survey = $this->Survey->read(null, $id);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->Survey->delete()) {
			$this->Session->setFlash(__('Survey deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Survey was not deleted'));
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

<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
/**
 * Surveys Controller
 *
 * @property Survey $Survey
 */
class SurveysController extends AppController {
	public $uses = array('Survey', 'SurveyInstance');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		// TODO: Filter to only show surveys belonging to groups they are part of
		$this->Survey->recursive = 0;
		$this->set('surveys', $this->paginate());
	}


/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			// TODO: Permission check to ensure a user is allowed to add a survey to this group
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
		$groups = $this->Survey->Group->find('list');
		$users = $this->Survey->User->find('list');
		$this->set(compact('groups', 'users'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		// TODO: Permissions check to ensure they are allowed to edit this survey
		$this->Survey->id = $id;
		if (!$this->Survey->exists()) {
			throw new NotFoundException(__('Invalid survey'));
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
		$users = $this->Survey->User->find('list');
		$this->set(compact('groups', 'users'));
		$this->set('survey', $this->Survey->read(null, $id));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		// TODO: Permissions check to ensure they are allowed to edit this survey
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Survey->id = $id;
		if (!$this->Survey->exists()) {
			throw new NotFoundException(__('Invalid survey'));
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

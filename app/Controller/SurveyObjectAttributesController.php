<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
/**
 * SurveyObjectAttributes Controller
 *
 * @property SurveyObjectAttribute $SurveyObjectAttribute
 */
class SurveyObjectAttributesController extends AppController {
	public $uses = array('SurveyObjectAttribute', 'SurveyObject');
	public $helpers = array('Form', 'Html', 'Js', 'Time', 'Question');

/**
 * index method
 *
 * @return void
 */
	public function index($survey_object_id) {
		// TODO: Permission check to ensure a user can view objects in this survey
		$this->SurveyObjectAttribute->recursive = 0;
		$this->paginate = array('conditions' => array('SurveyObject.id' => $survey_object_id));
		$this->set('surveyObjectAttributes', $this->paginate());
		$surveyObject = $this->SurveyObject->read(null, $survey_object_id);
		$this->set('survey_id', $surveyObject['SurveyObject']['survey_id']);
		$this->set('surveyObject', $surveyObject);
	}


/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SurveyObjectAttribute->create();
			if ($this->SurveyObjectAttribute->save($this->request->data)) {
				$this->Session->setFlash(__('The survey object attribute has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The survey object attribute could not be saved. Please, try again.'));
			}
		}
		$surveyObjects = $this->SurveyObjectAttribute->SurveyObject->find('list');
		$this->set(compact('surveyObjects'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->SurveyObjectAttribute->id = $id;
		if (!$this->SurveyObjectAttribute->exists()) {
			throw new NotFoundException(__('Invalid survey object attribute'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SurveyObjectAttribute->save($this->request->data)) {
				$this->Session->setFlash(__('The survey object attribute has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The survey object attribute could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->SurveyObjectAttribute->read(null, $id);
		}
		$surveyObjects = $this->SurveyObjectAttribute->SurveyObject->find('list');
		$this->set(compact('surveyObjects'));
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
		$this->SurveyObjectAttribute->id = $id;
		if (!$this->SurveyObjectAttribute->exists()) {
			throw new NotFoundException(__('Invalid survey object attribute'));
		}
		if ($this->SurveyObjectAttribute->delete()) {
			$this->Session->setFlash(__('Survey object attribute deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Survey object attribute was not deleted'));
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

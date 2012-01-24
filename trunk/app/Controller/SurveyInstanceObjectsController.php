<?php
App::uses('AppController', 'Controller');
/**
 * SurveyInstanceObjects Controller
 *
 * @property SurveyInstanceObject $SurveyInstanceObject
 */
class SurveyInstanceObjectsController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->SurveyInstanceObject->recursive = 0;
		$this->set('surveyInstanceObjects', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->SurveyInstanceObject->id = $id;
		if (!$this->SurveyInstanceObject->exists()) {
			throw new NotFoundException(__('Invalid survey instance object'));
		}
		$this->set('surveyInstanceObject', $this->SurveyInstanceObject->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SurveyInstanceObject->create();
			if ($this->SurveyInstanceObject->save($this->request->data)) {
				$this->Session->setFlash(__('The survey instance object has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The survey instance object could not be saved. Please, try again.'));
			}
		}
		$surveyInstances = $this->SurveyInstanceObject->SurveyInstance->find('list');
		$surveyObjects = $this->SurveyInstanceObject->SurveyObject->find('list');
		$this->set(compact('surveyInstances', 'surveyObjects'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->SurveyInstanceObject->id = $id;
		if (!$this->SurveyInstanceObject->exists()) {
			throw new NotFoundException(__('Invalid survey instance object'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SurveyInstanceObject->save($this->request->data)) {
				$this->Session->setFlash(__('The survey instance object has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The survey instance object could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->SurveyInstanceObject->read(null, $id);
		}
		$surveyInstances = $this->SurveyInstanceObject->SurveyInstance->find('list');
		$surveyObjects = $this->SurveyInstanceObject->SurveyObject->find('list');
		$this->set(compact('surveyInstances', 'surveyObjects'));
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
		$this->SurveyInstanceObject->id = $id;
		if (!$this->SurveyInstanceObject->exists()) {
			throw new NotFoundException(__('Invalid survey instance object'));
		}
		if ($this->SurveyInstanceObject->delete()) {
			$this->Session->setFlash(__('Survey instance object deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Survey instance object was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}

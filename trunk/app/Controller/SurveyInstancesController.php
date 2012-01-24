<?php
App::uses('AppController', 'Controller');
/**
 * SurveyInstances Controller
 *
 * @property SurveyInstance $SurveyInstance
 */
class SurveyInstancesController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->SurveyInstance->recursive = 0;
		$this->set('surveyInstances', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->SurveyInstance->id = $id;
		if (!$this->SurveyInstance->exists()) {
			throw new NotFoundException(__('Invalid survey instance'));
		}
		$this->set('surveyInstance', $this->SurveyInstance->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SurveyInstance->create();
			if ($this->SurveyInstance->save($this->request->data)) {
				$this->Session->setFlash(__('The survey instance has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The survey instance could not be saved. Please, try again.'));
			}
		}
		$surveys = $this->SurveyInstance->Survey->find('list');
		$this->set(compact('surveys'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->SurveyInstance->id = $id;
		if (!$this->SurveyInstance->exists()) {
			throw new NotFoundException(__('Invalid survey instance'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SurveyInstance->save($this->request->data)) {
				$this->Session->setFlash(__('The survey instance has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The survey instance could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->SurveyInstance->read(null, $id);
		}
		$surveys = $this->SurveyInstance->Survey->find('list');
		$this->set(compact('surveys'));
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
		$this->SurveyInstance->id = $id;
		if (!$this->SurveyInstance->exists()) {
			throw new NotFoundException(__('Invalid survey instance'));
		}
		if ($this->SurveyInstance->delete()) {
			$this->Session->setFlash(__('Survey instance deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Survey instance was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}

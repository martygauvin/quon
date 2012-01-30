<?php
App::uses('AppController', 'Controller');

// TODO: Clean up baked SurveyResultsController

/**
 * SurveyResults Controller
 *
 * @property SurveyResult $SurveyResult
 */
class SurveyResultsController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->SurveyResult->recursive = 0;
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
}

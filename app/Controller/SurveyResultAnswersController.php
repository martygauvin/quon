<?php
App::uses('AppController', 'Controller');

// TODO: Clean up baked SurveyResultAnswersController

/**
 * SurveyResultAnswers Controller
 *
 * @property SurveyResultAnswer $SurveyResultAnswer
 */
class SurveyResultAnswersController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->SurveyResultAnswer->recursive = 0;
		$this->set('surveyResultAnswers', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->SurveyResultAnswer->id = $id;
		if (!$this->SurveyResultAnswer->exists()) {
			throw new NotFoundException(__('Invalid survey result answer'));
		}
		$this->set('surveyResultAnswer', $this->SurveyResultAnswer->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SurveyResultAnswer->create();
			if ($this->SurveyResultAnswer->save($this->request->data)) {
				$this->Session->setFlash(__('The survey result answer has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The survey result answer could not be saved. Please, try again.'));
			}
		}
		$surveyResults = $this->SurveyResultAnswer->SurveyResult->find('list');
		$surveyObjectInstances = $this->SurveyResultAnswer->SurveyObjectInstance->find('list');
		$this->set(compact('surveyResults', 'surveyObjectInstances'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->SurveyResultAnswer->id = $id;
		if (!$this->SurveyResultAnswer->exists()) {
			throw new NotFoundException(__('Invalid survey result answer'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SurveyResultAnswer->save($this->request->data)) {
				$this->Session->setFlash(__('The survey result answer has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The survey result answer could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->SurveyResultAnswer->read(null, $id);
		}
		$surveyResults = $this->SurveyResultAnswer->SurveyResult->find('list');
		$surveyObjectInstances = $this->SurveyResultAnswer->SurveyObjectInstance->find('list');
		$this->set(compact('surveyResults', 'surveyObjectInstances'));
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
		$this->SurveyResultAnswer->id = $id;
		if (!$this->SurveyResultAnswer->exists()) {
			throw new NotFoundException(__('Invalid survey result answer'));
		}
		if ($this->SurveyResultAnswer->delete()) {
			$this->Session->setFlash(__('Survey result answer deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Survey result answer was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}

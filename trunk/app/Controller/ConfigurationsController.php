<?php
App::uses('AppController', 'Controller');

// TODO: Clean up baked ConfigurationsController

/**
 * Configurations Controller
 *
 * @property Configuration $Configuration
 */
class ConfigurationsController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Configuration->recursive = 0;
		$this->set('configurations', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Configuration->id = $id;
		if (!$this->Configuration->exists()) {
			throw new NotFoundException(__('Invalid configuration'));
		}
		$this->set('configuration', $this->Configuration->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Configuration->create();
			if ($this->Configuration->save($this->request->data)) {
				$this->Session->setFlash(__('The configuration has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The configuration could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Configuration->id = $id;
		if (!$this->Configuration->exists()) {
			throw new NotFoundException(__('Invalid configuration'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Configuration->save($this->request->data)) {
				$this->Session->setFlash(__('The configuration has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The configuration could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Configuration->read(null, $id);
		}
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
		$this->Configuration->id = $id;
		if (!$this->Configuration->exists()) {
			throw new NotFoundException(__('Invalid configuration'));
		}
		if ($this->Configuration->delete()) {
			$this->Session->setFlash(__('Configuration deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Configuration was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}

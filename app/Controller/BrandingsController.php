<?php
App::uses('AppController', 'Controller');

// TODO: Clean up baked BrandingsController

/**
 * Brandings Controller
 *
 * @property Branding $Branding
 */
class BrandingsController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Branding->recursive = 0;
		$this->set('brandings', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Branding->id = $id;
		if (!$this->Branding->exists()) {
			throw new NotFoundException(__('Invalid branding'));
		}
		$this->set('branding', $this->Branding->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Branding->create();
			if ($this->Branding->save($this->request->data)) {
				$this->Session->setFlash(__('The branding has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The branding could not be saved. Please, try again.'));
			}
		}
		$surveys = $this->Branding->Survey->find('list');
		$this->set(compact('surveys'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Branding->id = $id;
		if (!$this->Branding->exists()) {
			throw new NotFoundException(__('Invalid branding'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Branding->save($this->request->data)) {
				$this->Session->setFlash(__('The branding has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The branding could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Branding->read(null, $id);
		}
		$surveys = $this->Branding->Survey->find('list');
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
		$this->Branding->id = $id;
		if (!$this->Branding->exists()) {
			throw new NotFoundException(__('Invalid branding'));
		}
		if ($this->Branding->delete()) {
			$this->Session->setFlash(__('Branding deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Branding was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}

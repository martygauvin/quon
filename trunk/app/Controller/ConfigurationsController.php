<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');

/**
 * Configurations Controller
 *
 * @property Configuration $Configuration
 */
class ConfigurationsController extends AppController {

	var $helpers = array('Html', 'Form');

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
	 * isAuthorized method
	 * @param  user the logged in user, or null if unauthenticated
	 *
	 * @return boolean representing if a user can access this controller
	 */
	public function isAuthorized($user = null) {
		// Logging in users of any type admin access this controller
		if ($user != null)
			return $this->Auth->user('type') == User::type_admin;
		else
			return false;
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
}

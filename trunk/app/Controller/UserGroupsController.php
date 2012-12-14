<?php
/**
 * UserGroups Controller
 * @package Controller
 */
App::uses('AppController', 'Controller');
/**
 * UserGroups Controller
 * @property UserGroup $UserGroup
 */
class UserGroupsController extends AppController {


	/**
	 * index method.
	 *
	 * Lists user groups
	 */
	public function index() {
		$this->UserGroup->recursive = 0;
		$this->set('userGroups', $this->paginate());
	}

	/**
	 * view method.
	 * 
	 * Views the user group with the given id
	 *
	 * @param int $id The id of the group to view
	 */
	public function view($id = null) {
		$this->UserGroup->id = $id;
		if (!$this->UserGroup->exists()) {
			throw new NotFoundException(__('Invalid user group'));
		}
		$this->set('userGroup', $this->UserGroup->read(null, $id));
	}

	/**
	 * add method.
	 * 
	 * Adds a user group to the system when a post request is made.
	 * Otherwise displays page to enter user group information
	 */
	public function add() {
		if ($this->request->is('post')) {
			$this->UserGroup->create();
			if ($this->UserGroup->save($this->request->data)) {
				$this->Session->setFlash(__('The user group has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user group could not be saved. Please, try again.'));
			}
		}
		$users = $this->UserGroup->User->find('list');
		$groups = $this->UserGroup->Group->find('list');
		$this->set(compact('users', 'groups'));
	}

	/**
	 * edit method.
	 * 
	 * Edits a user group.
	 *
	 * @param integer $id The id of the user group to edit.
	 */
	public function edit($id = null) {
		$this->UserGroup->id = $id;
		if (!$this->UserGroup->exists()) {
			throw new NotFoundException(__('Invalid user group'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->UserGroup->save($this->request->data)) {
				$this->Session->setFlash(__('The user group has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user group could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->UserGroup->read(null, $id);
		}
		$users = $this->UserGroup->User->find('list');
		$groups = $this->UserGroup->Group->find('list');
		$this->set(compact('users', 'groups'));
	}

	/**
	 * delete method.
	 * 
	 * Removes a user from a group.
	 *
	 * @param int $id The id of the user group to delete
	 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->UserGroup->id = $id;
		if (!$this->UserGroup->exists()) {
			throw new NotFoundException(__('Invalid user group'));
		}
		if ($this->UserGroup->delete()) {
			$this->Session->setFlash(__('User group deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User group was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}

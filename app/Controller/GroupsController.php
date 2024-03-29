<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');

/**
 * Groups Controller
 * @package Controller
 * @property Group $Group
 */
class GroupsController extends AppController {
	public $uses = array('Group', 'User', 'Configuration');

	/**
	 * index method
	 *
	 * Lists groups in the system.
	 */
	public function index() {
		$this->Group->recursive = 0;
		$this->set('groups', $this->paginate());
	}

	/**
	 * add method.
	 *
	 * Adds a group to the system.
	 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Group->create();
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		} else {
			$mintURL = $this->Configuration->findByName('Mint URL');
			$queryURL = $mintURL['Configuration']['value'];
			$lookupSupported = isset($queryURL) && "" <> $queryURL;
			$this->set('lookupSupported', $lookupSupported);
		}
	}

	/**
	 * edit method.
	 * 
	 * Edits the group with the given id.
	 * 
	 * @param int $id The id of the group to edit
	 */
	public function edit($id = null) {
		$this->Group->id = $id;
		if (!$this->Group->exists()) {
			throw new NotFoundException(__('Invalid group'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Group->read(null, $id);
			$mintURL = $this->Configuration->findByName('Mint URL');
			$queryURL = $mintURL['Configuration']['value'];
			$lookupSupported = isset($queryURL) && "" <> $queryURL;
			$this->set('lookupSupported', $lookupSupported);
		}
	}

	/**
	 * users method.
	 * 
	 * Lists users in the group with the given id.
	 *
	 * @param int $id The id of the group to display the users for
	 */
	public function users($id = null) {
		$this->Group->id = $id;
		if (!$this->Group->exists()) {
			throw new NotFoundException(__('Invalid group'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Group->read(null, $id);
		}
		$users = $this->Group->User->find('list', array('order' => 'User.username'));
		$this->set(compact('users'));
	}

	/**
	 * search method.
	 *
	 * Performs a Mint lookup to find a group. Avoids cross-scripting issues by performing the search
	 * and passing results back to client.
	 */
	public function search() {
		$mintURL = $this->Configuration->findByName('Mint URL');
		$queryURL = $mintURL['Configuration']['value'];
		$query = '';
		if (isset($this->params['url']['query'])) {
			$query = $this->params['url']['query'];
		}

		$queryURL = $queryURL."/Parties_Groups/opensearch/lookup?searchTerms=".$query;
		$queryResponse = "error";

		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch,CURLOPT_URL,$queryURL);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$queryResponse = curl_exec($ch);
		curl_close($ch);

		$this->autoRender = false;
		$this->response->type('json');

		$this->response->body($queryResponse);
	}

	/**
	 * delete method.
	 * 
	 * Deletes the group with the given id.
	 *
	 * @param int $id The id of the group to delete
	 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Group->id = $id;
		if (!$this->Group->exists()) {
			throw new NotFoundException(__('Invalid group'));
		}
		if ($this->Group->delete()) {
			$this->Session->setFlash(__('Group deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Group was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * isAuthorized method.
	 * @param  user the logged in user, or null if unauthenticated
	 *
	 * @return boolean representing if a user can access this controller
	 */
	public function isAuthorized($user = null) {
		if ($user != null && $user['type'] == User::type_admin)
			return true;
		else if ($user != null && $user['type'] == User::type_researcher)
			return false;
		else
			return false;
	}
}

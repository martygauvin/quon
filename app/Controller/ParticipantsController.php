<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
/**
 * Participants Controller
 *
 * @property Participant $Participant
 */
class ParticipantsController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Participant->recursive = 0;
		$this->set('participants', $this->paginate());
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Participant->create();
			$this->request->data['Participant']['password'] = AuthComponent::password($this->request->data['Participant']['password']);
				
			if ($this->Participant->save($this->request->data)) {
				$this->Session->setFlash(__('The participant has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The participant could not be saved. Please, try again.'));
			}
		}
		$surveys = $this->Participant->Survey->find('list');
		$this->set(compact('surveys'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Participant->id = $id;
		if (!$this->Participant->exists()) {
			throw new NotFoundException(__('Invalid participant'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			// TODO: This is breaking participant authentication when the password isn't changed upon update
			$this->request->data['Participant']['password'] = AuthComponent::password($this->request->data['Participant']['password']);
				
			if ($this->Participant->save($this->request->data)) {
				$this->Session->setFlash(__('The participant has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The participant could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Participant->read(null, $id);
		}
		$surveys = $this->Participant->Survey->find('list');
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
		$this->Participant->id = $id;
		if (!$this->Participant->exists()) {
			throw new NotFoundException(__('Invalid participant'));
		}
		if ($this->Participant->delete()) {
			$this->Session->setFlash(__('Participant deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Participant was not deleted'));
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

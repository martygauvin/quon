<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			$this->request->data['User']['password'] = AuthComponent::password($this->request->data['User']['password']);
			
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->data['User']['password'] == null )
			{
				$oldVersionOfUser = $this->User->read(null, $id);
				$this->request->data['User']['password']=$oldVersionOfUser['User']['password']; // Reload user password from database if not entered
			}
			else
			{
				$this->request->data['User']['password'] = AuthComponent::password($this->request->data['User']['password']);
			}
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id); /** First load the user data */
			$this->request->data['User']['password']=null; /* Do not display the hashed password */
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * login method
 */
	public function login()
	{
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(__('Username or password is incorrect'), 'default', array(), 'auth');
			}
		}
	}

/**
* logout method
*/
	public function logout(){
	    $this->Session->setFlash('Thank You.');
	    $this->redirect($this->Auth->logout());
	}
		
	/**
	* isAuthorized method
	* @param  user the logged in user, or null if unauthenticated
	*
	* @return boolean representing if a user can access this controller
	*/
	public function isAuthorized($user = null) {
		// Anyone can access this controller to login and logout
		if ($this->action == "logout" || $this->Action == "login")
			return true;
		// Only admins can use this controller for manage users
		else if ($user != null && $user['type'] == User::type_admin)
			return true;
		else if ($user != null && $user['type'] == User::type_researcher)
			return false;
		else
			return false;
	}
}

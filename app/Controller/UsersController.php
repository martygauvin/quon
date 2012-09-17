<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
/**
 * Users Controller.
 * @package Controller
 * @property User $User
 */
class UsersController extends AppController {
	public $uses = array('User', 'Configuration');

	/**
	 * index method.
	 * 
	 * Lists users in the system
	 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	/**
	 * add method.
	 * 
	 * Adds a user to the system.
	 * User is added if a post request is used. Otherwise page to enter user details is displayed. 
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
	 * Edits a user in the system.
	 * User is saved if a post request is used. Otherwise details of the user are displayed.
	 *
	 * @param int $id The id of the user to edit
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
				
			$mintURL = $this->Configuration->findByName('Mint URL');
			$queryURL = $mintURL['Configuration']['value'];
			$lookupSupported = isset($queryURL) && "" <> $queryURL;
			$this->set('lookupSupported', $lookupSupported);
		}
	}

	/**
	 * delete method.
	 *
	 * Deletes the user with the given id only if a post request is used.
	 *
	 * @param integer $id
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
	 * login method.
	 * 
	 * Logs in the user using the Auth login component.
	 */
	public function login()
	{
		if ($this->request->is('post')) {
			if ($this->User->find('count') <= 0) {
				$this->User->create();
				$data['User']['username'] = "admin";
				$data['User']['password'] = AuthComponent::password("admin");
				$this->User->save($data);
			}
			if ($this->Auth->login()) {
				$this->Session->write('isLoggedIn', true);
				$this->Session->write('mc_rootpath', WWW_ROOT."/files/");
				$this->Session->write('mc_path', WWW_ROOT."/files/");
				$this->Session->write('imagemanager.preview.wwwroot', WWW_ROOT);
				$this->Session->write('imagemanager.preview.urlprefix', Router::url( "/", true ));
				return $this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(__('Username or password is incorrect'), 'default', array(), 'auth');
			}
		}
	}

	/**
	 * logout method.
	 * 
	 * Logs the user out of the system.
	 */
	public function logout(){
		$this->Session->setFlash('Thank You.');
		$this->redirect($this->Auth->logout());
	}

	/**
	 * Performs a search for people in the configured Mint.
	 * 
	 * To avoid Javascript cross-scripting problems, server requests data from Mint
	 * and returns results to user of local system
	 */
	public function search() {
		$mintURL = $this->Configuration->findByName('Mint URL');
		$queryURL = $mintURL['Configuration']['value'];
		$query = '';
		if (isset($this->params['url']['query'])) {
			$query = $this->params['url']['query'];
		}

		$queryURL = $queryURL."/Parties_People/opensearch/lookup?searchTerms=".$query;
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
	 * isAuthorized method.
	 * 
	 * Determines whether the user is authorised to access this controller.
	 * 
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

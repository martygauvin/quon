<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
/**
 * Configurations Controller
 *
 * @property Configuration $Configuration
 */
class DashboardController extends AppController {

	var $helpers = array('Html', 'Form');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		if ($this->Auth->user('type') == User::type_admin)
			$this->set('type', 'admin');
		else
			$this->set('type', 'researcher');
	}

/**
* isAuthorized method
* @param  user the logged in user, or null if unauthenticated
*
* @return boolean representing if a user can access this controller
*/
	public function isAuthorized($user = null) {
		if ($user != null)
			return true;
		else
			return false;
	}
}
?>
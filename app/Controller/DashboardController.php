<?php
/**
 * Dashboard Controller
 * @package Controller
 */
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
/**
 * Dashboard Controller
 * @property Configuration $Configuration
 */
class DashboardController extends AppController {
	/** The helpers used.*/
	var $helpers = array('Html', 'Form');

	/**
	 * index method.
	 *
	 * Displays main page for logged in user.
	 */
	public function index() {
		if ($this->Auth->user('type') == User::type_admin)
			$this->set('type', 'admin');
		else
			$this->set('type', 'researcher');
	}

	/**
	 * isAuthorized method.
	 * @param  user the logged in user, or null if unauthenticated
	 *
	 * @return boolean representing if a user can access this controller
	 */
	public function isAuthorized($user = null) {
		// Logging in users of any type can access this controller
		if ($user != null)
			return true;
		else
			return false;
	}
}
?>
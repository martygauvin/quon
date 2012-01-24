<?php
class AppController extends Controller {
	
	public $components = array(
	    'Auth' => array(
	        'loginAction' => array(
	            'controller' => 'users',
	            'action' => 'login',
	            'plugin' => 'users'
	        ),
	        'logoutRedirect' => array(
	        	'controller' => 'users',
	        	'action' => 'login'
			),
	        'authError' => 'Authentication failure',
	        'authenticate' => array(
	        	'Form' => array('userModel' => 'User')
	        )
	    ),
	    'Session'
	);	
	
	function beforeFilter(){
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
		$this->Auth->loginRedirect = array('controller' => 'dashboard', 'action' => 'index');
		$this->Auth->authorize = 'Controller';
	}
}
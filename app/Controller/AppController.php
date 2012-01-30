<?php
class AppController extends Controller {
	
	// TODO: Review all controllers for places where we can replace ->read with recursive
	// model indexing generating by the model relationships
	
	// TODO: Review all controller redirects and view links to ensure controller references do
	// not include underscores
	
	// Default authentication component configuration. This takes affect for all
	// application components unless overridden at the component-level
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
	    'Session',
	    'SurveyAuthorisation'
	);	
	
	// Defaut authentication hook setup.
	function beforeFilter(){
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
		$this->Auth->loginRedirect = array('controller' => 'dashboard', 'action' => 'index');
		$this->Auth->authorize = 'Controller';
	}
}
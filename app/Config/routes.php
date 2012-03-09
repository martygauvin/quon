<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'dashboard', 'action' => 'index'));
	
/**
 * Custom Public Controller routes
 */
	Router::connect('/public/complete/*', array('controller' => 'public', 'action' => 'complete'));
	Router::connect('/public/question/*', array('controller' => 'public', 'action' => 'question'));
	Router::connect('/public/answer/*', array('controller' => 'public', 'action' => 'answer'));
	Router::connect('/public/login/*', array('controller' => 'public', 'action' => 'login'));
	Router::connect('/public/logout/*', array('controller' => 'public', 'action' => 'logout'));
	Router::connect('/public/*', array('controller' => 'public', 'action' => 'index'));

/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
?>

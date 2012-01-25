<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       Cake.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 */
class AppModel extends Model {
	/**
	 * Function to make paginate work properly for custom-defined finds.
	 * @see Model::_findCount()
	 */
	function _findCount($state, $query, $results = array()) {
		if ($state == 'before') {
			if (isset($query['type']) && $query['type'] != 'count') {
				$query = $this->{'_find' . ucfirst($query['type'])}($state, $query);
			}
			return parent::_findCount($state, $query);
		}
		return parent::_findCount($state, $query, $results);
	}
}

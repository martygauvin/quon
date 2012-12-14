<?php
/**
 * Configuration
 * @package Model
 */
App::uses('AppModel', 'Model');
/**
 * Configuration Model
 * 
 * System-wide configuration settings.
 *
 * @package Model
 */
class Configuration extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
}

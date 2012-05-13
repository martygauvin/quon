<?php
App::uses('AppModel', 'Model');
/**
 * SurveyAttribute Model
 *
 * @property Survey $Survey
 */
class SurveyAttribute extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	
	// Statics
	const attribute_logo = 'MAIN_LOGO';
	

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Survey' => array(
			'className' => 'Survey',
			'foreignKey' => 'survey_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

<?php
App::uses('AppModel', 'Model');
/**
 * SurveyObjectAttribute Model
 *
 * @property SurveyObject $SurveyObject
 */
class SurveyObjectAttribute extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'SurveyObject' => array(
			'className' => 'SurveyObject',
			'foreignKey' => 'survey_object_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

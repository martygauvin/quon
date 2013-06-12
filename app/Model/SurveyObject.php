<?php
App::uses('AppModel', 'Model');
/**
 * SurveyObject Model
 *
 * An object (question) in a survey.
 * 
 * @package Model
 * @property Survey $Survey
 * @property SurveyInstanceObject $SurveyInstanceObject
 * @property SurveyObjectAttribute $SurveyObjectAttribute
 */
class SurveyObject extends AppModel {
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
		'Survey' => array(
			'className' => 'Survey',
			'foreignKey' => 'survey_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'SurveyInstanceObject' => array(
			'className' => 'SurveyInstanceObject',
			'foreignKey' => 'survey_object_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'SurveyObjectAttribute' => array(
			'className' => 'SurveyObjectAttribute',
			'foreignKey' => 'survey_object_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}

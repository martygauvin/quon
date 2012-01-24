<?php
App::uses('AppModel', 'Model');
/**
 * SurveyInstance Model
 *
 * @property Survey $Survey
 * @property SurveyInstanceObject $SurveyInstanceObject
 * @property SurveyResult $SurveyResult
 */
class SurveyInstance extends AppModel {
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
			'foreignKey' => 'survey_instance_id',
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
		'SurveyResult' => array(
			'className' => 'SurveyResult',
			'foreignKey' => 'survey_instance_id',
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

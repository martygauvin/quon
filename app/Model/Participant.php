<?php
App::uses('AppModel', 'Model');
/**
 * Participant Model
 *
 * @property Survey $Survey
 * @property SurveyResult $SurveyResult
 */
class Participant extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'username';

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
		'SurveyResult' => array(
			'className' => 'SurveyResult',
			'foreignKey' => 'participant_id',
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

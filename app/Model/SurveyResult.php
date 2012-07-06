<?php
App::uses('AppModel', 'Model');
/**
 * SurveyResult Model
 *
 * A set of survey answers for a survey instance
 *
 * @package Model
 * @property SurveyInstance $SurveyInstance
 * @property Participant $Participant
 * @property SurveyResultAnswer $SurveyResultAnswer
 */
class SurveyResult extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'SurveyInstance' => array(
			'className' => 'SurveyInstance',
			'foreignKey' => 'survey_instance_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Participant' => array(
			'className' => 'Participant',
			'foreignKey' => 'participant_id',
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
		'SurveyResultAnswer' => array(
			'className' => 'SurveyResultAnswer',
			'foreignKey' => 'survey_result_id',
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

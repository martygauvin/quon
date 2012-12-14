<?php
/**
 * SurveyResultAnswer
 * @package Model
 */
App::uses('AppModel', 'Model');
/**
 * SurveyResultAnswer Model
 *
 * An actual answer to a survey question.
 *
 * @package Model
 * @property SurveyResult $SurveyResult
 * @property SurveyObjectInstance $SurveyObjectInstance
 */
class SurveyResultAnswer extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'SurveyResult' => array(
			'className' => 'SurveyResult',
			'foreignKey' => 'survey_result_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'SurveyInstanceObject' => array(
			'className' => 'SurveyInstanceObject',
			'foreignKey' => 'survey_instance_object_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

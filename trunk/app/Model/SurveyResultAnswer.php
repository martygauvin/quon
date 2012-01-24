<?php
App::uses('AppModel', 'Model');
/**
 * SurveyResultAnswer Model
 *
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
		'SurveyObjectInstance' => array(
			'className' => 'SurveyObjectInstance',
			'foreignKey' => 'survey_object_instance_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

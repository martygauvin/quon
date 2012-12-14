<?php
/**
 * SurveyInstanceObject
 * @package Model
 */
App::uses('AppModel', 'Model');
/**
 * SurveyInstanceObject Model
 * 
 * An survey object associated with a survey instance
 * 
 * @package Model
 * @property SurveyInstance $SurveyInstance
 * @property SurveyObject $SurveyObject
 */
class SurveyInstanceObject extends AppModel {

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
		'SurveyObject' => array(
			'className' => 'SurveyObject',
			'foreignKey' => 'survey_object_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

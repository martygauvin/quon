<?php
/**
 * SurveyMetadataUser
 * @package Model
 */
App::uses('AppModel', 'Model');
/**
 * SurveyMetadataUser Model
 *
 * @property SurveyMetadata $SurveyMetadata
 * @property User $User
 */
class SurveyMetadataUser extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'SurveyMetadata' => array(
			'className' => 'SurveyMetadata',
			'foreignKey' => 'survey_metadata_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

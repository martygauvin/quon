<?php
/**
 * SurveyMetadata
 * @package Model
 */
App::uses('AppModel', 'Model');
/**
 * SurveyMetadata Model
 *
 * @property Survey $Survey
 * @property SurveyMetadataUser $SurveyMetadataUser
 */
class SurveyMetadata extends AppModel {

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
	public $hasAndBelongsToMany = array(
		'User' => array(
			'className' => 'User',
			'joinTable' => 'survey_metadata_users',
			'associationForeignKey' => 'user_id',
			'foreignKey' => 'survey_metadata_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);

}

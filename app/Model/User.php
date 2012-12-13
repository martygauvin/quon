<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 * 
 * A user that either administers the site, or manages surveys.
 * 
 * @package Model
 * @property Survey $Survey
 * @property UserGroup $UserGroup
 * @property SurveyMetadatum $SurveyMetadatum
 */
class User extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'username';
	
	// Statics
	const type_admin = 0;
	const type_researcher = 1;
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Survey' => array(
			'className' => 'Survey',
			'foreignKey' => 'user_id',
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
		'UserGroup' => array(
			'className' => 'UserGroup',
			'foreignKey' => 'user_id',
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
	);


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'SurveyMetadata' => array(
			'className' => 'SurveyMetadata',
			'joinTable' => 'survey_metadata_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'survey_metadata_id',
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

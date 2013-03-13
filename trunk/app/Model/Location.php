<?php
/**
 * Location
 * @package Model
 */
App::uses('AppModel', 'Model');
/**
 * Location Model
 * 
 * A location for use with survey metadata.
 * 
 * @package Model
 * @property SurveyMetadatum $SurveyMetadatum
 */
class Location extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'SurveyMetadata' => array(
			'className' => 'SurveyMetadata',
			'joinTable' => 'survey_metadata_locations',
			'foreignKey' => 'location_id',
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

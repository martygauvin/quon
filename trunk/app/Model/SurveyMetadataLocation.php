<?php
App::uses('AppModel', 'Model');
/**
 * SurveyMetadataLocation Model
 *
 * @property SurveyMetadata $SurveyMetadata
 * @property Location $Location
 */
class SurveyMetadataLocation extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

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
		'Location' => array(
			'className' => 'Location',
			'foreignKey' => 'location_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

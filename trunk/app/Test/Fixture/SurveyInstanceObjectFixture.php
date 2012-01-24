<?php
/* SurveyInstanceObject Fixture generated on: 2012-01-17 15:10:43 : 1326809443 */

/**
 * SurveyInstanceObjectFixture
 *
 */
class SurveyInstanceObjectFixture extends CakeTestFixture {
/**
 * Table name
 *
 * @var string
 */
	public $table = 'survey_instance_object';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary', 'collate' => NULL, 'comment' => ''),
		'survey_instance_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'survey_object_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'order' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'survey_instance_id' => 1,
			'survey_object_id' => 1,
			'order' => 1
		),
	);
}

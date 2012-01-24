<?php
/* SurveyObject Test cases generated on: 2012-01-17 15:09:57 : 1326809397*/
App::uses('SurveyObject', 'Model');

/**
 * SurveyObject Test Case
 *
 */
class SurveyObjectTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.survey_object', 'app.survey', 'app.group', 'app.user', 'app.user_group', 'app.branding', 'app.participant', 'app.survey_instance', 'app.survey_instance_object', 'app.survey_object_attribute');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->SurveyObject = ClassRegistry::init('SurveyObject');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SurveyObject);

		parent::tearDown();
	}

}

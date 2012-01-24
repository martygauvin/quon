<?php
/* SurveyInstanceObject Test cases generated on: 2012-01-17 15:10:43 : 1326809443*/
App::uses('SurveyInstanceObject', 'Model');

/**
 * SurveyInstanceObject Test Case
 *
 */
class SurveyInstanceObjectTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.survey_instance_object', 'app.survey_instance', 'app.survey', 'app.group', 'app.user', 'app.user_group', 'app.branding', 'app.participant', 'app.survey_object', 'app.survey_object_attribute', 'app.survey_result');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->SurveyInstanceObject = ClassRegistry::init('SurveyInstanceObject');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SurveyInstanceObject);

		parent::tearDown();
	}

}

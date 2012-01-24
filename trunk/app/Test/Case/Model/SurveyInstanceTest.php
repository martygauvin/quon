<?php
/* SurveyInstance Test cases generated on: 2012-01-17 15:10:59 : 1326809459*/
App::uses('SurveyInstance', 'Model');

/**
 * SurveyInstance Test Case
 *
 */
class SurveyInstanceTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.survey_instance', 'app.survey', 'app.group', 'app.user', 'app.user_group', 'app.branding', 'app.participant', 'app.survey_object', 'app.survey_instance_object', 'app.survey_object_attribute', 'app.survey_result');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->SurveyInstance = ClassRegistry::init('SurveyInstance');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SurveyInstance);

		parent::tearDown();
	}

}

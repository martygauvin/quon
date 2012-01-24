<?php
/* SurveyResult Test cases generated on: 2012-01-17 15:11:53 : 1326809513*/
App::uses('SurveyResult', 'Model');

/**
 * SurveyResult Test Case
 *
 */
class SurveyResultTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.survey_result', 'app.survey_instance', 'app.survey', 'app.group', 'app.user', 'app.user_group', 'app.branding', 'app.participant', 'app.survey_object', 'app.survey_instance_object', 'app.survey_object_attribute', 'app.survey_result_answer');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->SurveyResult = ClassRegistry::init('SurveyResult');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SurveyResult);

		parent::tearDown();
	}

}

<?php
/* SurveyResultAnswer Test cases generated on: 2012-01-17 15:11:59 : 1326809519*/
App::uses('SurveyResultAnswer', 'Model');

/**
 * SurveyResultAnswer Test Case
 *
 */
class SurveyResultAnswerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.survey_result_answer', 'app.survey_result', 'app.survey_instance', 'app.survey', 'app.group', 'app.user', 'app.user_group', 'app.branding', 'app.participant', 'app.survey_object', 'app.survey_instance_object', 'app.survey_object_attribute', 'app.survey_object_instance');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->SurveyResultAnswer = ClassRegistry::init('SurveyResultAnswer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SurveyResultAnswer);

		parent::tearDown();
	}

}

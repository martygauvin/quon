<?php
/* SurveyResultAnswers Test cases generated on: 2012-01-17 15:16:19 : 1326809779*/
App::uses('SurveyResultAnswersController', 'Controller');

/**
 * TestSurveyResultAnswersController *
 */
class TestSurveyResultAnswersController extends SurveyResultAnswersController {
/**
 * Auto render
 *
 * @var boolean
 */
	public $autoRender = false;

/**
 * Redirect action
 *
 * @param mixed $url
 * @param mixed $status
 * @param boolean $exit
 * @return void
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

/**
 * SurveyResultAnswersController Test Case
 *
 */
class SurveyResultAnswersControllerTestCase extends CakeTestCase {
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

		$this->SurveyResultAnswers = new TestSurveyResultAnswersController();
		$this->SurveyResultAnswers->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SurveyResultAnswers);

		parent::tearDown();
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {

	}

/**
 * testView method
 *
 * @return void
 */
	public function testView() {

	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {

	}

/**
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {

	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {

	}

}

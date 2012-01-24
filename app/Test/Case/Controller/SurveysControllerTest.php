<?php
/* Surveys Test cases generated on: 2012-01-17 15:15:22 : 1326809722*/
App::uses('SurveysController', 'Controller');

/**
 * TestSurveysController *
 */
class TestSurveysController extends SurveysController {
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
 * SurveysController Test Case
 *
 */
class SurveysControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.survey', 'app.group', 'app.user', 'app.user_group', 'app.branding', 'app.participant', 'app.survey_result', 'app.survey_instance', 'app.survey_instance_object', 'app.survey_object', 'app.survey_object_attribute', 'app.survey_result_answer', 'app.survey_object_instance');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Surveys = new TestSurveysController();
		$this->Surveys->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Surveys);

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

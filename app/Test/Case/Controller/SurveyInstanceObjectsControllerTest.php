<?php
/* SurveyInstanceObjects Test cases generated on: 2012-01-17 15:18:11 : 1326809891*/
App::uses('SurveyInstanceObjectsController', 'Controller');

/**
 * TestSurveyInstanceObjectsController *
 */
class TestSurveyInstanceObjectsController extends SurveyInstanceObjectsController {
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
 * SurveyInstanceObjectsController Test Case
 *
 */
class SurveyInstanceObjectsControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.survey_instance_object', 'app.survey_instance', 'app.survey', 'app.group', 'app.user', 'app.user_group', 'app.branding', 'app.participant', 'app.survey_result', 'app.survey_result_answer', 'app.survey_object_instance', 'app.survey_object', 'app.survey_object_attribute');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->SurveyInstanceObjects = new TestSurveyInstanceObjectsController();
		$this->SurveyInstanceObjects->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SurveyInstanceObjects);

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

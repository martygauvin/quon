<?php
/* Participants Test cases generated on: 2012-01-17 15:15:09 : 1326809709*/
App::uses('ParticipantsController', 'Controller');

/**
 * TestParticipantsController *
 */
class TestParticipantsController extends ParticipantsController {
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
 * ParticipantsController Test Case
 *
 */
class ParticipantsControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.participant', 'app.survey', 'app.group', 'app.user', 'app.user_group', 'app.branding', 'app.survey_instance', 'app.survey_instance_object', 'app.survey_object', 'app.survey_object_attribute', 'app.survey_result', 'app.survey_result_answer', 'app.survey_object_instance');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Participants = new TestParticipantsController();
		$this->Participants->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Participants);

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

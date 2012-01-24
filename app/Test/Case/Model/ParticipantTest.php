<?php
/* Participant Test cases generated on: 2012-01-17 15:11:26 : 1326809486*/
App::uses('Participant', 'Model');

/**
 * Participant Test Case
 *
 */
class ParticipantTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.participant', 'app.survey', 'app.group', 'app.user', 'app.user_group', 'app.branding', 'app.survey_instance', 'app.survey_instance_object', 'app.survey_object', 'app.survey_object_attribute', 'app.survey_result');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Participant = ClassRegistry::init('Participant');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Participant);

		parent::tearDown();
	}

}

<?php
/* Survey Test cases generated on: 2012-01-17 15:08:45 : 1326809325*/
App::uses('Survey', 'Model');

/**
 * Survey Test Case
 *
 */
class SurveyTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.survey', 'app.group', 'app.user', 'app.user_group', 'app.branding', 'app.participant', 'app.survey_instance', 'app.survey_object');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Survey = ClassRegistry::init('Survey');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Survey);

		parent::tearDown();
	}

}

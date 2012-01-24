<?php
/* SurveyObjectAttribute Test cases generated on: 2012-01-17 15:10:08 : 1326809408*/
App::uses('SurveyObjectAttribute', 'Model');

/**
 * SurveyObjectAttribute Test Case
 *
 */
class SurveyObjectAttributeTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.survey_object_attribute', 'app.survey_object', 'app.survey', 'app.group', 'app.user', 'app.user_group', 'app.branding', 'app.participant', 'app.survey_instance', 'app.survey_instance_object');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->SurveyObjectAttribute = ClassRegistry::init('SurveyObjectAttribute');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SurveyObjectAttribute);

		parent::tearDown();
	}

}

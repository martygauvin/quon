<?php
/* UserGroups Test cases generated on: 2012-01-17 15:14:58 : 1326809698*/
App::uses('UserGroups', 'Controller');

/**
 * TestUserGroups *
 */
class TestUserGroups extends UserGroups {
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
 * UserGroups Test Case
 *
 */
class UserGroupsTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.group', 'app.survey', 'app.user', 'app.user_group', 'app.branding', 'app.participant', 'app.survey_result', 'app.survey_instance', 'app.survey_instance_object', 'app.survey_object', 'app.survey_object_attribute', 'app.survey_result_answer', 'app.survey_object_instance', '');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->UserGroups = new TestUserGroups();
		$this->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UserGroups);

		parent::tearDown();
	}

}

<?php
/* UserGroup Test cases generated on: 2012-01-17 15:07:40 : 1326809260*/
App::uses('UserGroup', 'Model');

/**
 * UserGroup Test Case
 *
 */
class UserGroupTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.user_group', 'app.user', 'app.survey', 'app.group');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->UserGroup = ClassRegistry::init('UserGroup');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UserGroup);

		parent::tearDown();
	}

}

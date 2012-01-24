<?php
/* Configuration Test cases generated on: 2012-01-17 15:07:50 : 1326809270*/
App::uses('Configuration', 'Model');

/**
 * Configuration Test Case
 *
 */
class ConfigurationTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.configuration');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Configuration = ClassRegistry::init('Configuration');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Configuration);

		parent::tearDown();
	}

}

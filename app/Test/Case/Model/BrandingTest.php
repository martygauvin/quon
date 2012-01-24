<?php
/* Branding Test cases generated on: 2012-01-17 15:08:03 : 1326809283*/
App::uses('Branding', 'Model');

/**
 * Branding Test Case
 *
 */
class BrandingTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.branding', 'app.survey');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Branding = ClassRegistry::init('Branding');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Branding);

		parent::tearDown();
	}

}

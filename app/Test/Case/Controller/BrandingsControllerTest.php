<?php
/* Brandings Test cases generated on: 2012-01-17 15:13:10 : 1326809590*/
App::uses('BrandingsController', 'Controller');

/**
 * TestBrandingsController *
 */
class TestBrandingsController extends BrandingsController {
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
 * BrandingsController Test Case
 *
 */
class BrandingsControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.branding', 'app.survey', 'app.group', 'app.user', 'app.user_group', 'app.participant', 'app.survey_result', 'app.survey_instance', 'app.survey_instance_object', 'app.survey_object', 'app.survey_object_attribute', 'app.survey_result_answer', 'app.survey_object_instance');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Brandings = new TestBrandingsController();
		$this->Brandings->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Brandings);

		parent::tearDown();
	}

}

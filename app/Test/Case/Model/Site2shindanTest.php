<?php
App::uses('Site2shindan', 'Model');

/**
 * Site2shindan Test Case
 *
 */
class Site2shindanTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.site2shindan'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Site2shindan = ClassRegistry::init('Site2shindan');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Site2shindan);

		parent::tearDown();
	}

}

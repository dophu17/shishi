<?php
App::uses('ShindanHistory', 'Model');

/**
 * ShindanHistory Test Case
 *
 */
class ShindanHistoryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.shindan_history'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ShindanHistory = ClassRegistry::init('ShindanHistory');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ShindanHistory);

		parent::tearDown();
	}

}

<?php
App::uses('Shindan2total', 'Model');

/**
 * Shindan2total Test Case
 *
 */
class Shindan2totalTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.shindan2total'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Shindan2total = ClassRegistry::init('Shindan2total');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Shindan2total);

		parent::tearDown();
	}

}

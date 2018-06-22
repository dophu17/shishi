<?php
App::uses('Shindan', 'Model');

/**
 * Shindan Test Case
 *
 */
class ShindanTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.shindan'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Shindan = ClassRegistry::init('Shindan');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Shindan);

		parent::tearDown();
	}

}

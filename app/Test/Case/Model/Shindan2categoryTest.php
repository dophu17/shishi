<?php
App::uses('Shindan2category', 'Model');

/**
 * Shindan2category Test Case
 *
 */
class Shindan2categoryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.shindan2category'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Shindan2category = ClassRegistry::init('Shindan2category');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Shindan2category);

		parent::tearDown();
	}

}

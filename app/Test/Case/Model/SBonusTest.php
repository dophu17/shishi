<?php
App::uses('SBonus', 'Model');

/**
 * SBonus Test Case
 *
 */
class SBonusTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.s_bonus'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SBonus = ClassRegistry::init('SBonus');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SBonus);

		parent::tearDown();
	}

}

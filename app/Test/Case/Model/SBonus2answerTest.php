<?php
App::uses('SBonus2answer', 'Model');

/**
 * SBonus2answer Test Case
 *
 */
class SBonus2answerTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.s_bonus2answer'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SBonus2answer = ClassRegistry::init('SBonus2answer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SBonus2answer);

		parent::tearDown();
	}

}

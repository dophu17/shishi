<?php
App::uses('Advice2answer', 'Model');

/**
 * Advice2answer Test Case
 *
 */
class Advice2answerTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.advice2answer'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Advice2answer = ClassRegistry::init('Advice2answer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Advice2answer);

		parent::tearDown();
	}

}

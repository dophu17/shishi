<?php
App::uses('ShindanAnswer', 'Model');

/**
 * ShindanAnswer Test Case
 *
 */
class ShindanAnswerTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.shindan_answer'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ShindanAnswer = ClassRegistry::init('ShindanAnswer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ShindanAnswer);

		parent::tearDown();
	}

}

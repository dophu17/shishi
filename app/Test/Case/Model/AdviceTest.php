<?php
App::uses('Advice', 'Model');

/**
 * Advice Test Case
 *
 */
class AdviceTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.advice',
		'app.advice2answer'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Advice = ClassRegistry::init('Advice');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Advice);

		parent::tearDown();
	}

}

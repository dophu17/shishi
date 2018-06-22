<?php
App::uses('TotalValuation', 'Model');

/**
 * TotalValuation Test Case
 *
 */
class TotalValuationTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.total_valuation'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TotalValuation = ClassRegistry::init('TotalValuation');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TotalValuation);

		parent::tearDown();
	}

}

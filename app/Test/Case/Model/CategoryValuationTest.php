<?php
App::uses('CategoryValuation', 'Model');

/**
 * CategoryValuation Test Case
 *
 */
class CategoryValuationTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.category_valuation'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CategoryValuation = ClassRegistry::init('CategoryValuation');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CategoryValuation);

		parent::tearDown();
	}

}

<?php
App::uses('ShindanCategory', 'Model');

/**
 * ShindanCategory Test Case
 *
 */
class ShindanCategoryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.shindan_category'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ShindanCategory = ClassRegistry::init('ShindanCategory');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ShindanCategory);

		parent::tearDown();
	}

}

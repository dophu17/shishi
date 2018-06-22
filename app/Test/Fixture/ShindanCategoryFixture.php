<?php
/**
 * ShindanCategoryFixture
 *
 */
class ShindanCategoryFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'shindan_category';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'c_title' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'rank' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'category_id', 'unique' => 1),
			'fk_shindan_category_shindan_category1_idx' => array('column' => 'parent_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'category_id' => 1,
			'c_title' => 'Lorem ipsum dolor sit amet',
			'parent_id' => 1,
			'rank' => 1
		),
	);

}

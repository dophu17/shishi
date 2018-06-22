<?php
/**
 * Shindan2categoryFixture
 *
 */
class Shindan2categoryFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'shindan2category';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'shindan_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'indexes' => array(
			'PRIMARY' => array('column' => array('category_id', 'shindan_id'), 'unique' => 1),
			'fk_shindan2category_shindan_category1_idx' => array('column' => 'category_id', 'unique' => 0),
			'fk_shindan2category_shindan1_idx' => array('column' => 'shindan_id', 'unique' => 0)
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
			'shindan_id' => 1
		),
	);

}

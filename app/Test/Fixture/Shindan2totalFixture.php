<?php
/**
 * Shindan2totalFixture
 *
 */
class Shindan2totalFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'shindan2total';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'st_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'ss_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'total_grade' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'st_id', 'unique' => 1)
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
			'st_id' => 1,
			'ss_id' => 1,
			'category_id' => 1,
			'total_grade' => 1
		),
	);

}

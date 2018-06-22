<?php
/**
 * SBonusFixture
 *
 */
class SBonusFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 's_bonus';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'bonus_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'b_title' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'point_flag' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'unsigned' => false),
		'point' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'bonus_id', 'unique' => 1)
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
			'bonus_id' => 1,
			'b_title' => 'Lorem ipsum dolor sit amet',
			'point_flag' => 1,
			'point' => 1
		),
	);

}

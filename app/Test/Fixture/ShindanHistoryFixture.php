<?php
/**
 * ShindanHistoryFixture
 *
 */
class ShindanHistoryFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'shindan_history';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'ss_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'total' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'create_date' => array('type' => 'timestamp', 'null' => true, 'default' => null),
		'site_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'ss_id', 'unique' => 1),
			'fk_shindan_history_site1_idx' => array('column' => 'site_id', 'unique' => 0)
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
			'ss_id' => 1,
			'total' => 1,
			'create_date' => 1402389871,
			'site_id' => 1
		),
	);

}

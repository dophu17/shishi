<?php
/**
 * ClientFixture
 *
 */
class ClientFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'client';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'client_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'c_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'c_name_kana' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'person' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'mail' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'tel' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'c_status' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'unsigned' => false),
		'create_date' => array('type' => 'timestamp', 'null' => true, 'default' => null),
		'update_date' => array('type' => 'timestamp', 'null' => true, 'default' => null),
		'del_flag' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'client_id', 'unique' => 1)
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
			'client_id' => 1,
			'c_name' => 'Lorem ipsum dolor sit amet',
			'c_name_kana' => 'Lorem ipsum dolor sit amet',
			'person' => 'Lorem ipsum dolor sit amet',
			'mail' => 'Lorem ipsum dolor sit amet',
			'tel' => 'Lorem ipsum dolor ',
			'password' => 'Lorem ipsum dolor sit amet',
			'c_status' => 1,
			'create_date' => 1402389612,
			'update_date' => 1402389612,
			'del_flag' => 1
		),
	);

}

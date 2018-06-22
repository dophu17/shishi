<?php
/**
 * SiteFixture
 *
 */
class SiteFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'site';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'site_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'client_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		's_name' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'url' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'target' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
		'kind' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
		'ec_kind' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
		'total' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'create_date' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
		'update_date' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'del_flag' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'site_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'site_id' => 1,
			'client_id' => 1,
			's_name' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'url' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'target' => 1,
			'kind' => 1,
			'ec_kind' => 1,
			'total' => 1,
			'create_date' => 1402888893,
			'update_date' => 1402888893,
			'del_flag' => 1
		),
	);

}

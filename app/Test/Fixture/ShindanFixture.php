<?php
/**
 * ShindanFixture
 *
 */
class ShindanFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'shindan';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'shindan_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		's_title' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		's_content' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'view_flag' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'unsigned' => false),
		'rank' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'shindan_id', 'unique' => 1)
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
			'shindan_id' => 1,
			's_title' => 'Lorem ipsum dolor sit amet',
			's_content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'view_flag' => 1,
			'rank' => 1
		),
	);

}

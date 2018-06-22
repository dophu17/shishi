<?php
/**
 * TotalValuationFixture
 *
 */
class TotalValuationFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'total_valuation';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'tv_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'point' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'point_min' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'point_max' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'comment' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'tv_id', 'unique' => 1)
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
			'tv_id' => 1,
			'point' => 1,
			'point_min' => 1,
			'point_max' => 1,
			'comment' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
		),
	);

}

<?php
/**
 * Site2shindanFixture
 *
 */
class Site2shindanFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'site2shindan';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'ss_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'shindan_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'answer_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			
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
			'ss_id' => 1,
			'shindan_id' => 1,
			'answer_id' => 1
		),
	);

}

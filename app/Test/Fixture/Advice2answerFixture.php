<?php
/**
 * Advice2answerFixture
 *
 */
class Advice2answerFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'advice2answer';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'advice_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'answer_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'advice_id', 'unique' => 1)
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
			'advice_id' => 1,
			'answer_id' => 1
		),
	);

}

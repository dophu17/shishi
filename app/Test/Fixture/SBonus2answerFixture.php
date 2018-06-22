<?php
/**
 * SBonus2answerFixture
 *
 */
class SBonus2answerFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 's_bonus2answer';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'bonus_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'answer_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'indexes' => array(
			'PRIMARY' => array('column' => array('bonus_id', 'answer_id'), 'unique' => 1),
			'fk_s_bonus2answer_s_bonus1_idx' => array('column' => 'bonus_id', 'unique' => 0),
			'fk_s_bonus2answer_shindan_answer1_idx' => array('column' => 'answer_id', 'unique' => 0)
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
			'answer_id' => 1
		),
	);

}

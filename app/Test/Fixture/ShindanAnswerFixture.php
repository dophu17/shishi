<?php
/**
 * ShindanAnswerFixture
 *
 */
class ShindanAnswerFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'shindan_answer';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'answer_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'shindan_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'a_title' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'a_conent' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'grade' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'unsigned' => false),
		'rank' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'answer_id', 'unique' => 1),
			'fk_shindan_answer_shindan1_idx' => array('column' => 'shindan_id', 'unique' => 0)
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
			'answer_id' => 1,
			'shindan_id' => 1,
			'a_title' => 'Lorem ipsum dolor sit amet',
			'a_conent' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'grade' => 1,
			'rank' => 1
		),
	);

}

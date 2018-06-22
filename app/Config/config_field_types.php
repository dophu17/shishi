<?php

class FieldTypes {
	const RADIO_BOX = 1;
	const CHECK_BOX = 2;
	const TEXT_BOX = 3;
	const DROP_BOX = 4;
	const MULTI_TEXT_BOX = 5;
	const TEXT_AREA = 6;
	const FILE_UPLOAD = 7;
}

class DisplayTypes {
	const HORIZONTAL = 0;
	const VERTICAL = 1;
}

$config = array(
	'types' => array(
		FieldTypes::RADIO_BOX => array(
			'file_name' => 'radio_box',		
			'type_name' => 'Radio Box'
		),
		FieldTypes::CHECK_BOX => array(
			'file_name' => 'check_box',
			'type_name' => 'Check Box'
		),
		FieldTypes::TEXT_BOX => array(
			'file_name' => 'text_box',
			'type_name' => 'Text Box'
		),
		FieldTypes::DROP_BOX => array(
			'file_name' => 'drop_box',
			'type_name' => 'Drop Box'
		),
		FieldTypes::MULTI_TEXT_BOX => array(
			'file_name' => 'multi_text_box',
			'type_name' => 'Multi Text Box'
		),
		FieldTypes::TEXT_AREA => array(
			'file_name' => 'text_area',
			'type_name' => 'Text Area'
		),
		FieldTypes::FILE_UPLOAD => array(
			'file_name' => 'file_upload',
			'type_name' => 'File Upload'
		),
	),

	'profile_types' => array(
		FieldTypes::RADIO_BOX,
		FieldTypes::CHECK_BOX,
		FieldTypes::TEXT_BOX,
		FieldTypes::DROP_BOX,
		FieldTypes::MULTI_TEXT_BOX,
		FieldTypes::TEXT_AREA,
	),

	'workstyle_types' => array(
		FieldTypes::RADIO_BOX,
		FieldTypes::CHECK_BOX,
		FieldTypes::TEXT_BOX,
		FieldTypes::DROP_BOX,
		FieldTypes::TEXT_AREA,
	),
	
	'kadai_types' => array(
		FieldTypes::RADIO_BOX,
		FieldTypes::CHECK_BOX,
		FieldTypes::TEXT_BOX,
		FieldTypes::DROP_BOX,
		FieldTypes::TEXT_AREA,
		FieldTypes::FILE_UPLOAD,
	),

	'display' => array(
		DisplayTypes::HORIZONTAL => 'Horizontal',
		DisplayTypes::VERTICAL => 'Vertical'
	),
);
<?php
/**
 * Kadai Form Helper
 *
 */

App::uses('AppHelper', 'View');

/**
 * Kadai form helper
 *
 * @package       app.View.Helper
 */
class KadaiFormHelper extends Helper {

/**
* Helpers
*
*/
	public $helpers = array('Form','Html');

	public $uploadDir = 'uploads';

/**
* Input
*
* @param string $fieldType
* @param array $options
* @return mixed|string
*/
	public function input($fieldType, $options = array()) {
		switch ($fieldType) {
			case 'check_box':
				return $this->inputCheckBox($options);
				break;

			case 'drop_box':
				return $this->inputDropBox($options);
				break;

			case 'radio_box':
				return $this->inputRadioBox($options);
				break;

			case 'text_box':
				return $this->inputTextBox($options);
				break;

			case 'text_area':
				return $this->inputTextArea($options);
				break;

			case 'file_upload':
				return $this->inputFileUpload($options);
				break;

			default:
				return '';
				break;
		}
	}

/**
* Display
*
* @param string $fieldType
* @param array $options
* @return mixed|string
*/
	public function display($fieldType, $options = array()) {
		switch ($fieldType) {
			case 'check_box':
				return $this->displayCheckBox($options);
				break;

			case 'drop_box':
				return $this->displayDropBox($options);
				break;

			case 'radio_box':
				return $this->displayRadioBox($options);
				break;

			case 'text_box':
				return $this->displayTextBox($options);
				break;

			case 'text_area':
				return $this->displayTextArea($options);
				break;

			case 'file_upload':
				return $this->displayFileUpload($options);
				break;

			default:
				return '';
				break;
		}
	}

/**
* Input Check Box
*
* @param array $options
* @return mixed|string
*/
	public function inputCheckBox($options = array()) {
		extract($options);
		$out = '';

		$answers = array();
		if(isset($data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ])) {
			foreach ($data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ] as $answerData) {
				if (isset($answerData['answer_id'])) {
					$answers[$answerData['answer_id']] = $answerData;
				}
			}
		}
		if(isset($question['Answers'])) {
			foreach ($question['Answers'] as $key => $answer) {
				$inputOptions = array(
					'type' => 'checkbox',
					'id' => 'answer_' . $question['KadaiQuestion']['id'] . '_' . $key,
					'name' => 'data[KadaiQuestions][' . $question['KadaiQuestion']['id'] . '][' . $key . '][answer_id]',
					'value' => $answer['id'],
					'data-parsley-multiple' => 'validate_group_' . $question['KadaiQuestion']['id'],
					'data-parsley-errors-container' => '#validate_group_' . $question['KadaiQuestion']['id'],
					'div' => false,
					'label' => $answer['title'],
				);

				if ($key == 0 && $question['KadaiQuestion']['is_required']) {
					$title = $question['KadaiQuestion']['title_error']? $question['KadaiQuestion']['title_error']: $question['KadaiQuestion']['title'];
					$inputOptions['data-parsley-required-message'] = $title;
					$inputOptions['required'] = 'required';
				}

				$out .= $this->Form->input('checkbox', $inputOptions);

				if (!empty($answer['has_comment'])) {
					$inputOptions = array(
						'type' => 'text',
						'id' => 'answer_' . $question['KadaiQuestion']['id'] . '_' . $key . '_comment',
						'class' => 'comment',
						'name' => 'data[KadaiQuestions][' . $question['KadaiQuestion']['id'] . '][' . $key . '][comment]',
						'value' => isset($answers[ $answer['id'] ]['comment'])? $answers[ $answer['id'] ]['comment']: '',
						'div' => false,
						'label' => false,
						'data-parsley-multiple' => 'validate_group_' . $question['KadaiQuestion']['id'],
						'data-parsley-errors-container' => '#validate_group_' . $question['KadaiQuestion']['id'],
					);
					$out .= $this->Form->input('comment', $inputOptions);
				}
				$out .= '<br/ >';
			}
			//$out .= '<div id="validate_group_' . $question['KadaiQuestion']['id'] . '" class="error-message"></div>';
		}

		return $out;
	}

/**
* Input Drop Box
*
* @param array $options
* @return mixed|string
*/
	public function inputDropBox($options = array()) {
		extract($options);
		$out = '';

		if(isset($question['Answers'])) {
			$selected = '';
			if(isset($requestData['KadaiQuestions'][ $question['KadaiQuestion']['id'] ])) {
				foreach ($requestData['KadaiQuestions'][ $question['KadaiQuestion']['id'] ] as $answerData) {
					if (isset($answerData['answer_id'])) {
						$selected = $answerData;
						break;
					}
				}
			}

			$answers = array();
			foreach ($question['Answers'] as $key => $answer) {
				$answers[$answer['id']] = $answer['title'];
			}

			$inputOptions = array(
				'id' => 'answer_' . $question['KadaiQuestion']['id'] . '_0',
				'name' => 'data[KadaiQuestions][' . $question['KadaiQuestion']['id'] . '][0][answer_id]',
				'options' => $answers,
				'selected' => $selected,
				'empty' => '選択してください',
				'div' => false,
				'label' => false,
			);

			if ($question['KadaiQuestion']['is_required']) {
				$title = $question['KadaiQuestion']['title_error']? $question['KadaiQuestion']['title_error']: $question['KadaiQuestion']['title'];
				$inputOptions['data-parsley-required-message'] = $title;
				$inputOptions['required'] = 'required';
			}

			$out .= $this->Form->input('select', $inputOptions);
		}

		return $out;
	}

/**
* Input Radio Box
*
* @param array $options
* @return mixed|string
*/
	public function inputRadioBox($options = array()) {
		extract($options);
		$out = '';

		if(isset($question['Answers'])) {
			$answers = array();
			if(isset($data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0])) {
				foreach ($data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ] as $answerData) {
					if (isset($answerData['answer_id'])) {
						$answers[$answerData['answer_id']] = $answerData;
					}
				}
			}

			$title = $question['KadaiQuestion']['title_error']? $question['KadaiQuestion']['title_error']: $question['KadaiQuestion']['title'];
			foreach ($question['Answers'] as $key => $answer) {
				$out .= '<input type="radio" '.
					'id="answer_' . $question['KadaiQuestion']['id'] . '_' . $key . '" ' .
					'name="data[KadaiQuestions][' . $question['KadaiQuestion']['id'] . '][0][answer_id]" ' .
					'value="' . $answer['id'] . '" ' .
					'data-parsley-multiple="validate_group_' . $question['KadaiQuestion']['id'] . '" ' .
					'data-parsley-errors-container="#validate_group_' . $question['KadaiQuestion']['id'] . '" ' .
					($question['KadaiQuestion']['is_required']? 'data-parsley-required-message="' . $title . '" required="required" ': '') .
					(isset($answers[ $answer['id'] ])? 'checked ': '') . '> ';

				$out .= '<label for="answer_' . $question['KadaiQuestion']['id'] . '_' . $key . '">' . $answer['title'] . '</label>';

				if (!empty($answer['has_comment'])) {
					$inputOptions = array(
						'type' => 'text',
						'id' => 'answer_' . $question['KadaiQuestion']['id'] . '_' . $key . '_comment',
						'class' => 'comment',
						'name' => 'data[KadaiQuestions][' . $question['KadaiQuestion']['id'] . '][' . $key . '][comment]',
						'value' => isset($answers[ $answer['id'] ]['comment'])? $answers[ $answer['id'] ]['comment']: '',
						'div' => false,
						'label' => false,
						'data-parsley-multiple' => 'validate_group_' . $question['KadaiQuestion']['id'],
						'data-parsley-errors-container' => '#validate_group_' . $question['KadaiQuestion']['id'],
					);
					$out .= $this->Form->input('comment', $inputOptions);
				}
				$out .= '<br/ >';
			}
			//$out .= '<div id="validate_group_' . $question['KadaiQuestion']['id'] . '" class="error-message"></div>';
		}

		return $out;
	}

/**
* Input Check Box
*
* @param array $options
* @return mixed|string
*/
	public function inputTextBox($options = array()) {
		extract($options);

		$inputOptions = array(
			'type' => 'text',
			'id' => 'answer_' . $question['KadaiQuestion']['id'] . '_0',
			'name' => 'data[KadaiQuestions][' . $question['KadaiQuestion']['id'] . '][0][value]',
			'value' => isset($data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0]['value'])? $data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0]['value']: '',
			'div' => false,
			'label' => false,
		);

		if ($question['KadaiQuestion']['is_required']) {
			$title = $question['KadaiQuestion']['title_error']? $question['KadaiQuestion']['title_error']: $question['KadaiQuestion']['title'];
			$inputOptions['data-parsley-required-message'] = $title;
			$inputOptions['required'] = 'required';
		}

		return $this->Form->input('text', $inputOptions);
	}

/**
* Input TextArea
*
* @param array $options
* @return mixed|string
*/
	public function inputTextArea($options = array()) {
		extract($options);
		$inputOptions = array(
			'type' => 'textarea',
			'id' => 'answer_' . $question['KadaiQuestion']['id'] . '_0',
			'name' => 'data[KadaiQuestions][' . $question['KadaiQuestion']['id'] . '][0][value]',
			'value' => isset($data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0]['value'])? $data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0]['value']: '',
			'div' => false,
			'label' => false,
			'cols' => '100',
		);

		$title = $question['KadaiQuestion']['title_error']? $question['KadaiQuestion']['title_error']: $question['KadaiQuestion']['title'];
		if ($question['KadaiQuestion']['is_required']) {
			$inputOptions['data-parsley-required-message'] = $title;
			$inputOptions['required'] = 'required';
		}

		return $this->Form->input('textarea', $inputOptions);
	}

/**
* Input FileUpload
*
* @param array $options
* @return mixed|string
*/
	public function inputFileUpload($options = array()) {
		extract($options);
		$inputOptions = array(
			'type' => 'file',
			'id' => 'answer_' . $question['KadaiQuestion']['id'] . '_0',
			'name' => 'data[KadaiQuestions][' . $question['KadaiQuestion']['id'] . '][0][value]',
			'value' => isset($data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0]['value'])? $data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0]['value']: '',
			'div' => false,
			'label' => false,
			'accept' => 'image/png,image/jpeg,image/gif',
		);

		if ($question['KadaiQuestion']['is_required']) {
			$title = $question['KadaiQuestion']['title_error']? $question['KadaiQuestion']['title_error']: $question['KadaiQuestion']['title'];
			$inputOptions['data-parsley-required-message'] = $title;
			$inputOptions['required'] = 'required';
		}

		return $this->Form->input('file_upload', $inputOptions);
	}

/**
* Display Check Box
*
* @param array $options
* @return mixed|string
*/
	public function displayCheckBox($options = array()) {
		extract($options);

		$answerTexts = array();
		if(isset($question['Answers'], $data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ])) {
			foreach ($question['Answers'] as $answer) {
				foreach ($data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ] as $answerData) {
					if (isset($answerData['answer_id']) && $answerData['answer_id'] == $answer['id']) {
						if ($answer['has_comment'] && isset($answerData['comment'])) {
							$answer['title'] .= ' (' . $answerData['comment'] . ')';
						}
						array_push($answerTexts, $answer['title']);
						break;
					}
				}
			}
		}

		return implode('<br/>', $answerTexts);
	}

/**
* Display Drop Box
*
* @param array $options
* @return mixed|string
*/
	public function displayDropBox($options = array()) {
		extract($options);
		$out = '';

		if(isset($question['Answers'], $data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0])) {
			$answerData = $data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0];
			foreach ($question['Answers'] as $answer) {
				if (isset($answerData['answer_id']) && $answerData['answer_id'] == $answer['id']) {
					$out .= $answer['title'];
					break;
				}
			}
		}

		return $out;
	}

/**
* Display Radio Box
*
* @param array $options
* @return mixed|string
*/
	public function displayRadioBox($options = array()) {
		extract($options);

		$out = '';

		if(isset($question['Answers'], $data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0])) {
			$answerData = $data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0];
			foreach ($question['Answers'] as $answer) {
				if (isset($answerData['answer_id']) && $answerData['answer_id'] == $answer['id']) {
					$out .= $answer['title'];
					if ($answer['has_comment'] && isset($answerData['comment'])) {
						$out .= $answerData['comment'] . '<br/>';
					}
					break;
				}
			}
		}

		return $out;
	}

/**
* Display Text Box
*
* @param array $options
* @return mixed|string
*/
	public function displayTextBox($options = array()) {
		extract($options);
		return isset($data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0]['value'])? $data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0]['value']: '';
	}

/**
* Display TextArea
*
* @param array $options
* @return mixed|string
*/
	public function displayTextArea($options = array()) {
		extract($options);
		$out = '';
		return isset($data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0]['value'])? nl2br(h($data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0]['value'])): '';
	}

/**
* Display FileUpload
*
* @param array $options
* @return mixed|string
*/
	public function displayFileUpload($options = array()) {
		extract($options);
		$fileName = isset($data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0]['file_name'])? $data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0]['file_name'] : '';
		if($fileName) {
			$fileNameFullPath = '/' . $this->uploadDir . '/tmp/' . $fileName;
			if (isset($data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0]['value']['type']) &&
				strpos($data['KadaiQuestions'][ $question['KadaiQuestion']['id'] ][0]['value']['type'], 'image') !== false) {
				$out = $this->Html->image($fileNameFullPath, array('url' => $fileNameFullPath));
			} else {
				$out = $this->Html->link($fileName, $fileNameFullPath, array('target' => '_blank'));
			}
			return $out;
		} else {
			return $fileName;
		}
	}

/**
* Get thumbnail of the question
*
* @param array $question
* @return mixed|string
*/
	public function getQuestionThumbnailUrl($question) {
		if (!$question['KadaiQuestion']['image']) {
			return false;
		}

		$fileInfo = json_decode($question['KadaiQuestion']['image'], true);
		if (isset($fileInfo['type']) && strpos($fileInfo['type'], 'image') !== false) {
			$path = WWW_ROOT . $this->uploadDir . DS;
			$fileName = $fileInfo['name'];
			if (file_exists($path . $fileName)) {
				return '/' .  $this->uploadDir . '/' . $fileName;
			}
		}

		return false;
	}
}
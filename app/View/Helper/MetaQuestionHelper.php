<?php
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays a multiple other objects.
 * Answer is stored as multiple values entered by the user.
 */
class MetaQuestionHelper extends QuestionHelper {	
	
	protected $attributes = array(0 => array('name' => 'Questions', 
											 'help' => 'List of questions to combine, seperated by a pipe (|)'),
								  1 => array('name' => 'Conditions',
								  		     'help' => 'List of conditions, seperated by a pipe (|)')
	);
	
	function serialiseAnswer($data, $attibutes) {
		$result = '';
		foreach ($data as $datum) {
			$result = $result.'!!'.$datum;
		}
		// remove initial !!
		$result = substr($result, 2);
		return $result;
	}
	
	function deserialiseAnswer($data, $attributes) {
		if ($data == '') {
			return array();
		}
		$questions = explode('|', $attributes[0]);
		$answers = explode('!!', $data);
		
		$answer = array();
		for ($i = 0; $i < count($questions); $i++) {
			$key = $questions[$i];
			$value = $answers[$i];
			$answer[$key] = $value;
		}
		
		return $answer;
	}
	
	function validateConfig($object)
	{
		$validation = array();
		$validation['object'] = $object;
		
		$attributes = $object['SurveyObjectAttribute'];
		
		$errors = array();
		
		$objects = split("\|", $attributes[0]['value']);
		$conditions = split("\|", $attributes[1]['value']);

		if ($attributes[1]['value'] != '' && count($objects) != count($conditions))
		{
			$errors[] = "Warning: Count mismatch between meta question list and conditions list";
		}
		
		$validation['objects'] = $objects;
		$validation['errors'] = $errors;
		
		return $validation;
	}
}
?>

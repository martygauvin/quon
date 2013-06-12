<?php
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays a simple text box.
 * To be valid, answer must be less than "Answer Length" long (if specified) ant match the regular expression
 * in "Match Regular Expression".
 * Answer is stored as the value entered by the user.
 */
class TextQuestionHelper extends QuestionHelper {	
	
	protected $attributes = array(0 => array('name' => 'Question Text', 
											 'help' => 'Text to display when asking the user this question',
											 'type' => 'html'),
								  1 => array('name' => 'Minimum Answer Length',
											  'help' => 'Positive number representing the min length of the user\'s answer'),
    							  2 => array('name' => 'Maximum Answer Length', 
    							  			 'help' => 'Positive number representing the max length of the user\'s answer'),
    							  3 => array('name' => 'Match Regular Expression', 
    							  			 'help' => 'A regular expression to use when validating the users answer. Leave blank if you do not wish to validate'),
    							  4 => array('name' => 'Match Regular Expression Error',
    							  			 'help' => 'An error message to display when the regular expression is not matched',
    							  			 'type' => 'html')
	);
	
	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{	
		$field_name = $attributes['id'].'_answer';
		
		echo $attributes[0]."<br/><br/>";
		
		if ($previousAnswer) {
			if (isset($attributes[2]) && $attributes[2] != '') {
				echo $form->text($field_name, array('value' => $previousAnswer['SurveyResultAnswer']['answer'], 'maxlength' => $attributes[2]));
			} else {
				echo $form->text($field_name, array('value' => $previousAnswer['SurveyResultAnswer']['answer']));
			}
		} else {
			if (isset($attributes[2]) && $attributes[2] != '') {
				echo $form->text($field_name, array('maxlength' => $attributes[2]));
			} else {
				echo $form->text($field_name);
			}
		}
	}
	
	function convertAnswer($data, $attributes) {
		$field_name = $attributes['id'].'_answer';
		
		if (isset($data['Public'][$field_name]))
			return $data['Public'][$field_name];
		else
			return "";
	}
    
	function serialiseAnswer($data, $attributes)
	{
		return $data['value'];
	}
	
	function deserialiseAnswer($data, $attributes) {
		$answer = array();
		$answer['value'] = $data;
		return $answer;
	}
	
	function validateAnswer($data, $attributes, &$error)
	{
		$answer = $data['value'];
		
		if (isset($attributes[1]) && '' != $attributes[1]) {
			if (strlen($answer) < $attributes[1]) {
				$error = 'Answer must be at least '.$attributes[1].' characters long.';
			}
		}
		
		if (isset($attributes[2]) && '' != $attributes[2]) {
			if (strlen($answer) > $attributes[2]) {
				$error = 'Answer must be fewer than '.$attributes[2].' characters long.';
				return false;
			}
		}
		if (isset($attributes[3]) && $attributes[3] != '') {
			$matches = array();
			if (1 != preg_match_all($attributes[3], $answer, $matches)) {
				if (isset($attributes[4]) && $attributes[4] != '') {
					$error = $attributes[4];
				}
				else {
					$error = 'Answer does not match regular expression '.$attributes[2].'.';
				}
				return false;
			}
		}
		
		return true;
	}
}
?>
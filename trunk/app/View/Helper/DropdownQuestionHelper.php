<?php
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays a dropdown list of items.
 * To be valid, a value must be specified if "Other" is selected.
 * Answer is stored as the value of the selected option.
 */
class DropdownQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
	     							         'help' => 'Text to display when asking the user this question',
	     							         'type' => 'html'),
    							  1 => array('name' => 'Options',
											 'help' => 'List of possible values and options, seperated by a |. e.g. "1=Yes|2=No|3=Maybe" will display "Yes", "No", and "Maybe" as options, storing the value as "1", "2", or "3" respectively depending on which is selected.'),
								  2 => array('name' => 'Include "Other" option',
								  			 'help' => 'Leave blank to disable the "Other" option. Otherwise enter the value to be stored for "Other" is selected e.g. 88')
	);
	
	function validateAnswer($data, $attributes, &$error)
	{	
		if ($attributes[2] && strlen($attributes[2]) > 0 &&
				$data['value'] === $attributes[2] &&
				$data[$attributes[2].'_text'] === '')
		{
			$error = "Please enter a value for other in the textbox provided";
			return false;
		}
	
		return true;
	}

	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{
		$field_name = $attributes['id'].'_answer';
		
		echo $attributes[0]."<br/><br/>";
	
		$options = array();
		$questionOptions = split("\|", $attributes[1]);
		foreach ($questionOptions as $questionOption)
		{
			$questionValue = QuestionHelper::getKey($questionOption);
			$questionText = QuestionHelper::getValue($questionOption);
			$options[$questionValue] = $questionText;
		}
	
		if ($attributes[2] && strlen($attributes[2]) > 0)
		{
			$options[$attributes[2]] = 'Other';
		}
		
		//TODO: Move Javascript to separate file
		echo "<script type='text/javascript'>
									function checkOther".$attributes['id']."()
									{
										var option = document.getElementById('Public".$attributes['id']."Answer');
										var answerOther = document.getElementById('Public".$attributes['id']."AnswerOtherText');
										if (option) {
											if (option.value == '".$attributes[2]."')
											{
												answerOther.style.display = 'block';
											}
											else
											{
												answerOther.style.display = 'none';
											}
										}		
									}

									$(document).ready(function() {checkOther".$attributes['id']."();});
								  </script>
							";
	
		if ($previousAnswer) {
			$answerValue = $previousAnswer['SurveyResultAnswer']['answer'];
			$otherValue = '';
			if (strpos($answerValue, '|')) {
				$otherValue = QuestionHelper::unescapeString(substr($answerValue, 1 + strpos($answerValue, '|')));
				$answerValue = substr($answerValue, 0, strpos($answerValue, '|'));
			}
				
			echo $form->input($field_name, array('label'=>false, 'type'=>'select', 'onclick' => 'checkOther'.$attributes['id'].'();', 'options'=>$options, 'default'=>$answerValue));
			echo $form->input($field_name.'OtherText', array('type'=>'text', 'value'=>$otherValue, 'label'=>'&nbsp;', 'style' => 'display:none;'));
		} else {
			echo $form->input($field_name, array('label'=>false, 'type'=>'select', 'onclick' => 'checkOther'.$attributes['id'].'();', 'options'=>$options));
			echo $form->input($field_name.'OtherText', array('type'=>'text', 'label'=>'&nbsp;', 'style' => 'display:none;'));
		}
	}
	
	function convertAnswer($data, $attributes) {
		$field_name = $attributes['id'].'_answer';
		
		$answer = array();
		$answer['value'] = $data['Public'][$field_name];
		
		if ($attributes[2] && strlen($attributes[2]) > 0) {
			$answer[$attributes[2].'_text'] = $data['Public'][$field_name.'OtherText'];
		}
		
		// add compatibility for checkbox-style expressions
		$options = explode('|', $attributes[1]);
		foreach ($options as $option) {
			$key = QuestionHelper::getKey($option);
			if ($answer['value'] === $key) {
				$answer[$key] = 1;
			} else {
				$answer[$key] = 0;
			}
		}
		if ($attributes[2] && strlen($attributes[2]) > 0) {
			if ($answer['value'] === $attributes[2]) {
				$answer[$attributes[2]] = 1;
			} else {
				$answer[$attributes[2]] = 0;
			}
		}
		
		return $answer;
	}
	
	function serialiseAnswer($data, $attributes) {
		$result = $data['value'];
		if ($attributes[2] && strlen($attributes[2]) > 0 && $result === $attributes[2]) {
			$result = $result.'|'.QuestionHelper::escapeString($data[$attributes[2].'_text']);
		}
		return $result;
	}
	
	function deserialiseAnswer($data, $attributes){
		$answer = array();
		$answer['value'] = $data;
		if ($attributes[2] && strlen($attributes[2]) > 0) {
			$pipePos = strpos($data, '|');
			if ($pipePos) {
				$value = substr($data, 0, $pipePos);
				if ($attributes[2] === $value) {
					$other = substr($data, $pipePos + 1);
					$answer['value'] = $attributes[2];
					$answer[$attributes[2].'_text'] = $other;
				}
			}
		}
		
		// add compatibility for checkbox-style expressions
		$options = explode('|', $attributes[1]);
		foreach ($options as $option) {
			$key = QuestionHelper::getKey($option);
			if ($answer['value'] === $key) {
				$answer[$key] = 1;
			} else {
				$answer[$key] = 0;
			}
		}
		if ($attributes[2] && strlen($attributes[2]) > 0) {
			if ($answer['value'] === $attributes[2]) {
				$answer[$attributes[2]] = 1;
			} else {
				$answer[$attributes[2]] = 0;
			}
			if (!isset($answer[$attributes[2].'_text'])) {
				$answer[$attributes[2].'_text'] = '';
			}
		}
		
		return $answer;
	}
}
?>
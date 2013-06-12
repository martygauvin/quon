<?php
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays a set of radio buttons.
 * To be valid, answer must be one of the options, or a specified "other" value.
 * Answer is stored as the value associated with the selected option.
 */
class RadioButtonQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question',
											 'type' => 'html'),
    							  1 => array('name' => 'Options',
											 'help' => 'List of possible values and options, seperated by a |. e.g. "1=Yes|2=No|3=Maybe" will display "Yes", "No", and "Maybe" as options, storing the value as "1", "2", or "3" respectively depending on which is selected.'),
								  2 => array('name' => 'Include "None of the above" as an option',
								  		     'help' => 'Leave blank to disable the "None of the above" option. Otherwise enter the value to be stored when "None of the above" is selected e.g. 99'),
								  3 => array('name' => 'Include "Other" option',
								  			 'help' => 'Leave blank to disable the "Other" option. Otherwise enter the value to be stored for "Other" is selected e.g. 88'),
								  4 => array('name' => 'Mandatory',
											 'help' => 'Set to "true" if you wish the user to not be able to progress without selecting an option')
									
		);
	
	function validateAnswer($data, $attributes, &$error)
	{
		$field_name = $attributes['id'].'_answer';
		
		if (isset($attributes[4]) && $attributes[4] && strlen($attributes[4]) > 0 && $data['value'] == '')
		{
			$error = "Please select an option";
			return false;
		}
		
		if ($attributes[3] && strlen($attributes[3]) > 0 &&
			$data['value'] === $attributes[3] &&
		    $data[$attributes[3].'_text'] === '')
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
		$questionOptions = explode("|", $attributes[1]);
		foreach ($questionOptions as $questionOption)
		{
			$questionValue = QuestionHelper::getKey($questionOption);
			$questionText = QuestionHelper::getValue($questionOption);
			if (strlen($questionText) <= 0) {
				$questionText = '&nbsp;';
			}
			$options[$questionValue] = $questionText;
		}
	
		if ($attributes[2] && strlen($attributes[2]) > 0)
		{
			$options[$attributes[2]] = 'None of the above';
		}
		
		if ($attributes[3] && strlen($attributes[3]) > 0)
		{
			$options[$attributes[3]] = 'Other';
		}
		
		
		echo "<script type='text/javascript'>
							function checkOther".$attributes['id']."()
							{
								var option = document.getElementById('Public".$attributes['id']."Answer".ucfirst($attributes[3])."');
								var answerOther = document.getElementById('Public".$attributes['id']."AnswerOtherText');
								if (option) {
									if (option.checked)
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
		
		if ($previousAnswer)
		{
			$answerValue = $previousAnswer['SurveyResultAnswer']['answer'];
			$otherValue = '';
			if (strpos($answerValue, '|')) {
				$otherValue = QuestionHelper::unescapeString(substr($answerValue, 1 + strpos($answerValue, '|')));
				$answerValue = substr($answerValue, 0, strpos($answerValue, '|'));
			}
			
			echo $form->input($field_name, array('legend'=>false, 'type'=>'radio', 'value'=>$answerValue, 'options'=>$options, 'onclick' => 'checkOther'.$attributes['id'].'();'));
		
			if ($attributes[3] && strlen($attributes[3]) > 0)
			{
				echo $form->input($field_name.'OtherText', array('legend'=>false, 'type' => 'text', 'value'=>$otherValue, 'label'=>'&nbsp;', 'style' => 'display:none;'));
			}
		}
		else
		{
			echo $form->input($field_name, array('legend'=>false, 'type'=>'radio', 'options'=>$options, 'onclick' => 'checkOther'.$attributes['id'].'();'));
			
			if ($attributes[3] && strlen($attributes[3]) > 0)
			{
				echo $form->input($field_name.'OtherText', array('legend'=>false, 'type' => 'text', 'label'=>'&nbsp;', 'style' => 'display:none;'));
			}
		}
	
	}
	
	function convertAnswer($data, $attributes) {
		$field_name = $attributes['id'].'_answer';
		
		$answer = array();
		$answer['value'] = $data['Public'][$field_name];
		
		if ($attributes[3] && strlen($attributes[3]) > 0) {
			$answer[$attributes[3].'_text'] = $data['Public'][$field_name.'OtherText'];
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
		if ($attributes[3] && strlen($attributes[3]) > 0) {
			if ($answer['value'] === $attributes[3]) {
				$answer[$attributes[3]] = 1;
			} else {
				$answer[$attributes[3]] = 0;
			}
		}
		
		return $answer;
	}
	
	function serialiseAnswer($data, $attributes) {
		$result = $data['value'];
		if ($attributes[3] && strlen($attributes[3]) > 0 && $result === $attributes[3]) {
			$result = $result.'|'.QuestionHelper::escapeString($data[$attributes[3].'_text']);
		}
		return $result;
	}
	
	function deserialiseAnswer($data, $attributes){
		$answer = array();
		$answer['value'] = $data;
		if ($attributes[3] && strlen($attributes[3]) > 0) {
			$pipePos = strpos($data, '|');
			if ($pipePos) {
				$value = substr($data, 0, $pipePos);
				if ($attributes[3] === $value) {
					$other = substr($data, $pipePos + 1);
					$answer['value'] = $attributes[3];
					$answer[$attributes[3].'_text'] = $other;
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
		}
		if ($attributes[3] && strlen($attributes[3]) > 0) {
			if ($answer['value'] === $attributes[3]) {
				$answer[$attributes[3]] = 1;
			} else {
				$answer[$attributes[3]] = 0;
			}
			if (!isset($answer[$attributes[3].'_text'])) {
				$answer[$attributes[3].'_text'] = '';
			}
		}
		
		return $answer;
	}
}
?>
<?php
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays a set of buttons for the user to select.
 * No validation is performed.
 * Answer is stored as the value of the selected button.
 */
class ButtonOptionQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question',
											 'type' => 'html'),
    							  1 => array('name' => 'Options',
											 'help' => 'List of possible values and options, seperated by a |. e.g. "1=Yes|2=No|3=Maybe" will display "Yes", "No", and "Maybe" as options, storing the value as "1", "2", or "3" respectively depending on which is selected.'),
								  2 => array('name' => 'Include "None of the above" as an option',
								  		     'help' => 'Leave blank to disable the "None of the above" option. Otherwise enter the value to be stored when "None of the above" is selected e.g. 99')
		);
		
	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{		
		$field_name = $attributes['id'].'_answer';
		
		echo "<script type='text/javascript'>
			function answerButton".$attributes['id']."(answerStr)
			{
				document.getElementById('Public".$attributes['id']."Answer').value = answerStr;
				return questionSubmit('next');
			}
			</script>
			";
		
		echo $attributes[0]."<br/><br/>";
	
		$options = array();
		$questionOptions = split("\|", $attributes[1]);
		foreach ($questionOptions as $questionOption)
		{
			$questionValue = QuestionHelper::getKey($questionOption);
			$questionText = QuestionHelper::getValue($questionOption);
			echo $form->input($questionText, array('type' => 'submit', 'label' => false, 'onclick' => 'return answerButton'.$attributes['id'].'("'.$questionValue.'");'));
			echo "<br/><br/>";
		}
		
		echo $form->hidden($field_name);
	
		if ($attributes[2] && strlen($attributes[2]) > 0)
		{
			echo $form->input('None of the above', array('type' => 'submit', 'label' => false, 'onclick' => 'return answerButton'.$attributes['id'].'("'.$attributes[2].'");'));
		}
		
		$show_next = false;
	
	}
	
	function convertAnswer($data, $attributes) {
		$field_name = $attributes['id'].'_answer';
		
		$answer = array();
		$answer['value'] = $data['Public'][$field_name];
		
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
	
	function serialiseAnswer($data, $attributes)
	{
		return $data['value'];
	}
	
	function deserialiseAnswer($data, $attributes) {
		$answer = array();
		$answer['value'] = $data;
		
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
}
?>
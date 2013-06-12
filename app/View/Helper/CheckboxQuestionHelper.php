<?php
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays a list of items with checkboxes.
 * To be valid, answer must either be some options, or "None of the above".
 * If "Other" is specified, a value must also be given.
 * Answer is stored as a |-delimited set of selected options.
 */
class CheckboxQuestionHelper extends QuestionHelper  {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question',
											 'type' => 'html'),
    							  1 => array('name' => 'Options',
    							  			 'help' => 'List of possible values and options, seperated by a |. e.g. "1=Yes|2=No|3=Maybe" will display "Yes", "No", and "Maybe" as options, storing the value as "1", "2", or "3" respectively depending on which is selected. Note: 0 is a reserved value and should not be used.'),
    							  2 => array('name' => 'Minimum number of options to be selected',
    							  			 'help' => 'Number representing the minumum number of answers that the user has to select'),
    							  3 => array('name' => 'Maximum number of options to be selected',
    							   			 'help' => 'Number representing the maximum number of answers that the user has to select'),
								  4 => array('name' => 'Include "None of the above" as an option',
								  		     'help' => 'Leave blank to disable the "None of the above" option. Otherwise enter the value to be stored when "None of the above" is selected e.g. 99. Note: 0 is a reserved value and should not be used.'),
								  5 => array('name' => 'Include "Other" option',
								  			 'help' => 'Leave blank to disable the "Other" option. Otherwise enter the value to be stored for "Other" is selected e.g. 88. Note: 0 is a reserved value and should not be used.')
	);
	
	function validateAnswer($data, $attributes, &$error)
	{
		$answers = $this->serialiseAnswer($data, $attributes);
		
		if ($answers)
			$answers = explode('|', $answers);
		else
			$answers = array();

		$noneSelected = $attributes[4] && strlen($attributes[4]) > 0 && in_array($attributes[4], $answers);
		// If none of the above selected, it can be the only value
		if ($noneSelected && count($answers) > 1)
		{
			$error = 'Please do not select \'None of the Above\' in addition to other options';
			return false;
		}
		
		// If other selected, need text to specify
		if ($attributes[5] && strlen($attributes[5]) > 0
			&& in_array($attributes[5], $answers) && $data[$attributes[5].'_text'] == '')
		{
			$error = 'Please provide a value in the other text box';
			return false;
		}

		// If not none of the above, then range checks apply
		if (!$noneSelected)
		{
			$options = explode("|", $attributes[1]);
			if ($attributes[2] && '' != $attributes[2]) {
				if (count($answers) < $attributes[2] && $attributes[2] <= count($options)) {
					$error = 'Please select a minimum of '.$attributes[2].' options';
					return false;
				}
			}
			if ($attributes[3] && '' != $attributes[3]) {
				if (count($answers) > $attributes[3]) {
					$error = 'Please select a maximum of '.$attributes[3].' options';
					return false;
				}
			}
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
			$options[$questionValue] = $questionText;
		}
		
		if ($attributes[4] && strlen($attributes[4]) > 0)
		{
			$options[$attributes[4]] = 'None of the above';
		}
		
		$otherOption = 'false';
		if ($attributes[5] && strlen($attributes[5]) > 0)
		{
			$otherOption = 'true';
			$options[$attributes[5]] = 'Other';
		}

		//TODO: Move Javascript to separate file
		echo "<script type='text/javascript'>
							function checkSpecials".$attributes['id']."()
							{
								checkNone".$attributes['id']."();
								checkOther".$attributes['id']."();
							}
							
							function checkOther".$attributes['id']."()
							{
								var option = document.getElementById('Public".$attributes['id']."Answer".ucfirst($attributes[5])."');
								var answerOther = document.getElementById('Public".$attributes['id']."AnswerOtherText"."');
								if (".$otherOption." && option) {
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
							
							function checkNone".$attributes['id']."()
							{
								var optionNone = document.getElementById('Public".$attributes['id']."Answer".ucfirst($attributes[4])."');
								if (optionNone) {
									if (optionNone.checked)
									{
										$(':checkbox:not(:checked)').attr('disabled', true);
									}
									else
									{
										$(':checkbox:not(:checked)').removeAttr('disabled');
									}
								}
							}
							$(document).ready(function() {
								$(':checkbox').click(function(){checkSpecials".$attributes['id']."();});
								checkSpecials".$attributes['id']."();
							});
						  </script>
					";
		
		$selected = array();
		$otherValue = '';
		$otherFound = false;
		if ($previousAnswer)
		{
			$answers = explode('|', $previousAnswer['SurveyResultAnswer']['answer']);
			foreach ($answers as $answer) {
				if ($otherFound) {
					$otherFound = false;
					$otherValue = QuestionHelper::unescapeString($answer);
				} else {
					$selected[] = $answer;
					if ($attributes[5] && strlen($attributes[5]) > 0 && $answer == $attributes[5]) {
						$otherFound = true;
					}
				}
			}
		}
		
		echo $form->input($field_name, array('label'=>false, 'type'=>'select', 'multiple'=>'checkbox',
											'options'=>$options, 'selected'=>$selected));
			
		if ($attributes[5] && strlen($attributes[5]) > 0)
		{
			echo $form->input($field_name.'OtherText', array('type'=>'text', 'value'=>$otherValue,
								'label'=>'&nbsp;', 'style' => 'display:none;'));
		}
	}
	
	function convertAnswer($data, $attributes) {
		$field_name = $attributes['id'].'_answer';
		
		$results = array();
		$string = '';
		
		if (isset($data['Public'][$field_name]) && is_array($data['Public'][$field_name])) {
			foreach ($data['Public'][$field_name] as $answer)
			{
				if ($answer != '0')
				{
					$results[] = $answer;
					$string = $string.','.$answer;
				}
			}
		}

		$count = 0;
		$answers = array();
		$options = explode('|', $attributes[1]);
		foreach ($options as $option) {
			$key = QuestionHelper::getKey($option);
			if (in_array($key, $results)) {
				$answers[$key] = '1';
				$count++;
			} else {
				$answers[$key] = '0';
			}
		}
		if ($attributes[4] && strlen($attributes[4]) > 0) {
			$noneIncluded = '0';
			if (in_array($attributes[4], $results)) {
				$noneIncluded = '1';
				$count++;
			}
			$answers[$attributes[4]] = $noneIncluded;
		}
		if ($attributes[5] && strlen($attributes[5]) > 0) {
			$otherIncluded = '0';
			$otherText = '';
			if (in_array($attributes[5], $results)) {
				$otherIncluded = '1';
				$otherText = QuestionHelper::escapeString($data['Public'][$field_name.'OtherText']);
				$count++;
			}
			$answers[$attributes[5]] = $otherIncluded;
			$answers[$attributes[5].'_text'] = $otherText;
		}
		
		if (strlen($string) > 0) {
			$string = substr($string, 1);
		}
		
		$answers['value'] = $count;
		$answers['value_string'] = $string;

		return $answers;
	}
	
	function serialiseAnswer($data, $attributes)
	{
		$result = '';
		foreach ($data as $key=>$datum) {
			if ($datum === '1') {
				if (!$attributes[5] || strlen($attributes[5]) <= 0 || $key !== $attributes[5].'_text') {
					$result = $result.$key.'|';
				}
			}
		}
		if ($attributes[5] && strlen($attributes[5]) > 0 && $data[$attributes[5]] === '1') {
			$result = $result.QuestionHelper::escapeString($data[$attributes[5].'_text']).'|';
		}
		if (strlen($result) > 0) {
			$result = substr($result, 0, -1); // remove extra |
		}
		return $result;
	}
	
	function deserialiseAnswer($data, $attributes) {
		$answer = array();
		$string = '';
		$values = explode('|', $data);
		if ($attributes[5] && strlen($attributes[5]) > 0 && in_array($attributes[5], $values)) {
			$values = explode('|', substr($data, 0, strrpos($data, '|')));
		}
		$options = explode('|', $attributes[1]);
		
		$count = 0;
		foreach ($options as $option) {
			$key = QuestionHelper::getKey($option);
			if (in_array($key, $values)) {
				$answer[$key] = '1';
				$count++;
				$string = $string.','.$key;
			} else {
				$answer[$key] = '0';
			}
		}
		
		if ($attributes[4] && strlen($attributes[4]) > 0) {
			$noneIncluded = '0';
			if (in_array($attributes[4], $values)) {
				$noneIncluded = '1';
				$count++;
				$string = $string.','.$attributes[4];
			}
			$answer[$attributes[4]] = $noneIncluded;
		}
		
		if ($attributes[5] && strlen($attributes[5]) > 0) {
			$otherIncluded = '0';
			$otherText = '';
			if (in_array($attributes[5], $values)) {
				$otherIncluded = '1';
				$otherText = substr($data, strrpos($data, '|') + 1);
				$count++;
				$string = $string.','.$attributes[5];
			}
			$answer[$attributes[5]] = $otherIncluded;
			$answer[$attributes[5].'_text'] = $otherText;
		}
		
		if (strlen($string) > 0) {
			$string = substr($string, 1);
		}
		
		$answer['value'] = $count;
		$answer['value_string'] = $string;
		
		return $answer;
	}

}
?>

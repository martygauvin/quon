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
			&& in_array($attributes[5], $answers) && $data['Public']['answerOtherText'] == '')
		{
			$error = 'Please provide a value in the other text box';
			return false;
		}

		// If not none of the above, then range checks apply
		if (!$noneSelected)
		{
			if ($attributes[2] && '' != $attributes[2]) {
				if (count($answers) < $attributes[2]) {
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
		echo "Question: ".$attributes[0]."<br/><br/>";
		
		$options = array();
		$questionOptions = split("\|", $attributes[1]);
		foreach ($questionOptions as $questionOption)
		{
			$questionValue = $questionOption;
			$questionText = $questionOption;
			if (strpos($questionOption, '=')) {
				$questionValue = substr($questionValue, 0, strpos($questionValue, '='));
				$questionText = substr($questionText, 1 + strpos($questionText, '='));
			}
			$options[$questionValue] = $questionText;
		}
		
		if ($attributes[4] && strlen($attributes[4]) > 0)
		{
			$options[$attributes[4]] = 'None of the above';
		}
		
		if ($attributes[5] && strlen($attributes[5]) > 0)
		{
			$options[$attributes[5]] = 'Other';
		}

		//TODO: Move Javascript to separate file
		echo "<script type='text/javascript'>
							function checkSpecials()
							{
								checkNone();
								checkOther();
							}
							
							function checkOther()
							{
								var option = document.getElementById('PublicAnswer".ucfirst($attributes[5])."');
								var answerOther = document.getElementById('PublicAnswerOtherText');
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
							
							function checkNone()
							{
								var optionNone = document.getElementById('PublicAnswer".ucfirst($attributes[4])."');
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
								$(':checkbox').click(function(){checkSpecials();});
								checkSpecials();
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
		
		echo $form->input('answer', array('type'=>'select', 'multiple'=>'checkbox',
											'options'=>$options, 'selected'=>$selected));
			
		if ($attributes[5] && strlen($attributes[5]) > 0)
		{
			echo $form->input('answerOtherText', array('type'=>'text', 'value'=>$otherValue,
								'label'=>'&nbsp;', 'style' => 'display:none;'));
		}
	}
	
	function serialiseAnswer($data, $attributes)
	{
		$results = array();
		
		if (!is_array($data['Public']['answer'])) {
			return "";
		}
		foreach ($data['Public']['answer'] as $answer)
		{
			if ($answer != '0')
			{
				if ($attributes[5] && strlen($attributes[5]) > 0
					&& $answer == $attributes[5])
				{
					$otherText = QuestionHelper::escapeString($data['Public']['answerOtherText']);
					$results[] = $answer.'|'.$otherText;
				}
				else
				{
					$results[] = $answer;
				}
			}
		}
		
		return implode("|", $results);
	}

}
?>
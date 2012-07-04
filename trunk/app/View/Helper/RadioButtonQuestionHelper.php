<?php
App::uses('AppHelper', 'View/Helper');

class RadioButtonQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question',
											 'type' => 'html'),
    							  1 => array('name' => 'Options',
											 'help' => 'List of possible values and options, seperated by a |. e.g. "1=Yes|2=No|3=Maybe" will display "Yes", "No", and "Maybe" as options, storing the value as "1", "2", or "3" respectively depending on which is selected.'),
								  2 => array('name' => 'Include "None of the above" as an option',
								  		     'help' => 'Leave blank to disable the "None of the above" option. Otherwise enter the value to be stored when "None of the above" is selected e.g. 99'),
								  3 => array('name' => 'Include "Other" option',
								  			 'help' => 'Leave blank to disable the "Other" option. Otherwise enter the value to be stored for "Other" is selected e.g. 88')
		);
	
	function validateAnswer($data, $attributes, &$error)
	{
		if ($attributes[3] && strlen($attributes[3]) > 0 &&
			$data['Public']['answer'] == $attributes[3] &&
		    $data['Public']['answerOtherText'] == '')
		{
			$error = "Please enter a value for other in the textbox provided";
			return false;
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
	
		if ($attributes[2] && strlen($attributes[2]) > 0)
		{
			$options[$attributes[2]] = 'None of the above';
		}
		
		if ($attributes[3] && strlen($attributes[3]) > 0)
		{
			$options[$attributes[3]] = 'Other';
		}
		
		
		echo "<script type='text/javascript'>
							function checkOther()
							{
								var option = document.getElementById('PublicAnswer".$attributes[3]."');
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
							$(document).ready(function() {checkOther();});
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
			
			echo $form->input('answer', array('type'=>'radio', 'value'=>$answerValue, 'options'=>$options, 'onClick' => 'javascript:checkOther();'));
		
			if ($attributes[3] && strlen($attributes[3]) > 0)
			{
				echo $form->input('answerOtherText', array('type' => 'text', 'value'=>$otherValue, 'label'=>'&nbsp;', 'style' => 'display:none;'));
			}
		}
		else
		{
			echo $form->input('answer', array('type'=>'radio', 'options'=>$options, 'onClick' => 'javascript:checkOther();'));
			
			if ($attributes[3] && strlen($attributes[3]) > 0)
			{
				echo $form->input('answerOtherText', array('type' => 'text', 'label'=>'&nbsp;', 'style' => 'display:none;'));
			}
		}
	
	}
	
	function serialiseAnswer($data, $attributes)
	{
		if ($attributes[3] && strlen($attributes[3]) > 0 &&
				$data['Public']['answer'] == $attributes[3])
			return $data['Public']['answer'].'|'.QuestionHelper::escapeString($data['Public']['answerOtherText']);
		else
			return $data['Public']['answer'];
	}
}
?>
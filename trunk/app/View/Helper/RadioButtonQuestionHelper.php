<?php
App::uses('AppHelper', 'View/Helper');

class RadioButtonQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question'),
    							  1 => array('name' => 'Options',
											 'help' => 'List of possible options, each seperate by a |. e.g. Yes|No|Maybe'),
								  2 => array('name' => 'Include "None of the above" as an option',
								  		     'help' => 'Enter "yes" if you wish to include an extra option for "none of the above"'),
								  3 => array('name' => 'Include "Other" option',
								  			 'help' => 'Enter "yes" if you wish to include an extra option for "other"')
		);
	
	function validateAnswer($data, $attributes, &$error)
	{
		if ($data['Public']['answer'] == 'other' &&
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
			$options[$questionOption] = $questionOption;
		}
	
		if ($attributes[2] == 'yes')
		{
			$options['none'] = 'None of the above';
		}
		
		if ($attributes[3] == 'yes')
		{
			$options['other'] = 'Other';
		}
		
		
		echo "<script type='text/javascript'>
							function checkOther()
							{
								var option = document.getElementById('PublicAnswerOther');
								var answerOther = document.getElementById('PublicAnswerOtherText');
								if (option.checked)
								{
									answerOther.style.display = 'block';
								}
								else
								{
									answerOther.style.display = 'none';
								}
									
							}
							$(document).ready(function() {checkOther();});
				</script>
					";
		
		if ($previousAnswer)
		{
			// TODO: Implement load previous answer for radio question type
			echo $form->input('answer', array('type'=>'radio', 'options'=>$options, 'onClick' => 'javascript:checkOther();'));
		
			if ($attributes[3] == 'yes')
			{
				echo $form->input('answerOtherText', array('type' => 'text', 'label'=>'&nbsp;', 'style' => 'display:none;'));
			}
		}
		else
		{
			echo $form->input('answer', array('type'=>'radio', 'options'=>$options, 'onClick' => 'javascript:checkOther();'));
			
			if ($attributes[3] == 'yes')
			{
				echo $form->input('answerOtherText', array('type' => 'text', 'label'=>'&nbsp;', 'style' => 'display:none;'));
			}
		}
	
	}
	
	function serialiseAnswer($data, $attributes)
	{
		if ($data['Public']['answer'] == 'other')
			return $data['Public']['answerOtherText'];
		else
			return $data['Public']['answer'];
	}
}
?>
<?php
App::uses('AppHelper', 'View/Helper');

class CheckboxQuestionHelper extends QuestionHelper  {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question'),
    							  1 => array('name' => 'Options',
    							  			 'help' => 'List of possible options, each seperate by a |. e.g. Yes|No|Maybe'),
    							  2 => array('name' => 'Minimum number of options to be selected',
    							  			 'help' => 'Number representing the minumum number of answers that the user has to select'),
    							  3 => array('name' => 'Maximum number of options to be selected',
    							   			 'help' => 'Number representing the maximum number of answers that the user has to select'),
								  4 => array('name' => 'Include "None of the above" as an option',
								  		     'help' => 'Enter "yes" if you wish to include an extra option for "none of the above"'),
								  5 => array('name' => 'Include "Other" option',
								  			 'help' => 'Enter "yes" if you wish to include an extra option for "other"')
	);
	
	function validateAnswer($data, $attributes, &$error)
	{
		$answers = $this->serialiseAnswer($data, $attributes);
		
		if ($answers)
			$answers = explode('|', $answers);
		else
			$answers = array();
		
		if (array_search('none', $answers) && count($answers) > 1)
		{
			$error = 'Please do not select \'None of the Above\' in addition to other options';
			return false;
		}
		
		if (array_search('other', $answers) && $data['Public']['answerOther'] == '')
		{
			$error = 'Please provide a value in the other text box';
			return false;
		}

		if (!array_search('none', $answers))
		{
			if (isset($attributes[2]) && '' != $attributes[2]) {
				if (count($answers) < $attributes[2]) {
					$error = 'Please select a minimum of '.$attributes[2].' options';
					return false;
				}
			}
			if (isset($attributes[3]) && '' != $attributes[3]) {
				if (count($answers) > $attributes[3]) {
					$error = 'Please select a maximum of '.$attributes[3].' options';
					return false;
				}
			}
		}
		
		return true;
	}
	
	function renderQuestion($form, $attributes)
	{	
		echo "Question: ".$attributes[0]."<br/><br/>";
		
		$options = array();
		$questionOptions = split("\|", $attributes[1]);
		foreach ($questionOptions as $questionOption)
		{
			$options[$questionOption] = $questionOption;
		}
		
		if ($attributes[4] == 'yes')
		{
			// TODO: Add Javascript that disables all if "none of the above" is selected
			$options['none'] = 'None of the above';		
		}
		
		if ($attributes[5] == 'yes')
		{
			$options['other'] = 'Other';
		}
		
		echo $form->input('answer', array('type'=>'select', 'multiple'=>'checkbox', 
										  'options'=>$options, 
										  'onClick' => 'javascript:return clear();'));
		
		if ($attributes[5] == 'yes')
		{
			echo $form->input('answerOther', array('type'=>'text', 'label'=>'Other'));
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
				if ($answer == 'other')
				{
					$results[] = $data['Public']['answerOther'];
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
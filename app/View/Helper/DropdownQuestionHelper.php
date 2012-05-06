<?php
App::uses('AppHelper', 'View/Helper');

class DropdownQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
	     							         'help' => 'Text to display when asking the user this question'),
    							  1 => array('name' => 'Options',
											 'help' => 'List of possible options, each seperate by a |. e.g. Yes|No|Maybe'),
								  2 => array('name' => 'Include "Other" option',
								  			 'help' => 'Enter "yes" if you wish to include an extra option for "other"')
	);

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
			$options['other'] = 'Other';
		}
	
		if ($previousAnswer)
			echo $form->input('answer', array('type'=>'select', 'options'=>$options, 'default'=>$previousAnswer['SurveyResultAnswer']['answer']));	
		else
			echo $form->input('answer', array('type'=>'select', 'options'=>$options));
		
	}
	
	function serialiseAnswer($data, $attributes)
	{
		return $data['Public']['answer'];
	}
}
?>
<?php
App::uses('AppHelper', 'View/Helper');

class TextQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text', 
											 'help' => 'Text to display when asking the user this question'),
    							  1 => array('name' => 'Answer Length', 
    							  			 'help' => 'Positive number representing the max length of the users answer'),
    							  2 => array('name' => 'Match Regular Expression', 
    							  			 'help' => 'A regular expression to use when validating the users answer. Leave blank if you do not wish to validate')
	);
	
	function renderQuestion($form, $attributes)
	{	
		echo "Question: ".$attributes[0]."<br/><br/>";
		echo $form->text('answer', array('maxlength' => $attributes[1]));
	}
    
	function serialiseAnswer($data)
	{
		return $data['Public']['answer'];
	}
}
?>
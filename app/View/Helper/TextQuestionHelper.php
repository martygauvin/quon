<?php
App::uses('AppHelper', 'View/Helper');

class TextQuestionHelper extends QuestionHelper {	
	
	protected $attributes = array(0 => array('name' => 'Question Text', 
											 'help' => 'Text to display when asking the user this question',
											 'type' => 'html'),
    							  1 => array('name' => 'Answer Length', 
    							  			 'help' => 'Positive number representing the max length of the users answer'),
    							  2 => array('name' => 'Match Regular Expression', 
    							  			 'help' => 'A regular expression to use when validating the users answer. Leave blank if you do not wish to validate'),
    							  3 => array('name' => 'Match Regular Expression Error',
    							  			 'help' => 'An error message to display when the regular expression is not matched')
	);
	
	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{	
		echo "Question: ".$attributes[0]."<br/><br/>";
		
		if ($previousAnswer)
			echo $form->text('answer', array('value' => $previousAnswer['SurveyResultAnswer']['answer'], 'maxlength' => $attributes[1]));
		else
			echo $form->text('answer', array('maxlength' => $attributes[1]));
	}
    
	function serialiseAnswer($data, $attributes)
	{
		return $data['Public']['answer'];
	}
	
	function validateAnswer($data, $attributes, &$error)
	{
		$answer = $this->serialiseAnswer($data, $attributes);
		
		if (isset($attributes[1]) && '' != $attributes[1]) {
			if (strlen($answer) > $attributes[1]) {
				$error = 'Answer must be fewer than '.$attributes[1].' characters long.';
				return false;
			}
		}
		if (isset($attributes[2]) && $attributes[2] != '') {
			$matches = array();
			if (1 != preg_match_all($attributes[2], $answer, $matches)) {
				if (isset($attributes[3]) && $attributes[3] != '') {
					$error = $attributes[3];
				}
				else {
					$error = 'Answer does not match regular expression '.$attributes[2].'.';
				}
				return false;
			}
		}
		
		return true;
	}
}
?>
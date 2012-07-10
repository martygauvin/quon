<?php
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays information.
 * No answer is validated.
 * No answer is stored.
 */
class InformationalQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question',
											 'type' => 'html'),
	);
	
	function renderQuestion($form, $attributes)
	{
		echo $attributes[0]."<br/><br/>";
	}
	
	function serialiseAnswer($data, $attributes)
	{
		return;
	}
}
?>
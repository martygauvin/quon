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
	
	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{
		echo $attributes[0]."<br/><br/>";
	}
	
	function serialiseAnswer($data, $attibutes) {
		return '';
	}
	
	function deserialiseAnswer($data, $attributes) {
		$answer = array();
		$answer['value'] = $data;
		return $answer;
	}
}
?>
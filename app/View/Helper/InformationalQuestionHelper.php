<?php
/**
 * InformationalQuestionHelper
 * @package View.Helper
 */
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays information.
 * No answer is validated.
 * No answer is stored.
 */
class InformationalQuestionHelper extends QuestionHelper {	
	/** The attributes for the question.*/
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question',
											 'type' => 'html'),
	);
	
	/**
	 * (non-PHPdoc)
	 * @see QuestionHelper::renderQuestion()
	 * @param unknown $form As in QuestionHelper::renderQuestion()
	 * @param unknown $attributes As in QuestionHelper::renderQuestion()
	 */
	function renderQuestion($form, $attributes)
	{
		echo $attributes[0]."<br/><br/>";
	}
	
	/**
	 * Serialises the given answer.
	 * @param unknown_type $data The given answer
	 * @param unknown_type $attributes The question attributes
	 * @return A string representation of the given answer
	 */
	function serialiseAnswer($data, $attributes)
	{
		return;
	}
}
?>
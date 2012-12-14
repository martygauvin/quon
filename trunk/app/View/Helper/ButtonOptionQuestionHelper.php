<?php
/**
 * ButtonOptionQuestionHelper
 * @package View.Helper
 */
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays a set of buttons for the user to select.
 * No validation is performed.
 * Answer is stored as the value of the selected button.
 */
class ButtonOptionQuestionHelper extends QuestionHelper {	
	/** The attributes for the question.*/
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question',
											 'type' => 'html'),
    							  1 => array('name' => 'Options',
											 'help' => 'List of possible values and options, seperated by a |. e.g. "1=Yes|2=No|3=Maybe" will display "Yes", "No", and "Maybe" as options, storing the value as "1", "2", or "3" respectively depending on which is selected.'),
								  2 => array('name' => 'Include "None of the above" as an option',
								  		     'help' => 'Leave blank to disable the "None of the above" option. Otherwise enter the value to be stored when "None of the above" is selected e.g. 99')
		);
		
	/**
	 * (non-PHPdoc)
	 * @see QuestionHelper::renderQuestion()
	 * @param unknown $form As in QuestionHelper::renderQuestion()
	 * @param unknown $attributes As in QuestionHelper::renderQuestion()
	 * @param unknown $previousAnswer As in QuestionHelper::renderQuestion()
	 * @param unknown $show_next As in QuestionHelper::renderQuestion()
	 */
	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{		
		echo "<script type='text/javascript'>
			function answerButton(answerStr)
			{
				document.getElementById('PublicAnswer').value = answerStr;
				document.getElementById('PublicDirection').value = 'next';
				return true;
			}
			</script>
			";
		
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
			echo $form->input($questionText, array('type' => 'submit', 'label' => '', 'onClick' => 'javascript:return answerButton("'.$questionValue.'");'));
			echo "<br/><br/>";
		}
		
		echo $form->hidden('answer');
	
		if ($attributes[2] && strlen($attributes[2]) > 0)
		{
			echo $form->input('None of the above', array('type' => 'submit', 'label' => '', 'onClick' => 'javascript:return answerButton("'.$attributes[2].'");'));
		}
		
		$show_next = false;
	
	}
	
	/**
	 * Serialises the given answer.
	 * @param unknown_type $data The given answer
	 * @param unknown_type $attributes The question attributes
	 * @return A string representation of the given answer
	 */
	function serialiseAnswer($data, $attributes)
	{
		return $data['Public']['answer'];
	}
}
?>
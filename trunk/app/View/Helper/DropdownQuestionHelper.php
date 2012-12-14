<?php
/**
 * DropdownQuestionHelper
 * @package View.Helper
 */
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays a dropdown list of items.
 * To be valid, a value must be specified if "Other" is selected.
 * Answer is stored as the value of the selected option.
 */
class DropdownQuestionHelper extends QuestionHelper {	
	/** The attributes for the question.*/
	protected $attributes = array(0 => array('name' => 'Question Text',
	     							         'help' => 'Text to display when asking the user this question',
	     							         'type' => 'html'),
    							  1 => array('name' => 'Options',
											 'help' => 'List of possible values and options, seperated by a |. e.g. "1=Yes|2=No|3=Maybe" will display "Yes", "No", and "Maybe" as options, storing the value as "1", "2", or "3" respectively depending on which is selected.'),
								  2 => array('name' => 'Include "Other" option',
								  			 'help' => 'Leave blank to disable the "Other" option. Otherwise enter the value to be stored for "Other" is selected e.g. 88')
	);
	
	/**
	 * (non-PHPdoc)
	 * @see QuestionHelper::validateAnswer()
	 * @param $data As in QuestionHelper::validateAnswer()
	 * @param $attributes As in QuestionHelper::validateAnswer()
	 * @param $error As in QuestionHelper::validateAnswer()
	 */
	function validateAnswer($data, $attributes, &$error)
	{
		if ($attributes[2] && strlen($attributes[2]) > 0 &&
				$data['Public']['answer'] == $attributes[2] &&
				$data['Public']['answerOtherText'] == '')
		{
			$error = "Please enter a value for other in the textbox provided";
			return false;
		}
	
		return true;
	}

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
			$options[$attributes[2]] = 'Other';
		}
		
		//TODO: Move Javascript to separate file
		echo "<script type='text/javascript'>
									function checkOther()
									{
										var option = document.getElementById('PublicAnswer');
										var answerOther = document.getElementById('PublicAnswerOtherText');
										if (option) {
											if (option.value == '".$attributes[2]."')
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
	
		if ($previousAnswer) {
			$answerValue = $previousAnswer['SurveyResultAnswer']['answer'];
			$otherValue = '';
			if (strpos($answerValue, '|')) {
				$otherValue = QuestionHelper::unescapeString(substr($answerValue, 1 + strpos($answerValue, '|')));
				$answerValue = substr($answerValue, 0, strpos($answerValue, '|'));
			}
				
			echo $form->input('answer', array('type'=>'select', 'onClick' => 'javascript:checkOther();', 'options'=>$options, 'default'=>$answerValue));
			echo $form->input('answerOtherText', array('type'=>'text', 'value'=>$otherValue, 'label'=>'&nbsp;', 'style' => 'display:none;'));
		} else {
			echo $form->input('answer', array('type'=>'select', 'onClick' => 'javascript:checkOther();', 'options'=>$options));
			echo $form->input('answerOtherText', array('type'=>'text', 'label'=>'&nbsp;', 'style' => 'display:none;'));
		}
	}
	
	/**
	 * Serialises the given answer.
	 * @param unknown_type $data The given answer
	 * @param unknown_type $attributes The question attributes
	 * @return A string representation of the given answer
	 */
	function serialiseAnswer($data, $attributes)
	{
		if ($attributes[2] && strlen($attributes[2]) > 0 &&
				$data['Public']['answer'] == $attributes[2])
			return $data['Public']['answer'].'|'.QuestionHelper::escapeString($data['Public']['answerOtherText']);
		else
			return $data['Public']['answer'];
	}
}
?>
<?php
/**
 * LikertScaleQuestionHelper
 * @package View.Helper
 */
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays a list of Likert items.
 * To be valid, answer must contain a selection for each item.
 * Answer is stored as a |-delimited set of values, one for each item.
 */
class LikertScaleQuestionHelper extends QuestionHelper {	
	/** The attributes for the question.*/
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question',
											 'type' => 'html'),
								  1 => array('name' => 'Options',
								  			 'help' => 'List of possible values and options, seperated by a |. e.g. "1=Strongly disagree|2=Disagree|3=Neither agree nor disagree|4=Agree|5=Strongly agree" will display a five-point Likert scale with a value of 1 indicating "Strongly disagree" through to 5 indicating "Strongly agree".'),
								  2 => array('name' => 'Items',
		    							  	 'help' => 'Text to display for items, each separated by a |. e.g. Item 1|Item 2'),
		    					  3 => array('name' => 'Table',
		    					  			 'help' => 'Enter any value to display question as a table. Leave blank to display each item sequentially.')
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
		echo "Question: ".$attributes[0]."<br/><br/>";
		
		$table = isset($attributes[3]) && strlen($attributes[3]) > 0;
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
		$items = explode("|", $attributes[2]);
		
		// Hack to make empty strings work correctly
		foreach ($options as $index=>$option)
		{
			if ('' == $option)
			{
				$options[$index] = '&nbsp;';
			}
		}
		
		if ($table)
		{
			echo '<table>';
			$headers = $options;
			array_unshift($headers, '&nbsp;');
			echo $this->Html->tableHeaders($headers);
			$tableCells = array();
			$answerValues = array();
			if ($previousAnswer) {
				$answerValues = explode("|", $previousAnswer['SurveyResultAnswer']['answer']);
			}
			while (count($answerValues) < count($items)) {
				$answerValues[] = '';
			}
			$answerCount = 0;
			foreach ($items as $itemIndex=>$item)
			{
				// TODO: Almost certainly a more Cake-like way to create Likert table
				$row = array();
				$row[] = $item.'<input type="hidden" name="data[Public][answer'.$itemIndex.'i]" id="PublicAnswer'.$itemIndex.'i_" value=""/>';
				foreach ($options as $index=>$option)
				{
					if ($answerValues[$answerCount] == $index) {
						$row[] = '<input type="radio" name="data[Public][answer'.$itemIndex.'i]" id="PublicAnswer'.$itemIndex.'i'.$index.'" value="'.$index.'" checked="checked"/>';
					} else {
						$row[] = '<input type="radio" name="data[Public][answer'.$itemIndex.'i]" id="PublicAnswer'.$itemIndex.'i'.$index.'" value="'.$index.'"/>';
					}
				}
				$tableCells[] = $row;
				$answerCount++;
			}
			echo $this->Html->tableCells($tableCells);
			echo '</table>';
		}
		else
		{
			$answerValues = array();
			if ($previousAnswer) {
				$answerValues = explode("|", $previousAnswer['SurveyResultAnswer']['answer']);
			}
			while (count($answerValues) < count($items)) {
				$answerValues[] = '';
			}
			$answerCount = 0;
			foreach ($items as $index=>$item)
			{
				echo $form->input('answer'.$index.'i', array('type'=>'radio', 'legend'=>$item, 'options'=>$options, 'value'=>$answerValues[$answerCount]));
				$answerCount++;
			}
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
		$items = explode("|", $attributes[2]);
		
		$results = array();
		
		foreach ($items as $index=>$item)
		{
			$results[] = $data['Public']['answer'.$index.'i'];
		}
		
		return implode("|", $results);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see QuestionHelper::validateAnswer()
	 * @param $data As in QuestionHelper::validateAnswer()
	 * @param $attributes As in QuestionHelper::validateAnswer()
	 * @param $error As in QuestionHelper::validateAnswer()
	 */
	function validateAnswer($data, $attributes, &$error)
	{
		$answers = $this->serialiseAnswer($data, $attributes);

		if ($answers)
		$answers = explode('|', $answers);
		else
		$answers = array();

		foreach ($answers as $answer) {
			if (!isset($answer) || '' == $answer)
			{
				$error = 'Please select an option for each item.';
				return false;
			}
		}
		return true;
	}
}
?>
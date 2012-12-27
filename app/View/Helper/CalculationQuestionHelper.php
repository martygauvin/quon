<?php
/**
 * CalculationQuestionHelper
 * @package View.Helper
 */
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays a calculation (only if "Display" is non-empty).
 * No validation is performed.
 * Answer is stored as the value of the calculation.
 */
class CalculationQuestionHelper extends QuestionHelper {	
	/** The attributes for the question.*/
	protected $attributes = array(0 => array('name' => 'Calculation', 
											 'help' => 'Enter the formula for the calculation. Valid operators are: + (addition); - (subtraction); * (multiplication); / (division). Names of questions can be entered in square brackets. e.g. "[mass]/([height]*[height])"'),
    							  1 => array('name' => 'Display', 
    							  			 'help' => 'If blank, calculation is not displayed. Otherwise the value from this field is displayed, with "[value]" replaced with the calculated value. e.g. "Your BMI is: [value]"',
								  			  'type' => 'html'),
								  2 => array('name' => 'Error value',
								  			  'help' => 'The value to use if an error occurs in the calculation. Defaults to "error".'),
								  3 => array('name' => 'Decimal points',
								  		      'help' => 'The number of decimal points the answer should be rounded to (leave blank to perform no rounding).')
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
		$calculatedValue = 0;
		if ($previousAnswer) {
			$calculatedValue = $previousAnswer['SurveyResultAnswer']['answer'];
		}
		
		echo str_replace('[value]', $calculatedValue, $attributes[1]);
		echo $form->hidden('answer', array('value' => $calculatedValue));
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
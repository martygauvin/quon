<?php
App::uses('AppHelper', 'View/Helper');

class CalculationQuestionHelper extends QuestionHelper {	
	
	protected $attributes = array(0 => array('name' => 'Calculation', 
											 'help' => 'Enter the formula for the calculation. Valid operators are: + (addition); - (subtraction); * (multiplication); / (division). Names of questions can be entered in square brackets. e.g. "[mass]/([height]*[height])"'),
    							  1 => array('name' => 'Display', 
    							  			 'help' => 'If blank, calculation is not displayed. Otherwise the value from this field is displayed, with "[value]" replaced with the calculated value. e.g. "Your BMI is: [value]"',
								  			  'type' => 'html'),
								  2 => array('name' => 'Error value',
								  			  'help' => 'The value to use if an error occurs in the calculation. Defaults to "error".')
	);
	
	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{	
		$calculatedValue = 0;
		if ($previousAnswer) {
			$calculatedValue = $previousAnswer['SurveyResultAnswer']['answer'];
		}
		
		echo str_replace('[value]', $calculatedValue, $attributes[1]);
		echo $form->hidden('answer', array('value' => $calculatedValue));
	}
    
	function serialiseAnswer($data, $attributes)
	{
		return $data['Public']['answer'];
	}
}
?>
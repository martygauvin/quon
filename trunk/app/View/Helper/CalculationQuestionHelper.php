<?php
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays a calculation (only if "Display" is non-empty).
 * No validation is performed.
 * Answer is stored as the value of the calculation.
 */
class CalculationQuestionHelper extends QuestionHelper {	
	
	protected $attributes = array(0 => array('name' => 'Calculation', 
											 'help' => 'Enter the formula for the calculation. Valid operators are: + (addition); - (subtraction); * (multiplication); / (division). Names of questions can be entered in square brackets. e.g. "[mass]/([height]*[height])"'),
								  1 => array('name' => 'Javascript',
								  			  'help' => 'If necessary, enter the name of a Javascript function that takes the value from the calculation as is only parameter and returns the result to store. Note: only alphanumeric characters, _, and $ are valid here.'),
								  2 => array('name' => 'Error value',
								  			  'help' => 'The value to use if an error occurs in the calculation. Defaults to "error".'),
	);
	
	function convertAnswer($data, $attributes) {
		return $data['Public']['answer'];
	}
    
	function serialiseAnswer($data, $attributes)
	{
		return $data['value'];
	}
	
	function deserialiseAnswer($data, $attributes) {
		$answer = array();
		$answer['value'] = $data;
		return $answer;
	}
	
	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{	
		$calculatedValue = 0;
		if ($previousAnswer) {
			$calculatedValue = $previousAnswer['SurveyResultAnswer']['answer'];
		}
		
		echo $form->hidden('answer', array('value' => htmlspecialchars($calculatedValue)));
		echo "
			<script type='text/javascript'>
				$('#PublicQuestionForm').hide();
				
				$(document).ready(function() {
				";
		if (strlen($attributes[1]) > 0) {
			$errorValue = 'error';
			if (strlen($attributes[2]) > 0) {
				$errorValue = $attributes[2];
			}
			$validFunctionName = preg_match("/^[A-Za-z_\$][A-Za-z0-9_\$]*$/", $attributes[1]);
			if ($validFunctionName) {
			echo "
				if (typeof ".$attributes[1]." == 'function') {
					$('#PublicAnswer').val(".$attributes[1]."(\"".htmlspecialchars($calculatedValue)."\"));
				} else {
					$('#PublicAnswer').val(\"".$errorValue."\");
				}
			";
			} else {
				echo "$('#PublicAnswer').val(\"".$errorValue."\");";
			}
		}
		echo "
					questionSubmit('next');
				});
			</script>
		";
	}
}
?>
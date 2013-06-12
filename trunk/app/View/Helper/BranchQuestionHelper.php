<?php
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that implements a branch in the survey.
 * No validation is performed.
 * No answer is stored.
 */
class BranchQuestionHelper extends QuestionHelper {	
	protected $attributes = array(0 => array('name' => 'Rule', 
											 'help' => 'Question in the form of \'[questionname] = value\'. Leave blank to unconditionally always jump to the positive destination. Use the "&" (for and) or "|" (for or) symbol to combine multiple conditions. The following conditions are supported: =, !=, <, >, <=, >=. Conditions can be grouped in parentheses "()"'),
    							  1 => array('name' => 'Positive Destination', 
    							  			 'help' => 'The name of the page to jump to if the expression is true. Leave blank to continue to next object.'),
							  	  2 => array('name' => 'Negative Destination', 
    							  			 'help' => 'The name of the page to jump to if the expression is false Leave blank to continue to next object.'),
		);

        function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
        {
		echo $attributes[0];

		echo $form->input('branchDestination', array('type' => 'hidden', 'value' => $previousAnswer));
        }
        
        function serialiseAnswer($data, $attibutes) {
        	return '';
        }
        
        function deserialiseAnswer($data, $attributes) {
        	$answer = array();
        	$answer['value'] = $data;
        	return $answer;
        }
	
	function validateConfig($object)
	{
		$validation = array();
		$validation['object'] = $object;
		$validation['errors'] = array();
	
		$attributes = $object['SurveyObjectAttribute'];
			
		$objects = array();
		if ($attributes[1]['value'] != '')
			$objects[] = $attributes[1]['value'];
		
		if ($attributes[2]['value'] != '')
			$objects[] = $attributes[2]['value'];
		
		$validation['objects'] = $objects;
		
		return $validation;
	}

}
?>

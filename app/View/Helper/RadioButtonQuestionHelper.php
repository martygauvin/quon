<?php
App::uses('AppHelper', 'View/Helper');

class RadioButtonQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => 'Question Text',
    							  1 => 'Options',
								  2 => 'Include "None of the above" as an option',
								  3 => 'Include "Other" option'
	);
	

}
?>
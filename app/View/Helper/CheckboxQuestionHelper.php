<?php
App::uses('AppHelper', 'View/Helper');

class CheckboxQuestionHelper extends QuestionHelper  {	
    
	protected $attributes = array(0 => 'Question Text',
    							  1 => 'Options',
    							  2 => 'Minimum number of options to be selected',
    							  3 => 'Maximum number of options to be selected',
								  4 => 'Include "None of the above" as an option',
								  5 => 'Include "Other" option'
	);

}
?>
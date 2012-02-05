<?php
App::uses('AppHelper', 'View/Helper');

class CheckboxQuestionHelper extends QuestionHelper  {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question'),
    							  1 => array('name' => 'Options',
    							  			 'help' => 'List of possible options, each seperate by a |. e.g. Yes|No|Maybe'),
    							  2 => array('name' => 'Minimum number of options to be selected',
    							  			 'help' => 'Number representing the minumum number of answers that the user has to select'),
    							  3 => array('name' => 'Maximum number of options to be selected',
    							   			 'help' => 'Number representing the maximum number of answers that the user has to select'),
								  4 => array('name' => 'Include "None of the above" as an option',
								  		     'help' => 'Enter "yes" if you wish to include an extra option for "none of the above"'),
								  5 => array('name' => 'Include "Other" option',
								  			 'help' => 'Enter "yes" if you wish to include an extra option for "other"')
	);

}
?>
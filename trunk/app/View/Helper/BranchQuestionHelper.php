<?php
App::uses('AppHelper', 'View/Helper');

class BranchQuestionHelper extends QuestionHelper {	
	
	protected $attributes = array(0 => array('name' => 'Rule', 
											 'help' => 'Question in the form of \'[pagename] = "value"\'. Leave blank to unconditionally always jump to the positive destination.'),
    							  1 => array('name' => 'Positive Destination', 
    							  			 'help' => 'The name of the page to jump to if the expression is true. Leave blank to continue to next object.'),
							  	  2 => array('name' => 'Negative Destination', 
    							  			 'help' => 'The name of the page to jump to if the expression is false Leave blank to continue to next object.'),
		);
	


}
?>
<?php
App::uses('AppHelper', 'View/Helper');

class TextQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => 'Question Text',
    							  1 => 'Answer Length',
    							  2 => 'Match Regular Expression'
	);
    
}
?>
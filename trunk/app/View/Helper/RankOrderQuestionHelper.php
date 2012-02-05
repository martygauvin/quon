<?php
App::uses('AppHelper', 'View/Helper');

class RankOrderQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => 'Question Text',
    							  1 => 'Options',
    							  2 => 'Maximum number of options to be ranked',
								  3 => 'Include "None of the above" as an option',
								  4 => 'Include "Other" option'
	);
	

}
?>
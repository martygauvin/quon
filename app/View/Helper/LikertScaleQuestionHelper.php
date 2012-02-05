<?php
App::uses('AppHelper', 'View/Helper');

class LikertScaleQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => 'Question Text',
    							  1 => 'Left label',
    							  2 => 'Right label',
								  3 => 'Number of graduations'
	);

}
?>
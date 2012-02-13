<?php
App::uses('AppHelper', 'View/Helper');

class LikertScaleQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question'),
    							  1 => array('name' => 'Left label',
    							  			 'help' => 'Text to display for the left-most label'),
    							  2 => array('name' => 'Right label',
    							  			 'help' => 'Text to display for the right-most label'),
								  3 => array('name' => 'Number of graduations',
								  			 'help' => 'Number of options to display between the two labels')
	);

	function renderQuestion($form, $attributes)
	{
		// TODO: Render LikeRT scale question type
	}
	
	function serialiseAnswer($data)
	{
		// TODO: Serialise answer for LikeRT question type
		return;
	}
}
?>
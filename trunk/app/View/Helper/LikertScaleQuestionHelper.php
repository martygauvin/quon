<?php
App::uses('AppHelper', 'View/Helper');

class LikertScaleQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question'),
								  1 => array('name' => 'Options',
								  			 'help' => 'Options to display for each item, each separated by a |. e.g. For a five-point Likert scale: Strongly disagree|Disagree|Neither agree nor disagree|Agree|Strongly agree'),
								  2 => array('name' => 'Items',
		    							  	 'help' => 'Text to display for items, each separated by a |. e.g. Item 1|Item 2'),
	);

	function renderQuestion($form, $attributes)
	{
		echo "Question: ".$attributes[0]."<br/><br/>";
		
		$options = explode("|", $attributes[1]);
		$items = explode("|", $attributes[2]);
		
		// Hack to make empty strings work correctly
		foreach ($options as $index=>$option)
		{
			if ('' == $option)
			{
				$options[$index] = '&nbsp;';
			}
		}
		
		foreach ($items as $index=>$item)
		{
			echo $form->input('answer'.$index.'i', array('type'=>'radio', 'legend'=>$item, 'options'=>$options));
		}
	}
	
	function serialiseAnswer($data, $attributes)
	{
		$items = explode("|", $attributes[2]);
		
		$results = array();
		
		foreach ($items as $index=>$item)
		{
			$results[] = $data['Public']['answer'.$index.'i'];
		}
		
		return implode("|", $results);
	}
}
?>
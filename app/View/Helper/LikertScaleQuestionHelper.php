<?php
App::uses('AppHelper', 'View/Helper');

class LikertScaleQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question'),
								  1 => array('name' => 'Options',
								  			 'help' => 'Options to display for each item, each separated by a |. e.g. For a five-point Likert scale: Strongly disagree|Disagree|Neither agree nor disagree|Agree|Strongly agree'),
								  2 => array('name' => 'Items',
		    							  	 'help' => 'Text to display for items, each separated by a |. e.g. Item 1|Item 2'),
		    					  3 => array('name' => 'Table',
		    					  			 'help' => 'Enter "yes" if you wish to display each item in a table')
	);

	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{
		echo "Question: ".$attributes[0]."<br/><br/>";
		
		$table = isset($attributes[3]) && 'yes' == $attributes[3];
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
		
		// TODO: Implement load previous answer for like RT question type
		if ($table)
		{
			echo '<table>';
			$headers = $options;
			array_unshift($headers, '&nbsp;');
			echo $this->Html->tableHeaders($headers);
			$tableCells = array();
			foreach ($items as $itemIndex=>$item)
			{
				// TODO: Almost certainly a more Cake-like way to create Likert table
				$row = array();
				$row[] = $item.'<input type="hidden" name="data[Public][answer'.$itemIndex.'i]" id="PublicAnswer'.$itemIndex.'i_" value=""/>';
				foreach ($options as $index=>$option)
				{
					$row[] = '<input type="radio" name="data[Public][answer'.$itemIndex.'i]" id="PublicAnswer'.$itemIndex.'i'.$index.'" value="'.$index.'"/>';
				}
				$tableCells[] = $row;
			}
			echo $this->Html->tableCells($tableCells);
			echo '</table>';
		}
		else
		{
			foreach ($items as $index=>$item)
			{		
				echo $form->input('answer'.$index.'i', array('type'=>'radio', 'legend'=>$item, 'options'=>$options));
			}
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
	
	function validateAnswer($data, $attributes, &$error)
	{
		$answers = $this->serialiseAnswer($data, $attributes);

		if ($answers)
		$answers = explode('|', $answers);
		else
		$answers = array();

		foreach ($answers as $answer) {
			if (!isset($answer) || '' == $answer)
			{
				$error = 'Please select an option for each item.';
				return false;
			}
		}
		return true;
	}
}
?>
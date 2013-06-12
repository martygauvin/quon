<?php
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays a list of Likert items.
 * To be valid, answer must contain a selection for each item.
 * Answer is stored as a |-delimited set of values, one for each item.
 */
class LikertScaleQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question',
											 'type' => 'html'),
								  1 => array('name' => 'Options',
								  			 'help' => 'List of possible values and options, seperated by a |. e.g. "1=Strongly disagree|2=Disagree|3=Neither agree nor disagree|4=Agree|5=Strongly agree" will display a five-point Likert scale with a value of 1 indicating "Strongly disagree" through to 5 indicating "Strongly agree".'),
								  2 => array('name' => 'Items',
		    							  	 'help' => 'Text to display for items, each separated by a |. e.g. 1=Item 1|2=Item 2'),
		    					  3 => array('name' => 'Table',
		    					  			 'help' => 'Enter any value to display question as a table. Leave blank to display each item sequentially.'),
								  4 => array('name' => 'Mandatory',
								  		      'help' => 'Set to "true" if you wish the user to not be able to progress without selecting an option for each item.')			
	);

	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{
		$field_name = $attributes['id'].'_answer';
		
		echo $attributes[0]."<br/><br/>";
		
		$table = isset($attributes[3]) && strlen($attributes[3]) > 0;
		$options = array();
		$questionOptions = split("\|", $attributes[1]);
		foreach ($questionOptions as $questionOption)
		{
			$questionValue = QuestionHelper::getKey($questionOption);
			$questionText = QuestionHelper::getValue($questionOption);
			$options[$questionValue] = $questionText;
		}
		$items = explode("|", $attributes[2]);
		
		// Hack to make empty strings work correctly
		foreach ($options as $index=>$option)
		{
			if ('' == $option)
			{
				$options[$index] = '&nbsp;';
			}
		}
		echo $form->hidden($field_name, array('value' => ''));
		if ($table)
		{
			echo '<table>';
			$headers = $options;
			array_unshift($headers, '&nbsp;');
			echo $this->Html->tableHeaders($headers);
			$tableCells = array();
			$answerValues = array();
			if ($previousAnswer) {
				$answerValues = explode("|", $previousAnswer['SurveyResultAnswer']['answer']);
			}
			while (count($answerValues) < count($items)) {
				$answerValues[] = '';
			}
			$answerCount = 0;
			
			foreach ($items as $itemIndex=>$item)
			{
				$itemLabel = QuestionHelper::getValue($item);
				// TODO: Almost certainly a more Cake-like way to create Likert table
				$row = array();
				$row[] = $itemLabel.'<input type="hidden" name="data[Public]['.$field_name.$itemIndex.'i]" id="PublicAnswer'.$itemIndex.'i_" value=""/>';
				foreach ($options as $index=>$option)
				{
					if ($answerValues[$answerCount] == $index) {
						$row[] = '<input type="radio" name="data[Public]['.$field_name.$itemIndex.'i]" id="PublicAnswer'.$field_name.$itemIndex.'i'.$index.'" value="'.$index.'" checked="checked"/>';
					} else {
						$row[] = '<input type="radio" name="data[Public]['.$field_name.$itemIndex.'i]" id="PublicAnswer'.$field_name.$itemIndex.'i'.$index.'" value="'.$index.'"/>';
					}
				}
				$tableCells[] = $row;
				$answerCount++;
			}
			echo $this->Html->tableCells($tableCells);
			echo '</table>';
		}
		else
		{
			$answerValues = array();
			if ($previousAnswer) {
				$answerValues = explode("|", $previousAnswer['SurveyResultAnswer']['answer']);
			}
			while (count($answerValues) < count($items)) {
				$answerValues[] = '';
			}
			$answerCount = 0;
			foreach ($items as $index=>$item)
			{
				$itemLabel = QuestionHelper::getValue($item);
				echo $form->input($field_name.$index.'i', array('type'=>'radio', 'legend'=>$itemLabel, 'options'=>$options, 'value'=>$answerValues[$answerCount]));
				$answerCount++;
			}
		}
	}
	
	function convertAnswer($data, $attributes)
	{
		$field_name = $attributes['id'].'_answer';
		
		$items = explode("|", $attributes[2]);
		
		$results = array();
		$string = '';
		
		foreach ($items as $index=>$item)
		{
			$key = QuestionHelper::getKey($item);
			$results[$key] = $data['Public'][$field_name.$index.'i'];
			$string = $string.','.$results[$key];
		}
		if (strlen($string) > 0) {
			$string = substr($string, 1);
		}
		$results['value_string'] = $string;
		return $results;
	}
	
	function serialiseAnswer($data, $attibutes) {
		$newData = array();
		foreach ($data as $key=>$datum) {
			if ($key !== 'value_string') {
				$newData[$key] = $datum;
			}
		}
		return implode('|', $newData);
	}
	
	function deserialiseAnswer($data, $attributes) {
		$values = explode('|', $data);
		$items = explode('|', $attributes[2]);
		while (count($items) > count($values)) {
			$values[count($values)] = '';
		}
		$results = array();
		$string = '';
		foreach ($items as $num=>$item) {
			$key = QuestionHelper::getKey($item);
			$value = $values[$num];
			$results[$key] = $value;
			$string = $string.','.$value;
		}
		if (strlen($string) > 0) {
			$string = substr($string, 1);
		}
		$results['value_string'] = $string;
		return $results;
	}
	
	function validateAnswer($data, $attributes, &$error)
	{
		if (isset($attributes[4]) && $attributes[4] && strlen($attributes[4]) > 0) {
			$answers = $this->serialiseAnswer($data, $attributes);

			if ($answers) {
				$answers = explode('|', $answers);
			} else {
				$answers = array();
			}

			foreach ($answers as $answer) {
				if (!isset($answer) || '' == $answer)
				{
					$error = 'Please select an option for each item.';
					return false;
				}
			}
		}
		return true;
	}
}
?>
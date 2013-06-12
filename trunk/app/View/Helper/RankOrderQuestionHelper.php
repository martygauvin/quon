<?php
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays a list of options and gets user to rank them.
 * To be valid, at least "Minimum number of options to be ranked" and at most "Maximum number of options to be ranked"
 * must be given a rank from 1 through to the maximum number (or "None of the above" selected).
 * Answer is stored as a |-delimited list of rankings.
 */
class RankOrderQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question',
											 'type' => 'html'),
    							  1 => array('name' => 'Options',
    							  			 'help' => 'List of possible options, each seperate by a |. e.g. 1=Water|2=Milk|3=Wine'),
    							  2 => array('name' => 'Minimum number of options to be ranked',
    							  			 'help' => 'Number representing the minimum number of options to be ranked'),
    							  3 => array('name' => 'Maximum number of options to be ranked',
    							  			 'help' => 'Number representing the maximum number of options to be ranked'),
								  4 => array('name' => 'Include "None of the above" as an option',
								  		     'help' => 'Leave blank to disable the "None of the above" option. Otherwise an option of "None of the above" will be presented with given value.'),
								  5 => array('name' => 'Include "Other" option',
								  			 'help' => 'Leave blank to disable the "Other" option. Otherwise an option of "Other" will be presented with given value.')
		);
		
	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{
		$field_name = $attributes['id'].'_answer';
		
		echo $attributes[0]."<br/><br/>";
		
		$questionOptions = split("\|", $attributes[1]);
		
		$none = false;
		$previousAnswers = array();
		
		if (isset($previousAnswer) && isset($previousAnswer['SurveyResultAnswer']['answer'])) {
			if ($previousAnswer['SurveyResultAnswer']['answer'] == 'none') {
				$none = true;
			} else {
				$previousAnswers = split("\|", $previousAnswer['SurveyResultAnswer']['answer']);
			}
		}
		
		while (count($previousAnswers) < count($questionOptions))
		{
			$previousAnswers[] = '';
		}
		
		echo $form->hidden($field_name, array('value' => ''));
		foreach ($questionOptions as $number=>$option) {
			$label = QuestionHelper::getValue($option);
			echo $form->input($field_name.$number, array('label'=>$label, 'value'=>$previousAnswers[$number]));
		}
		
		if (isset($attributes[5]) && strlen($attributes[5]) > 0)
		{
			while (count($previousAnswers) < count($questionOptions) + 2) {
				$previousAnswers[] = '';
			}
			echo $form->input($field_name.'other', array('label'=>'Other', 'value'=>$previousAnswers[count($questionOptions)]));
			echo $form->input($field_name.'othertext', array('label'=>'Please specify', 'value'=>QuestionHelper::unescapeString($previousAnswers[count($questionOptions) + 1])));
		}
		
		if (isset($attributes[4]) && strlen($attributes[4]) > 0)
		{
			//TODO: Move Javascript to separate file
			echo "<script type='text/javascript'>
							function checkNone".$attributes['id']."()
							{
								var option = document.getElementById('Public".$attributes['id']."Answernone');
								if (option) {
									if (option.checked) {
										$('[id^=Public".$attributes['id']."Answer]:text').attr('disabled', true);								
									} else {
										$('[id^=Public".$attributes['id']."Answer]:text').removeAttr('disabled');
									}
								}
							}
							
							$(document).ready(function() {checkNone".$attributes['id']."();});
						  </script>
					";
			echo $form->input($field_name.'none', array('type'=>'checkbox', 'label'=>'None of the above',
				'value'=>'1', 'checked'=>$none, 'onclick'=>'checkNone'.$attributes['id'].'();'));
		}
	}
	
	function validateAnswer($data, $attributes, &$error)
	{
		// none of the above
		if ($attributes[4] && strlen($attributes[4]) > 0 && strlen($data[$attributes[4]]) > 0) {
			return true;
		}
		
		$rawanswers = array();
		
		// other
		if ($attributes[5] && strlen($attributes[5]) > 0 && strlen($data[$attributes[5]]) > 0) {
			if (strlen($data[$attributes[5].'_text']) <= 0) {
				$error = 'Other option must be specified';
				return false;
			}
			$rawanswers[] = $data[$attributes[5]];
		}
		
		$options = explode("|", $attributes[1]);
		
		foreach ($options as $option) {
			$key = QuestionHelper::getKey($option);
			$rawanswers[] = $data[$key];
		}
		
		$answers = array();
		
		foreach ($rawanswers as $rawanswer) {
			if (strlen($rawanswer) > 0) {
				if (!is_numeric($rawanswer)) {
					$error = 'Answers must be positive integers';
					return false;
				}
				$answer = intval($rawanswer);
				if ($answer < 1 || $answer > count($rawanswers)) {
					$error = 'Ranking out of range';
					return false;
				}
				if (in_array($answer, $answers)) {
					$error = 'Rankings must be unique';
					return false;
				}
				$answers[] = $answer;
			} else {
				$answers[] = $rawanswer;
			}
		}
		
		$max = max($answers);
		if ($max > count($answers)) {
			$error = "Ranking out of range";
			return false;
		}
		
		for ($i = 1; $i < $max; $i++) {
			if (!in_array($i, $answers)) {
				$error = 'Gap in rankings';
				return false;
			}
		}
		
		$minRank = $attributes[2];
		$maxRank = $attributes[3];
		
		if (is_numeric($minRank) && $minRank > 0 && $minRank <= count($answers)) {
			if ($max < intval($minRank)) {
				$error = 'Must rank at least '.$minRank.' options';
				return false;
			}
		}
		if (is_numeric($maxRank) && $maxRank > 0 && $maxRank <= count($answers)) {
			if ($max > intval($maxRank)) {
				$error = 'Must rank at most '.$maxRank.' options';
				return false;
			}
		}
		
		return true;
	}
	
	function convertAnswer($data, $attributes) {
		$field_name = $attributes['id'].'_answer';
		$options = explode("|", $attributes[1]);
		$results = array();
		$string = '';
						
		if (isset($data['Public'][$field_name.'none']) && $data['Public'][$field_name.'none'] != 0) {
			if ($attributes[4] && strlen($attributes[4]) > 0) {
				foreach ($options as $option) {
					$key = QuestionHelper::getKey($option);
					$results[$key] = '';
				}
				$results[$attributes[4]] = '1';
				if ($attributes[5] && strlen($attributes[5]) > 0) {
					$results[$attributes[5]] = '';
					$results[$attributes[5].'_text'] = '';
				}
				$results['value_string'] = 'none';
				return $results;
			}
		}
		
		foreach ($options as $number=>$option)
		{
			$key = QuestionHelper::getKey($option);
			$answer = $data['Public'][$field_name.$number];
			$results[$key] = $answer;
			$string = $string.','.$answer;
		}
		
		if ($attributes[4] && strlen($attributes[4]) > 0) {
			$noneSelected = '';
			$string = $string.',';
			if ($data['Public'][$field_name.'none'] != 0) {
				$noneSelected = '1';
				$string = $string.'1';
			} else {
				$string = $string.'0';
			}
			$results[$attributes[4]] = $noneSelected;
		}
		
		if ($attributes[5] && strlen($attributes[5]) > 0)
		{
			$results[$attributes[5]] = $data['Public'][$field_name.'other'];
			$results[$attributes[5].'_text'] = QuestionHelper::escapeString($data['Public'][$field_name.'othertext']);
			$string = $string.','.$results[$attributes[5]];
		}
		
		if (strlen($string) > 0) {
			$string = substr($string, 1);
		}
		$results['value_string'] = $string;
		
		return $results;
	}
	
	function serialiseAnswer($data, $attributes)
	{
		// none of the above
		if ($attributes[4] && strlen($attributes[4]) && strlen($data[$attributes[4]]) > 0) {
			return 'none';
		}
		
		$options = split("\|", $attributes[1]);
		
		$results = array();
		
		foreach ($options as $option)
		{
			$key = QuestionHelper::getKey($option);
			$answer = $data[$key];
			$results[] = $answer;
		}
		
		if ($attributes[5] && strlen($attributes[5]) > 0)
		{
			$results[] = $data[$attributes[5]];
			$results[] = QuestionHelper::escapeString($data[$attributes[5].'_text']);
		}
		
		return implode("|", $results);
	}
	
	function deserialiseAnswer($data, $attributes) {
		$answers = array();
		$string = '';
		$options = explode('|', $attributes[1]);
		
		if ($data === 'none') {
			foreach ($options as $option) {
				$key = QuestionHelper::getKey($option);
				$answers[$key] = '';
			}
			$answers[$attributes[4]] = '1';
			if ($attributes[5] && strlen($attributes[5]) > 0) {
				$answers[$attributes[5]] = '';
				$answers[$attributes[5].'_text'] = '';
			}
			$answers['value_string'] = $data;
			return $answers;
		}
		
		$results = explode('|', $data);
		foreach ($options as $num=>$option) {
			$key = QuestionHelper::getKey($option);
			$answers[$key] = $results[$num];
			$string = $string.','.$results[$num];
		}
		
		if ($attributes[4] && strlen($attributes[4]) > 0) {
			$answers[$attributes[4]] = '';
			$string = $string.',0';
		}
		
		if ($attributes[5] && strlen($attributes[5]) > 0) {
			$answers[$attributes[5]] = $results[count($results) - 2];
			$answers[$attributes[5].'_text'] = $results[count($results) - 1];
			$string = $string.','.$answers[$attributes[5]];
		}
		
		if (strlen($string) > 0) {
			$string = substr($string, 1);
		}
		$answers['value_string'] = $string;
		return $answers;
	}
}
?>
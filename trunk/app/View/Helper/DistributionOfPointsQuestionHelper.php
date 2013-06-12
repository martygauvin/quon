<?php
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays a list of options and gets user to give them a number of points.
 * To be valid, the number of points assigned must add to between the minimum and maximum number provided (inclusive).
 * Answer is stored as a |-delimited list of rankings.
 */
class DistributionOfPointsQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question',
											 'type' => 'html'),
								  1 => array('name' => 'Options',
											 'help' => 'List of possible options, each seperate by a |. e.g. 1=Water|2=Milk|3=Wine'),
			    				  2 => array('name' => 'Maximum number of points to distribute',
    							  			 'help' => 'The maximum number of points to be distributed between the options (leave blank for no maximum)'),
								  3 => array('name' => 'Minimum number of points to distribute',
											 'help' => 'The minimum number of points to be distributed between the options (leave blank for no minimum)')
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
			echo $form->input($field_name.$number, array('type'=>'number','label'=>$label, 'value'=>$previousAnswers[$number], 'onchange'=>'javascript:updateTotal'.$attributes['id'].'()'));
		}
		
		//TODO: Move Javascript to separate file
		echo "<div id='points_total'>";
		echo "<span id='total_wrapper".$attributes['id']."'>Total: <span id='total".$attributes['id']."'>0</span></span>";
		echo "</div>";
		echo "<script type='text/javascript'>
		function updateTotal".$attributes['id']."() {
			$('#total".$attributes['id']."').text(calculateTotal".$attributes['id']."());
		}
		function calculateTotal".$attributes['id']."() {
			var total = 0;
			$('input[id^=\"Public".$attributes['id']."Answer\"]').each(function(index) {
				if (isNumber".$attributes['id']."($(this).val())) {
					total += parseInt($(this).val());
				}
			})
			return total;
		}
		function isNumber".$attributes['id']."(n) {
			return !isNaN(parseInt(n)) && isFinite(n);
		}
		$(document).ready(function() {
			updateTotal".$attributes['id']."()
		});
		</script>
		";
	}
	
	function validateAnswer($data, $attributes, &$error)
	{
		$options = explode("|", $attributes[1]);
		$rawanswers = array();
		
		foreach ($options as $option) {
			$key = QuestionHelper::getKey($option);
			$rawanswers[] = $data[$key];
		}
		
		$answers = array();
		$tally = 0;
		
		foreach ($rawanswers as $rawanswer) {
			if ($rawanswer != '') {
				if (!is_numeric($rawanswer)) {
					$error = 'Answers must be integers';
					return false;
				}
				$answer = intval($rawanswer);
				$tally += $answer;
				$answers[] = $answer;
			} else {
				$answers[] = $rawanswer;
			}
		}
		
		$minTally = $attributes[3];
		$maxTally = $attributes[2];
		
		if (is_numeric($minTally) && $minTally > 0 && $tally < $minTally)
		{
			$error = "Points must add to at least " . $minTally;
			return false;
		}
		
		if (is_numeric($maxTally) && $maxTally > 0 && $tally > $maxTally)
		{
			$error = "Points must add to at most " . $maxTally;
			return false;
		}
		
		return true;
	}
	
	function convertAnswer($data, $attributes)
	{
		$field_name = $attributes['id'].'_answer';
		
		$options = explode("|", $attributes[1]);
		
		$results = array();
		$string = '';
		
		foreach ($options as $number=>$option)
		{
			$key = QuestionHelper::getKey($option);
			$answer = $data['Public'][$field_name.$number];
			$results[$key] = $answer;
			$string = $string.','.$answer;
		}
		if (strlen($string) > 0) {
			$string = substr($string, 1);
		}
		$results['value_string'] = $string;
		
		return $results;
	}
	
	function serialiseAnswer($data, $attributes) {
		$newData = array();
		foreach ($data as $key=>$datum) {
			if ($key !== 'value_string') {
				$newData[$key] = $datum;
			}
		}
		return implode("|", $newData);
	}
	
	function deserialiseAnswer($data, $attributes) {
		$answers = array();
		$values = explode("|", $data);
		$options = explode("|", $attributes[1]);
		$string = '';
		foreach ($options as $num=>$option) {
			$key = QuestionHelper::getKey($option);
			$value = $values[$num];
			$answers[$key] = $value;
			$string = $string.','.$value;
		}
		if (strlen($string) > 0) {
			$string = substr($string, 1);
		}
		$answers['value_string'] = $string;
		return $answers;
	}
}
?>

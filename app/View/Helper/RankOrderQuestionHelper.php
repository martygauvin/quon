<?php
App::uses('AppHelper', 'View/Helper');

class RankOrderQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question'),
    							  1 => array('name' => 'Options',
    							  			 'help' => 'List of possible options, each seperate by a |. e.g. Water|Milk|Wine'),
    							  2 => array('name' => 'Minimum number of options to be ranked',
    							  			 'help' => 'Number representing the minimum number of options to be ranked'),
    							  3 => array('name' => 'Maximum number of options to be ranked',
    							  			 'help' => 'Number representing the maximum number of options to be ranked'),
								  4 => array('name' => 'Include "None of the above" as an option',
								  		     'help' => 'Enter "yes" if you wish to include an extra option for "none of the above"'),
								  5 => array('name' => 'Include "Other" option',
								  			 'help' => 'Enter "yes" if you wish to include an extra option for "other"')
		);
		
	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{
		echo "Question: ".$attributes[0]."<br/><br/>";
		
		$questionOptions = split("\|", $attributes[1]);
		
		$none = false;
		$previousAnswers = array();
		
		if ($previousAnswer && $previousAnswer['SurveyResultAnswer']['answer']) {
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
		
		foreach ($questionOptions as $number=>$option) {
			echo $form->input('answer'.$number, array('label'=>$option, 'value'=>$previousAnswers[$number]));
		}
		
		if ($attributes[5] && $attributes[5] == 'yes')
		{
			while (count($previousAnswers) < count($questionOptions) + 2) {
				$previousAnswers[] = '';
			}
			echo $form->input('answerother', array('label'=>'Other', 'value'=>$previousAnswers[count($questionOptions)]));
			echo $form->input('answerothertext', array('label'=>'Please specify', 'value'=>$previousAnswers[count($questionOptions) + 1]));
		}
		
		if ($attributes[4] && $attributes[4] == 'yes')
		{
			//TODO: Move Javascript to separate file
			echo "<script type='text/javascript'>
							function checkNone()
							{
								var option = document.getElementById('PublicAnswernone');
								if (option.checked) {
									$(':text').attr('disabled', true);								
								} else {
									$(':text').removeAttr('disabled');
								}
							}
							
							$(document).ready(function() {checkNone();});
						  </script>
					";
			echo $form->input('answernone', array('type'=>'checkbox', 'label'=>'None of the above',
				'value'=>'1', 'checked'=>$none, 'onClick'=>'javascript:checkNone();'));
		}
	}
	
	function validateAnswer($data, $attributes, &$error)
	{
		if ($data['Public']['answernone'] && $data['Public']['answernone'] != 0) {
			if ($attributes[4] && $attributes[4] == 'yes') {
				return true;
			}
		}
		
		$options = split("\|", $attributes[1]);
		$rawanswers = array();
		
		foreach ($options as $number=>$option) {
			$rawanswers[] = $data['Public']['answer'.$number];
		}
		if ($attributes[5] && $attributes[5] == 'yes') {
			$answer = $data['Public']['answerother'];
			$rawanswers[] = $answer;
			if ($answer != '') {
				$answer = $data['Public']['answerothertext'];
				if ($answer == '') {
					$error = 'Other option must be specified';
					return false;
				}
			}
		}
		
		$answers = array();
		
		foreach ($rawanswers as $rawanswer) {
			if ($rawanswer != '') {
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
	
	function serialiseAnswer($data, $attributes)
	{
		if ($data['Public']['answernone'] && $data['Public']['answernone'] != 0) {
			if ($attributes[4] && $attributes[4] == 'yes') {
				return 'none';
			}
		}
		
		$options = split("\|", $attributes[1]);
		
		$results = array();
		
		foreach ($options as $number=>$option)
		{
			$answer = $data['Public']['answer'.$number];
			$results[] = $answer;
		}
		
		if ($attributes[5] && $attributes[5] == 'yes')
		{
			$results[] = $data['Public']['answerother'];
			$results[] = $data['Public']['answerothertext'];
		}
		
		return implode("|", $results);
	}
}
?>
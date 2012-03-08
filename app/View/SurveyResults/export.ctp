<?php
	header("Content-type:application/vnd.ms-excel");
	header('Content-disposition:attachment;filename="'.$survey_instance_id.'.csv"');

	$answerIndex = array();
	$answerCnt = 0;
	
	echo 'ResultID,Participant,';
	
	foreach ($objects as $object)
	{
		echo $object['SurveyObject']['name'].",";
		$answerIndex[$object['SurveyObject']['id']] = $answerCnt;
		$answerCnt++;
	}
		
	echo "\n";
	
	foreach ($results as $result)
	{
		echo $result['SurveyResult']['id'].",";
		
		if ($result['Participant']['username'] == "")
			echo "Anonymous,";
		else
			echo $result['Participant']['username'].",";
		
		$answerTemplate = array();
		
		for ($cnt=0;$cnt<$answerCnt;$cnt++)
		{
			$answerTemplate[$cnt] = "";
		}
		
		foreach ($result['SurveyResultAnswers'] as $answer)
		{
			$answerTemplate[$answerIndex[$answer['SurveyInstanceObject']['survey_object_id']]] = $answer['SurveyResultAnswer']['answer'];
		}
		
		foreach ($answerTemplate as $answer)
		{
			echo $answer.",";
		}
			
		echo "\n";
	}
?>
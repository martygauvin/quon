<?php
	header("Content-type:application/vnd.ms-excel");
	header('Content-disposition:attachment;filename="'.$survey_instance_id.'.csv"');

	$answerIndex = array();
	$answerCnt = 0;
	
	echo 'ResultID,Participant,';
	
	foreach ($objects as $object)
	{
		$objectName = $object['SurveyObject']['name'];
		if (strpos($objectName,',') !== false || strpos($objectName,'"') !== false
				|| strpos($objectName,'\n') !== false)
		{
			$objectName = str_replace('"', '""', $objectName);
			$objectName = '"'.$objectName.'"';
		}
		echo $objectName.",";
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
		{
			$outputName = $result['Participant']['username'];
			if (strpos($outputName,',') !== false || strpos($outputName,'"') !== false
				|| strpos($outputName,'\n') !== false)
			{
				$outputName = str_replace('"', '""', $outputName);
				$outputName = '"'.$outputName.'"';
			}
			echo $outputName.",";
		}
		
		$answerTemplate = array();
		
		for ($cnt=0;$cnt<$answerCnt;$cnt++)
		{
			$answerTemplate[$cnt] = "";
		}
		
		foreach ($result['SurveyResultAnswers'] as $answer)
		{
			$outputAnswer = $answer['SurveyResultAnswer']['answer'];
			if (strpos($outputAnswer,',') !== false || strpos($outputAnswer,'"') !== false
				|| strpos($outputAnswer,'\n') !== false)
			{
				$outputAnswer = str_replace('"', '""', $outputAnswer);
				$outputAnswer = '"'.$outputAnswer.'"';
			}
			$answerTemplate[$answerIndex[$answer['SurveyInstanceObject']['survey_object_id']]] = $outputAnswer;
		}
		
		foreach ($answerTemplate as $answer)
		{
			echo $answer.",";
		}
			
		echo "\n";
	}
?>
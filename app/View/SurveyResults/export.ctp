<?php
	header("Content-type:application/vnd.ms-excel");
	header('Content-disposition:attachment;filename="'.$survey_instance_id.'.csv"');

function escape($var)
{
	if (strpos($var,',') !== false || strpos($var,'"') !== false
				|| strpos($var,'\n') !== false)
	{
		$var = str_replace('"', '""', $var);
		$var = '"'.$var.'"';
	}
	return $var;
}

function outputLine($entries)
{
	$sanitisedEntries = array();
	foreach ($entries as $key=>$entry)
	{
		$sanitisedEntries[$key] = escape($entry);
	}
	echo implode(',', $sanitisedEntries)."\n";
}

function getObjectNames($objects)
{
	$objectNames = array();
	foreach ($objects as $object)
	{
		// TODO: The below if clause is horrible
		if ($object['SurveyObject']['type'] == 12)
		{
			$objectAttributes = $object['SurveyObject']['SurveyObjectAttribute'];
			foreach ($objectAttributes as $attrib)
			{
				if ($attrib['name'] === '0')
				{
					$options = explode('|', $attrib['value']);
					foreach ($options as $option)
					{
						$objectNames[] = $object['SurveyObject']['name'].'{'.$option.'}';
					}
				}
			}
		}
		else
		{
			$objectNames[] = $object['SurveyObject']['name'];
		}
	}
	return $objectNames;
}

function getParticipantName($name)
{
	if ($name == '')
	{
		return "Anonymous";
	}
	else
	{
		return $name;
	}
}

function getResults($answers, $blankResponse, $objects)
{
	$results = array();
	foreach ($answers as $answer)
	{
		$objectName = $answer['SurveyInstanceObject']['SurveyObject']['name'];
		// TODO: The below if clause is horrible
		if ($answer['SurveyInstanceObject']['SurveyObject']['type'] == 12)
		{
			foreach ($objects as $object)
			{
				if ($object['SurveyObject']['name'] === $objectName)
				{
					$attributes = $object['SurveyObject']['SurveyObjectAttribute'];
					foreach ($attributes as $attribute)
					{
						if ($attribute['name'] === '0')
						{
							$questions = explode('|', $attribute['value']);
							$answers = explode('!!', $answer['SurveyResultAnswer']['answer']);
							foreach ($questions as $key=>$question)
							{
								$name = $objectName.'{'.$question.'}';
								$response = $answers[$key];
								if ($response === '')
								{
									$response = $blankResponse;
								}
								$results[$name] = $response;
							}
						}
					}
				}
			}
		}
		else
		{
			$response = $answer['SurveyResultAnswer']['answer'];
			if ($response === '')
			{
				$response = $blankResponse;
			}
			$results[$objectName] = $response;
		}
	}
	return $results;
}

$line = array();
$line[] = 'Survey';
$line[] = 'Instance';
$line[] = 'ResultID';
$line[] = 'Participant';
$objectNames = getObjectNames($objects);
foreach ($objectNames as $objectName)
{
	$line[] = $objectName;
}

outputLine($line);

foreach ($results as $result)
{
	$line = array();
	$line[] = $survey['Survey']['name'];
	$line[] = $instance['SurveyInstance']['name'];
	$line[] = $result['SurveyResult']['id'];
	$line[] = getParticipantName($result['Participant']['username']);
	$values = getResults($result['SurveyResultAnswers'], $blankResponse, $objects);
	foreach ($objectNames as $objectName)
	{
		$value = $nullResponse;
		if (isset($values[$objectName]))
		{
			$value = $values[$objectName];
		}
		$line[] = $value;
	}
	outputLine($line);
}
?>
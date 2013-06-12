<?php
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that displays a calendar for the user to select a date.
 * No validation of answer occurs.
 * Answer is stored as the value entered by the user, if "Answer Type" is "value".
 * Otherwise, the number of seconds between the selected date and teh value in "Differential date".
 */
class CalendarQuestionHelper extends QuestionHelper  {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question',
											 'type' => 'html'),
    							  1 => array('name' => 'Input Type',
    							  			 'help' => 'What fields do we display: "dd MM yy", "MM yy" or "yy"'),
    							  2 => array('name' => 'Answer Type',
    							  			 'help' => 'Enter "value" or "difference" depending on the type of answer you want to store'),
    							  3 => array('name' => 'Start Date',
    							   			 'help' => 'Start date in the format yyyy-mm-dd (e.g. 2001-07-09)'),
								  4 => array('name' => 'End Date',
								  		     'help' => 'End date in the format yyyy-mm-dd (e.g. 2001-07-09)'),
								  5 => array('name' => 'Differential date',
								  			 'help' => 'If "difference" is selected as the answer type then what date do we calculate from, in yyyy-mm-dd format or leave empty for date of survey'),
								  6 => array('name' => 'Mandatory',
								  			  'help' => 'Set to "true" if you wish the user to not be able to progress without selecting an option')
	);
	
	function convertAnswer($data, $attributes) {
		$field_name = $attributes['id'].'_answer';
		
		if ($attributes[2] == 'difference')
		{
			// get base only as accurate as required
			$base = time();
			$dateFormat = 'd F Y';
			if ($attributes[1] == 'MM yy') {
				$dateFormat = 'F Y';
			} else if ($attributes[1] == 'yy') {
				$dateFormat = 'Y';
			}
			$baseDate = date($dateFormat, $base);
			if ($attributes[1] == 'MM yy') {
				$baseDate = '01 '.$baseDate;
			} else if ($attributes[1] == 'yy') {
				$baseDate = '01 January '.$baseDate;
			}
			$base = strtotime($baseDate);
			// attempt to use user-defined value rather than current time
			if ($attributes[5]) {
				$base = strtotime($attributes[5]);
			}
				
			// get provided answer
			$answer = $data['Public'][$field_name];
			if ($attributes[1] == 'MM yy') {
				$answer = '01 '.$answer;
			} else if ($attributes[1] == 'yy') {
				$answer = '01 January '.$answer;
			}
			$provided = strtotime($answer);
				
			// return the difference
			return $provided - $base;
		}
		else
			return $data['Public'][$field_name];
	}
	
	function serialiseAnswer($data, $attributes)
	{
		return $data['value'];
	}
	
	function deserialiseAnswer($data, $attributes) {
		$answer = array();
		$answer['value'] = $data;
		return $answer;
	}
	
	function validateAnswer($data, $attributes, &$error) {
		if (isset($attributes[6]) && $attributes[6] && strlen($attributes[6]) > 0 && $data['value'] == '')
		{
			$error = "Please enter a value";
			return false;
		}
		return true;
	}
	
	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{
		$field_name = $attributes['id'].'_answer';
		
		echo $attributes[0]."<br/><br/>";
		
		$startDate = "";
		$endDate = "";
		if (strtotime($attributes[3]))
		{
			$times = split('-', $attributes[3]);
			if (3 == count($times))
				$startDate = "\nminDate: new Date(".$times[0].",".($times[1]-1).",".$times[2]."),";
		}
		if (strtotime($attributes[4]))
		{
			$times = split('-', $attributes[4]);
			if (3 == count($times))
				$endDate = "\nmaxDate: new Date(".$times[0].",".($times[1]-1).",".$times[2]."),";
				$endDate = $endDate."\ndefaultDate: new Date(".$times[0].",".($times[1]-1).",".$times[2]."),";
		}
		
			
		echo "<script type='text/javascript'>
			$(document).ready(function() {
			    $('#Public".$attributes['id']."Answer').datepicker( {
			        changeMonth: true,
			        yearRange: 'c-100:c+100',
			        changeYear: true,".$startDate.$endDate."
			        showButtonPanel: true,";

		if ($attributes[1] == 'MM yy')
		{
			echo "   onClose: function(dateText, inst) { 
            			var month = $('#ui-datepicker-div .ui-datepicker-month :selected').val();
            			var year = $('#ui-datepicker-div .ui-datepicker-year :selected').val();
            			$(this).datepicker('setDate', new Date(year, month, 1));
       		 		},	";
       	}
       	else if ($attributes[1] == 'yy')
       	{
       		echo "   onClose: function(dateText, inst) {
       		            			var year = $('#ui-datepicker-div .ui-datepicker-year :selected').val();
       		            			$(this).datepicker('setDate', new Date(year, 1, 1));
       		       		 		},	";       		
       	}
       	
       	$dateFormat = $attributes[1];
       	if (empty($dateFormat)) {
       		$dateFormat = 'dd MM yy';
       	}
       	echo 	"  dateFormat: '".$dateFormat."'
			    });
			});
			</script>";
		
		if ($attributes[1] == 'MM yy')
		{
			echo "<style type='text/css'>
			.ui-datepicker-calendar {
			    display: none;
			    }
			</style>";
		}
		else if ($attributes[1] == 'yy')
		{
			echo "<style type='text/css'>
			.ui-datepicker-calendar {
			    display: none;
			    }
			.ui-datepicker-month {
				display: none;
				}
			</style>";			
		}
		
		if ($previousAnswer)
		{
			$oldAnswer = $previousAnswer['SurveyResultAnswer']['answer'];
			
			if ($attributes[2] == 'difference')
			{
				$base = time();
				if ($attributes[5]) {
					$base = strtotime($attributes[5]);
				}
				
				$dateFormat = 'd F Y';
				if ($attributes[1] == 'MM yy') {
					$dateFormat = 'F Y';
				} else if ($attributes[1] == 'yy') {
					$dateFormat = 'Y';
				}
				
				$oldAnswer = date($dateFormat, ($previousAnswer['SurveyResultAnswer']['answer'] + $base));
			}
			
			// make a complete date
			if ($attributes[1] == 'MM yy') {
				$oldAnswer = '01 '.$oldAnswer;
			} else if ($attributes[1] == 'yy') {
				$oldAnswer = '01 January '.$oldAnswer;
			}
			
			// use Javascript to set previous answer
			$oldDate = date('Y-m-d', strtotime($oldAnswer));
			$times = split('-', $oldDate);
			if (3 == count($times)) {
				$year = $times[0];
				$month = $times[1];
				$day = $times[2];
				
				if ($attributes[1] == 'MM yy') {
					$day = '1';
				} else if ($attributes[1] == 'yy') {
					$month = '1';
					$day = '1';
				}
				// Javascript handles months from 0
				$month = $month - 1;
				
				echo "<script type='text/javascript'>$(document).ready(function() {
				$('#Public".$attributes['id']."Answer').datepicker('setDate', new Date(".$year.", ".$month.", ".$day."));
				});</script>";
			}
		}
		
		echo $form->text($field_name, array('readonly' => 'true', 'class' => 'datepicker'));
	}

	
}

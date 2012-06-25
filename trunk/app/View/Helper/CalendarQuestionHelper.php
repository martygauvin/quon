<?php
App::uses('AppHelper', 'View/Helper');

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
								  			 'help' => 'If "difference" is selected as the answer type then what date do we subtract from, in d/m/y format or leave empty for date of survey')
	);
	
	function serialiseAnswer($data, $attributes)
	{
		if ($attributes[2] == 'difference')
		{
			$base = strtotime($attributes[5]);
			$provided = strtotime($data['Public']['answer']);
			
			return date_diff($base, $provided);
		}
		else
			return $data['Public']['answer'];
	}
	
	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{
		echo "Question: ".$attributes[0]."<br/><br/>";
		
		$startDate = "";
		$endDate = "";
		if (strtotime($attributes[3]))
		{
			$times = split('-', $attributes[3]);
			if (3 == count($times))
				$startDate = "\nminDate: new Date(".$times[0].",".$times[1].",".$times[2]."),";
		}
		if (strtotime($attributes[4]))
		{
			$times = split('-', $attributes[4]);
			if (3 == count($times))
				$endDate = "\nmaxDate: new Date(".$times[0].",".$times[1].",".$times[2]."),";
		}
		
			
		echo "<script type='text/javascript'>
			$(function() {
			    $('.datepicker').datepicker( {
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
       	echo 	"  dateFormat: '".$attributes[1]."'
			    });
			});
			</script>";
		
		if ($attributes[1] == 'MM yy')
		{
			echo "<style>
			.ui-datepicker-calendar {
			    display: none;
			    }
			</style>";
		}
		else if ($attributes[1] == 'yy')
		{
			echo "<style>
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
			if ($attributes[2] == 'difference')
			{
				$base = strtotime($attributes[5]);
				$provided = date_add($base, $previousAnswer['SurveyResultAnswer']['answer']);
				
				echo $form->text('answer', array('value' => $provided, 'class' => 'datepicker'));
			}
			else
			{
				echo $form->text('answer', array('value' => $previousAnswer['SurveyResultAnswer']['answer'], 'class' => 'datepicker'));
		
			}
		}
		else
			echo $form->text('answer', array('class' => 'datepicker'));
				
		
	}

	
}
<?php
App::uses('AppHelper', 'View/Helper');

class CalendarQuestionHelper extends QuestionHelper  {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question'),
    							  1 => array('name' => 'Input Type',
    							  			 'help' => 'What fields do we display: \"dd MM yy\", \"MM yy\" or \"yy\"'),
    							  2 => array('name' => 'Answer Type',
    							  			 'help' => 'Enter \"value\" or \"difference\" depending on the type of answer you want to store'),
    							  3 => array('name' => 'Start Date',
    							   			 'help' => 'Start date in the format selected for the input type'),
								  4 => array('name' => 'End Date',
								  		     'help' => 'End date in the format selected for the input type'),
								  5 => array('name' => 'Differential date',
								  			 'help' => 'If \"difference\" is selected as the answer type then what date do we subtract from, in d/m/y format or leave empty for date of survey')
	);
	
	function serialiseAnswer($data, $attributes)
	{
		if ($attributes[2] == 'difference')
		{
			// TODO: Implement the "difference" logic
			// Use attribute 5 to determine where you are subtracting from
			return $data['Public']['answer'];
		}
		else
			return $data['Public']['answer'];
	}
	
	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{
		echo "Question: ".$attributes[0]."<br/><br/>";
		
		$startDate = "";
		$endDate = "";
		if ($attributes[3])
			$startDate = "\nminDate: '".$attributes[3]."',";
		if ($attributes[4])
			$endDate = "\nmaxDate: '".$attributes[4]."',";
		
			
		echo "<script type='text/javascript'>
			$(function() {
			    $('.datepicker').datepicker( {
			        changeMonth: true,
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
			echo $form->text('answer', array('value' => $previousAnswer['SurveyResultAnswer']['answer'], 'class' => 'datepicker'));
		else
			echo $form->text('answer', array('class' => 'datepicker'));
				
		
	}

	
}
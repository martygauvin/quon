<?php
App::uses('AppHelper', 'View/Helper');

class ButtonOptionQuestionHelper extends QuestionHelper {	
    
	protected $attributes = array(0 => array('name' => 'Question Text',
											 'help' => 'Text to display when asking the user this question'),
    							  1 => array('name' => 'Options',
											 'help' => 'List of possible options, each seperate by a |. e.g. Yes|No|Maybe'),
								  2 => array('name' => 'Include "None of the above" as an option',
								  		     'help' => 'Enter "yes" if you wish to include an extra option for "none of the above"')
		);
		
	function renderQuestion($form, $attributes, &$show_next)
	{		
		echo "<script type='text/javascript'>
			function answerButton(answerStr)
			{
				document.getElementById('PublicAnswer').value = answerStr;
				document.getElementById('PublicDirection').value = 'next';
				return true;
			}
			</script>
			";
		
		echo "Question: ".$attributes[0]."<br/><br/>";
	
		$options = array();
		$questionOptions = split("\|", $attributes[1]);
		foreach ($questionOptions as $questionOption)
		{
			echo $form->input($questionOption, array('type' => 'submit', 'label' => '', 'onClick' => 'javascript:return answerButton("'.$questionOption.'");'));
			echo "<br/><br/>";
		}
		
		echo $form->hidden('answer');
	
		if ($attributes[2] == 'yes')
		{
			echo $form->input('None of the above', array('type' => 'submit', 'label' => '', 'onClick' => 'javascript:return answerButton("None of the Above");'));
		}
		
		$show_next = false;
	
	}
	
	function serialiseAnswer($data, $attributes)
	{
		return $data['Public']['answer'];
	}
}
?>
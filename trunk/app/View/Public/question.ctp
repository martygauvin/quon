<?php 
	$this->Branding->applyBranding($surveyAttributes);
?>

<div class="surveyResultAnswer form">

<script type="text/javascript">
function questionSubmit($direction)
{
	document.getElementById('PublicDirection').value = $direction;
        document.getElementById('PublicQuestionForm').submit();
	return true;
}

</script>

<?php echo $this->Form->create('Public', array('url' => array('controller' => 'public', 'action' => 'answer')));?>
	<fieldset>
	<?php
		echo $this->Form->hidden('direction');
		echo $this->Form->hidden('survey_result_id', array('value' => $surveyResultID));
		echo $this->Form->hidden('survey_instance_object_id', array('value' => $surveyInstanceObject['SurveyInstanceObject']['id']));
		
		$show_next = true;
		
		if (isset($meta))
		{
			$cnt = 0;
			
			$answers = split("!!", $surveyResultAnswer['SurveyResultAnswer']['answer']);
						
			foreach ($surveyObject as $metaObject)
			{
				if ($metaObject)
				{
					$questionHelper = $this->Question->getHelper($metaObject['SurveyObject']['type']);
					
					// TODO: Cleanup required
					$metaPreviousAnswer = array();
					$metaPreviousAnswer['SurveyResultAnswer'] = array();
					
					if (count($answers) > $cnt)
					{
						$metaPreviousAnswer['SurveyResultAnswer']['answer'] = $answers[$cnt];
					}
					else
					{
						$metaPreviousAnswer['SurveyResultAnswer']['answer'] = "";
					}
						
					
					$questionHelper->render($this->Form, $surveyObjectAttributes[$cnt], $metaPreviousAnswer, $show_next);
					
					echo "<br/><br/><hr/><br/>";
				}
				
				$cnt++;
			}
		}
		else
		{
			$questionHelper = $this->Question->getHelper($surveyObject['SurveyObject']['type']);
			$questionHelper->render($this->Form, $surveyObjectAttributes, $surveyResultAnswer, $show_next);
		}
	?>
	</fieldset>
<?php 
	echo $this->Form->button('Back', array('class' => 'buttonLeft', 'onclick' => 'return questionSubmit(\'back\');'));	
	
	if ($show_next)
		echo $this->Form->button('Next', array('class' => 'buttonRight', 'onclick' => 'return questionSubmit(\'next\');'));
	echo $this->Form->end();
?>
</div>

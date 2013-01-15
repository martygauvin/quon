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
		$questionHelper = $this->Question->getHelper($surveyObject['SurveyObject']['type']);

		echo $this->Form->hidden('direction');
		echo $this->Form->hidden('survey_result_id', array('value' => $surveyResultID));
		echo $this->Form->hidden('survey_instance_object_id', array('value' => $surveyInstanceObject['SurveyInstanceObject']['id']));
		
		$show_next = true;
		
		$questionHelper->render($this->Form, $surveyObjectAttributes, $surveyResultAnswer, $show_next);
	?>
	</fieldset>
<?php 
	echo $this->Form->button('Back', array('class' => 'buttonLeft', 'onClick' => 'javascript:return questionSubmit(\'back\');'));	
	
	if ($show_next)
		echo $this->Form->button('Next', array('class' => 'buttonRight', 'onClick' => 'javascript:return questionSubmit(\'next\');'));
	echo $this->Form->end();
?>
</div>

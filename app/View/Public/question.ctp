<div class="surveyResultAnswer form">

<script type="text/javascript">
function questionSubmit($direction)
{
	document.getElementById('PublicDirection').value = $direction;
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
		
		$questionHelper->render($this->Form, $surveyObjectAttributes, $show_next);
	?>
	</fieldset>
<?php 
	echo $this->Form->submit('Back', array('class' => 'buttonLeft', 'onClick' => 'javascript:return questionSubmit(\'back\');'));	
	
	if ($show_next)
		echo $this->Form->submit('Next', array('class' => 'buttonRight', 'onClick' => 'javascript:return questionSubmit(\'next\');'));
	echo $this->Form->end();
?>
</div>

<div class="surveyResultAnser form">

<script language="javascript">
function questionSubmit($direction)
{
	document.getElementById('PublicDirection').value = $direction;
	return true;
}

</script>

<?php echo $this->Form->create('Public', array('url' => array('controller' => 'public', 'action' => 'answer')));?>
	<fieldset>
		<legend>Question <?php echo $surveyInstanceObject['SurveyInstanceObject']['order'];?></legend>
	<?php
		$questionHelper = $this->Question->getHelper($surveyObject['SurveyObject']['type']);

		echo $this->Form->hidden('direction');
		echo $this->Form->hidden('survey_result_id', array('value' => $surveyResultID));
		echo $this->Form->hidden('survey_instance_object_id', array('value' => $surveyInstanceObject['SurveyInstanceObject']['id']));
		
		$questionHelper->render($this->Form, $surveyObjectAttributes);
	?>
	</fieldset>
<?php 
	if ($hasBack)
	{
		echo $this->Form->submit('Back', array('onClick' => 'javascript:return questionSubmit(\'back\');'));		
	}
	
	if ($hasNext)
	{
		echo $this->Form->submit('Next', array('onClick' => 'javascript:return questionSubmit(\'next\');'));
	}
?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php 
			if (!$survey['Survey']['type'] == Survey::type_anonymous)
			{
				echo $this->Html->link(__('Logout'), array('controller' => 'public', 'action' => 'logout'));
			} 
		?> </li>
	</ul>
</div>

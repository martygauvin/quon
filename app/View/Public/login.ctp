<div id="logo">
	<?php 
		if (array_key_exists(SurveyAttribute::attribute_logo, $surveyAttributes))
		{
			echo "<img src='".$this->Html->url("../".$surveyAttributes[SurveyAttribute::attribute_logo], true)."'/><br/><br/>";
		}
	
	?>
</div>
<div class="partitipants form">
<?php echo $this->Form->create('Public', array('url' => array('controller' => 'public', 'action' => 'login', $survey['Survey']['short_name'])));?>
	<fieldset>
		<legend>Survey: <?php echo $survey['Survey']['name'];?></legend>

	<?php
		echo $this->Form->input('username');
		
		if ($survey['Survey']['type'] == Survey::type_authenticated)
		{
			echo $this->Form->input('password');
		}
	?>
	</fieldset>
<?php echo $this->Form->end(__('Login'));?>
</div>


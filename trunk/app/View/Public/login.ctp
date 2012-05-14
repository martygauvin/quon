<?php 
	$this->Branding->applyBranding($surveyAttributes);
?>

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


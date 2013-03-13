<div class="surveys form">
	<?php echo $this->Form->create('SurveyMetadata');?>
	<fieldset>
		<legend><?php echo __('Edit Survey Metadata'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('description');
		echo $this->Form->input('keywords');
		echo $this->Form->input('date_from', array('empty' => true, 'minYear' => date('Y') - 100, 'maxYear' => date('Y') + 10));
		echo $this->Form->input('date_to', array('empty' => true, 'minYear' => date('Y') - 100, 'maxYear' => date('Y') + 10));
		echo $this->Form->input('Location', array('label' => 'Locations'));
		echo $this->Form->input('fields_of_research');
		echo $this->Form->input('socio-economic_objective');
		echo $this->Form->input('retention_period');
		echo $this->Form->input('access_rights');
		echo $this->Form->input('User', array('label' => 'Users'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>

</div>
<div class="actions">
	<h3>

	<?php echo __('Actions'); ?></h3>
	<ul>
		<?php if ($publishSupported) { ?>
			<li><?php echo $this->Html->link(__('Publish to ReDBox'), array('controller' => 'surveys', 'action' => 'publish', $survey['Survey']['id'])); ?></li>
		<?php } ?>
	</ul>
	<br /><br />
	<ul>
		<li><?php echo $this->Html->link(__('Return to Survey'), array('controller' => 'surveys', 'action' => 'edit', $survey['Survey']['id'])); ?> </li>
	</ul>
</div>

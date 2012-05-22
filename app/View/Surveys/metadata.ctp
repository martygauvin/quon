<div class="surveys form">
	<?php echo $this->Form->create('SurveyMetadata');?>
	<fieldset>
		<legend><?php echo __('Edit Survey Metadata'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('description');
		echo $this->Form->input('access_rights');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>

</div>
<div class="actions">
	<h3>

	<?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Export Metadata'), array('controller' => 'surveys', 'action' => 'export', $survey['Survey']['id'])); ?></li>
		<?php if ($publishSupported) { ?>
			<li><?php echo $this->Html->link(__('Publish to ReDBox'), array('controller' => 'surveys', 'action' => 'publish', $survey['Survey']['id'])); ?></li>
		<?php } ?>
	</ul>
	<br /><br />
	<ul>
		<li><?php echo $this->Html->link(__('Return to Survey'), array('controller' => 'surveys', 'action' => 'edit', $survey['Survey']['id'])); ?> </li>
	</ul>
</div>

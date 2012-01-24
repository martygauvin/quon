<div class="brandings form">
<?php echo $this->Form->create('Branding');?>
	<fieldset>
		<legend><?php echo __('Edit Branding'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('css');
		echo $this->Form->input('survey_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Branding.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Branding.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Brandings'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Surveys'), array('controller' => 'surveys', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey'), array('controller' => 'surveys', 'action' => 'add')); ?> </li>
	</ul>
</div>

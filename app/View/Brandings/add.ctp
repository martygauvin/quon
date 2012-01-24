<div class="brandings form">
<?php echo $this->Form->create('Branding');?>
	<fieldset>
		<legend><?php echo __('Add Branding'); ?></legend>
	<?php
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

		<li><?php echo $this->Html->link(__('List Brandings'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Surveys'), array('controller' => 'surveys', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey'), array('controller' => 'surveys', 'action' => 'add')); ?> </li>
	</ul>
</div>

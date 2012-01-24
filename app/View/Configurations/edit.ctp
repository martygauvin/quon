<div class="configurations form">
<?php echo $this->Form->create('Configuration');?>
	<fieldset>
		<legend><?php echo __('Edit Configuration'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('value');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Configuration.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Configuration.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Configurations'), array('action' => 'index'));?></li>
	</ul>
</div>

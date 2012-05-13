<div class="groups form">
<?php echo $this->Form->create('Group');?>
	<fieldset>
		<legend><?php echo __('Edit Group'); ?></legend>
	<?php
		echo $this->Form->input('name');
		// TODO: Add lookup for external identifier
		echo $this->Form->input('external_identifier', array('readonly' => true));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
	</ul>
</div>

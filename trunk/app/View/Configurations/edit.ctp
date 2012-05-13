<div class="configurations form">
<?php echo $this->Form->create('Configuration');?>
	<fieldset>
		<legend><?php echo __('Edit Configuration'); ?></legend>
	<?php
		echo $this->Form->input('id');
		// TODO: Fix this so it cannot be edited (even when user changes readonly attribute in page)
		echo $this->Form->input('name', array('readonly' => true));
		echo $this->Form->input('value');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Return to Configurations'), array('action' => 'index'));?></li>
	</ul>
</div>

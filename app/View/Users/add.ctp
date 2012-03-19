<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('type', array(
		    'options' => array(User::type_researcher => 'Researcher', User::type_admin => 'Administrator')
		));
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('given_name');
		echo $this->Form->input('surname');
		echo $this->Form->input('email');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('controller' => 'users', 'action' => 'index')); ?> </li>
	</ul>
</div>

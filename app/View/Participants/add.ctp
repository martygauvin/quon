<div class="participants form">
<?php echo $this->Form->create('Participant');?>
	<fieldset>
		<legend><?php echo __('Add Participant'); ?></legend>
	<?php
		echo $this->Form->input('survey_id');
		echo $this->Form->input('given_name');
		echo $this->Form->input('surname');
		echo $this->Form->input('dob',
			array('minYear' => date('Y') - 120, 'maxYear' => date('Y')));
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('email');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('controller' => 'participants', 'action' => 'index')); ?> </li>
	</ul>
</div>
<div class="surveys form">
<?php echo $this->Form->create('Survey');?>
	<fieldset>
		<legend><?php echo __('Edit Survey'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('group_id');
		echo $this->Form->input('name');
		echo $this->Form->input('short_name');
		echo $this->Form->input('type', array(
		    'options' => array(Survey::type_anonymous => 'Anonymous', 
		    				   Survey::type_identified => 'Identified',
		    				   Survey::type_authenticated => 'Authenticated',
		    				   Survey::type_autoidentified => 'Auto-Identified')
		));		echo $this->Form->input('multiple_run');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Survey Objects'), array('controller' => 'survey_objects', 'action' => 'index', $this->request->data['Survey']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Survey Design'), array('controller' => 'survey_instances', 'action' => 'index', $this->request->data['Survey']['id']));?> </li>
	</ul>
	<br><br>
	<ul>
		<li><?php echo $this->Html->link(__('Return to Surveys'), array('controller' => 'surveys', 'action' => 'index')); ?> </li>
	</ul>
</div>

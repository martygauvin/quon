<div class="surveyInstanceObjects form">
<?php echo $this->Form->create('SurveyInstanceObject');?>
	<fieldset>
		<legend><?php echo __('Add Survey Instance Object'); ?></legend>
	<?php
		echo $this->Form->input('survey_instance_id');
		echo $this->Form->input('survey_object_id');
		echo $this->Form->input('order');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Survey Instance Objects'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Survey Instances'), array('controller' => 'survey_instances', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Instance'), array('controller' => 'survey_instances', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Objects'), array('controller' => 'survey_objects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Object'), array('controller' => 'survey_objects', 'action' => 'add')); ?> </li>
	</ul>
</div>

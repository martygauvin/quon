<div class="surveyInstances form">
<?php echo $this->Form->create('SurveyInstance');?>
	<fieldset>
		<legend><?php echo __('Edit Survey Instance'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('survey_id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('SurveyInstance.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('SurveyInstance.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Survey Instances'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Surveys'), array('controller' => 'surveys', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey'), array('controller' => 'surveys', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Instance Objects'), array('controller' => 'survey_instance_objects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Instance Object'), array('controller' => 'survey_instance_objects', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Results'), array('controller' => 'survey_results', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Result'), array('controller' => 'survey_results', 'action' => 'add')); ?> </li>
	</ul>
</div>

<div class="surveyResults form">
<?php echo $this->Form->create('SurveyResult');?>
	<fieldset>
		<legend><?php echo __('Add Survey Result'); ?></legend>
	<?php
		echo $this->Form->input('survey_instance_id');
		echo $this->Form->input('date');
		echo $this->Form->input('participant_id');
		echo $this->Form->input('test');
		echo $this->Form->input('completed');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Survey Results'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Survey Instances'), array('controller' => 'survey_instances', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Instance'), array('controller' => 'survey_instances', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Participants'), array('controller' => 'participants', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Participant'), array('controller' => 'participants', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Result Answers'), array('controller' => 'survey_result_answers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Result Answer'), array('controller' => 'survey_result_answers', 'action' => 'add')); ?> </li>
	</ul>
</div>

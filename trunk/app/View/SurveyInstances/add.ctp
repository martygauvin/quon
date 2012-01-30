<div class="surveyInstances form">
<?php echo $this->Form->create('SurveyInstance');?>
	<fieldset>
		<legend><?php echo __('Add Survey Instance'); ?></legend>
	<?php
		echo $this->Form->hidden('survey_id', array('value' => $survey_id));
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('controller' => 'survey_instances', 'action' => 'index', $survey_id)); ?> </li>
	</ul>
</div>

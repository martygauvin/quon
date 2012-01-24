<div class="surveyObjectAttributes form">
<?php echo $this->Form->create('SurveyObjectAttribute');?>
	<fieldset>
		<legend><?php echo __('Add Survey Object Attribute'); ?></legend>
	<?php
		echo $this->Form->input('survey_object_id');
		echo $this->Form->input('name');
		echo $this->Form->input('value');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Survey Object Attributes'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Survey Objects'), array('controller' => 'survey_objects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Object'), array('controller' => 'survey_objects', 'action' => 'add')); ?> </li>
	</ul>
</div>

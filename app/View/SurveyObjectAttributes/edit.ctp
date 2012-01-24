<div class="surveyObjectAttributes form">
<?php echo $this->Form->create('SurveyObjectAttribute');?>
	<fieldset>
		<legend><?php echo __('Edit Survey Object Attribute'); ?></legend>
	<?php
		echo $this->Form->input('id');
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
		<li><?php echo $this->Html->link(__('Cancel'), array('controller' => 'survey_objects', 'action' => 'index')); ?> </li>
	</ul>
</div>

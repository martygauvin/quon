<div class="surveyObjects form">
<?php echo $this->Form->create('SurveyObject');?>
	<fieldset>
		<legend><?php echo __('Add Survey Object'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('type', array(
		    'options' => $this->Question->types()
		));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('controller' => 'survey_objects', 'action' => 'index', $survey_id)); ?> </li>
	</ul>
</div>

<div class="surveyObjectAttributes form">
<?php echo $this->Form->create('SurveyObjectAttribute');?>
	<fieldset>
		<legend><?php echo __('Edit Survey Object Attribute'); ?></legend>
		Survey Object: <?php echo $surveyObject['SurveyObject']['name'];?><br/><br/>
		<?php 
			$questionHelper = $this->Question->getHelper($surveyObject['SurveyObject']['type']);
		?>
		Attribute Name: <?php echo $questionHelper->getAttributeName($this->request->data['SurveyObjectAttribute']['name']);?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('survey_object_id');
		
		echo $this->Form->input('value');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('controller' => 'survey_object_attributes', 'action' => 'index', $surveyObject['SurveyObject']['id'])); ?> </li>
	</ul>
</div>

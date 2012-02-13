<div class="surveyResultAnser form">
<?php echo $this->Form->create('Public', array('url' => array('controller' => 'public', 'action' => 'answer')));?>
	<fieldset>
		<legend>Question <?php echo $surveyInstanceObject['SurveyInstanceObject']['order'];?></legend>
	<?php
		$questionHelper = $this->Question->getHelper($surveyObject['SurveyObject']['type']);

		echo $this->Form->hidden('survey_result_id', array('value' => $surveyResultID));
		echo $this->Form->hidden('survey_object_instance_id', array('value' => $surveyInstanceObject['SurveyInstanceObject']['id']));
		
		// TODO: Hook into helper framework to display question
		$questionHelper->render($this->Form, $surveyObjectAttributes);
	?>
	</fieldset>
<?php echo $this->Form->end(__('Next'));?>
</div>


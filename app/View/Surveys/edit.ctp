<script type="text/javascript">
	function toggleMultipleRun()
	{
		var mySurveyType = document.getElementById('SurveyType');
		var myMultipleRun = document.getElementById('SurveyMultipleRun');

		if (mySurveyType.value == 0)
		{
			myMultipleRun.disabled = true;
			myMultipleRun.checked = false;
		}
		else
		{
			myMultipleRun.disabled = false;
		}
			
	}
</script>
<div class="surveys form">
<?php echo $this->Form->create('Survey', array('type' => 'file'));?>
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
		    				   Survey::type_autoidentified => 'Auto-Identified'),
		    'onClick' => 'javascript:toggleMultipleRun();'
		));		 
		
		if ($this->request->data['Survey']['type'] == Survey::type_anonymous)
			echo $this->Form->input('multiple_run', array('disabled' => 'true'));
		else
			echo $this->Form->input('multiple_run');
	
		echo $this->Form->input('Survey.logo', array('type' => 'file', 'label' => 'Logo Image'));
		
		if (array_key_exists(SurveyAttribute::attribute_logo, $surveyAttributes))
		{
			echo "<div class='input file'><label>Existing Logo:</label> <img src='".$this->Html->url("../".$surveyAttributes[SurveyAttribute::attribute_logo], true)."'/></div><br/><br/>";
		}
	
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Survey Objects'), array('controller' => 'survey_objects', 'action' => 'index', $this->request->data['Survey']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Survey Design'), array('controller' => 'survey_instances', 'action' => 'index', $this->request->data['Survey']['id']));?> </li>
		<li><?php echo $this->Html->link(__('Survey Metadata'), array('controller' => 'surveys', 'action' => 'metadata', $this->request->data['Survey']['id']));?> </li>
	</ul>
	<br /><br />
	<ul>
		<li><?php echo $this->Html->link(__('Return to Surveys'), array('controller' => 'surveys', 'action' => 'index')); ?> </li>
	</ul>
</div>

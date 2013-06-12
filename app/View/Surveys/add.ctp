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
<?php echo $this->Form->create('Survey');?>
	<fieldset>
		<legend><?php echo __('Add Survey'); ?></legend>
	<?php
		echo $this->Form->input('group_id');
		echo $this->Form->input('name');
		echo $this->Form->input('short_name');
		echo $this->Form->input('type', array(
		    'options' => array(Survey::type_anonymous => 'Anonymous', 
		    				   Survey::type_identified => 'Identified',
		    				   Survey::type_authenticated => 'Authenticated',
		    				   Survey::type_autoidentified => 'Auto-Identified'),
			'onclick' => 'javascript:toggleMultipleRun();'
		));
		echo $this->Form->input('multiple_run', array('disabled' => 'true'));
		echo $this->Form->input('locked_edit');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('controller' => 'surveys', 'action' => 'index')); ?> </li>
	</ul>
</div>

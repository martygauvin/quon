<div class="surveyObjectAttributes form">
<?php echo $this->Form->create('SurveyObjectAttribute');?>
	<fieldset>
		<legend><?php echo __('Edit Survey Object Attribute'); ?></legend>
		Survey Object: <?php echo $surveyObject['SurveyObject']['name'];?><br/><br/>
		<?php 
			$questionHelper = $this->Question->getHelper($surveyObject['SurveyObject']['type']);
			$attribute = $questionHelper->getAttribute($this->request->data['SurveyObjectAttribute']['name']);
		?>
		Attribute Name: <?php echo $attribute['name'];?><br/>
		Description: <?php echo $attribute['help'];?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('survey_object_id');
		
		if (array_key_exists('type', $attribute) && $attribute['type'] == "html")
		{
			echo $this->Html->script('tiny_mce/tiny_mce.js');
			echo "<script type='text/javascript'>
								tinyMCE.init({
			        				mode : 'textareas'
								});
							  </script>";
				
			echo $this->Form->input('value');	
		}
		else
		{
			echo $this->Form->input('value');
		}
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

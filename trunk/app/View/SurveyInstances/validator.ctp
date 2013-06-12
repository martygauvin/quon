<div class="surveyInstances form">
	<fieldset>
		<legend><?php echo __('Validate Survey Instance'); ?></legend>
		Survey Name: <?php echo $surveyInstance['Survey']['name'];?><br/>
		Instance Name: <?php echo $surveyInstance['SurveyInstance']['name'];?>

	<table cellpadding="0" cellspacing="0">
	<tr>
		<th>Survey Object</th>
		<th>Errors</th>
	</tr>
	<?php 
		$cnt = 0;
		foreach ($validation as $result)
		{
			if (count($result['errors'] != 0))
			{ 
				foreach ($result['errors'] as $error)
				{
	?>
		<tr>
			<td><?php echo $result['object']['SurveyObject']['name'];?></td>
			<td><?php echo $error;?></td>
		</tr>
	<?php 
				}
			}
		}

		$cnt++;
	?>
	</table>
	</fieldset>

</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Return to Survey Instance'), array('controller' => 'survey_instances', 'action' => 'index', $surveyInstance['Survey']['id'])); ?> </li>
	</ul>
</div>

<div class="surveyInstances form">
	<fieldset>
		<legend><?php echo __('View Survey Instance'); ?></legend>
		Survey Name: <?php echo $surveyInstance['Survey']['name'];?><br/>
		Instance Name: <?php echo $surveyInstance['SurveyInstance']['name'];?>

	<table cellpadding="0" cellspacing="0">
	<tr>
		<th>Order</th>
		<th>Name</th>
	</tr>
	<?php 
		$cnt = 0;
		foreach ($surveyInstanceObjects as $surveyInstanceObject): 
			$cnt++;
	?>
		<tr>
			<td><?php echo $cnt;?></td>
			<td><?php echo $surveyInstanceObject['SurveyObject']['name'];?></td>
		</tr>
	<?php 
		endforeach;

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

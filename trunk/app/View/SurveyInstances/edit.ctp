<div class="surveyInstances form">
<?php echo $this->Form->create('SurveyInstance');?>
	<fieldset>
		<legend><?php echo __('Edit Survey Instance'); ?></legend>
		Survey Name: <?php echo $surveyInstance['Survey']['name'];?><br/>
		Instance Name: <?php echo $surveyInstance['SurveyInstance']['name'];?>
	<?php
		echo $this->Form->input('id');
	?>
	<table cellpadding="0" cellspacing="0">
	<tr>
		<th>Order</th>
		<th>Name</th>
		<th>Actions</th>
	</tr>
	<?php 
		$cnt = 0;
		foreach ($surveyInstanceObjects as $surveyInstanceObject): 
			$cnt++;
	?>
		<tr>
			<td><?php echo $cnt;?></td>
			<td><?php echo $surveyInstanceObject['SurveyObject']['name'];?></td>
			<?php echo $this->Form->hidden('SurveyInstanceObject.survey_object_id.'.$cnt, array('value' => $surveyInstanceObject['SurveyObject']['id']));?>
			<td>
				<?php echo $this->Form->postLink(__('Remove'), array('controller' => 'survey_instance_objects', 'action' => 'delete', $surveyInstanceObject['SurveyInstanceObject']['id']), null, __('Are you sure you want to remove this item ?')); ?>
				<?php echo $this->Html->link(__('Up'), array('controller' => 'survey_instance_objects', 'action' => 'move_up', $surveyInstanceObject['SurveyInstanceObject']['id'])); ?>
				<?php echo $this->Html->link(__('Down'), array('controller' => 'survey_instance_objects', 'action' => 'move_down', $surveyInstanceObject['SurveyInstanceObject']['id'])); ?>
			</td>
		</tr>
	<?php 
		endforeach;

		$cnt++;
	?>
		<tr>
			<td><?php echo $surveyInstanceObjectMax[0]['morder'] + 1;?></td>
			<td><?php echo $this->Form->select('SurveyInstanceObject.survey_object_id.'.$cnt, $surveyObjects);?>
			<td></td>
		</tr>
	</table>
	</fieldset>

<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Return to Survey Instance'), array('controller' => 'survey_instances', 'action' => 'index', $surveyInstance['Survey']['id'])); ?> </li>
	</ul>
</div>
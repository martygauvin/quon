<div class="surveyResults index">
	<h2><?php echo __('Survey Results');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('survey_instance_id');?></th>
			<th><?php echo $this->Paginator->sort('date');?></th>
			<th><?php echo $this->Paginator->sort('participant_id');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($surveyResults as $surveyResult): ?>
	<tr>		
		<td><?php echo h($surveyResult['SurveyResult']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($surveyResult['SurveyInstance']['name'], array('controller' => 'survey_instances', 'action' => 'view', $surveyResult['SurveyInstance']['id'])); ?>
		</td>
		<td><?php echo h($surveyResult['SurveyResult']['date']); ?>&nbsp;</td>
		<td>
			<?php if (!$surveyResult['SurveyResult']['participant_id'])
					echo "Anonymous";
				  else if (!$surveyResult['Participant']['given_name'])
				  	echo "Deleted Participant";
				  else 
					echo $this->Html->link($surveyResult['Participant']['given_name']." ".$surveyResult['Participant']['surname'], array('controller' => 'participants', 'action' => 'view', $surveyResult['Participant']['id'])); 
			?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $surveyResult['SurveyResult']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Export'), array('controller' => 'surveyResults', 'action' => 'export', $surveyInstance['SurveyInstance']['id'])); ?></li>
	</ul>
	<ul>
		<li><?php echo $this->Html->link(__('Return to Survey'), array('controller' => 'surveyInstances', 'action' => 'index', $survey['Survey']['id'])); ?> </li>
	</ul>
</div>

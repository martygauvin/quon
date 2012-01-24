<div class="surveyResults index">
	<h2><?php echo __('Survey Results');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('survey_instance_id');?></th>
			<th><?php echo $this->Paginator->sort('date');?></th>
			<th><?php echo $this->Paginator->sort('participant_id');?></th>
			<th><?php echo $this->Paginator->sort('test');?></th>
			<th><?php echo $this->Paginator->sort('completed');?></th>
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
			<?php echo $this->Html->link($surveyResult['Participant']['username'], array('controller' => 'participants', 'action' => 'view', $surveyResult['Participant']['id'])); ?>
		</td>
		<td><?php echo h($surveyResult['SurveyResult']['test']); ?>&nbsp;</td>
		<td><?php echo h($surveyResult['SurveyResult']['completed']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $surveyResult['SurveyResult']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $surveyResult['SurveyResult']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $surveyResult['SurveyResult']['id']), null, __('Are you sure you want to delete # %s?', $surveyResult['SurveyResult']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Survey Result'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Survey Instances'), array('controller' => 'survey_instances', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Instance'), array('controller' => 'survey_instances', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Participants'), array('controller' => 'participants', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Participant'), array('controller' => 'participants', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Result Answers'), array('controller' => 'survey_result_answers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Result Answer'), array('controller' => 'survey_result_answers', 'action' => 'add')); ?> </li>
	</ul>
</div>

<div class="surveyInstances index">
	<h2><?php echo __('Survey Instances');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('survey_id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($surveyInstances as $surveyInstance): ?>
	<tr>
		<td><?php echo h($surveyInstance['SurveyInstance']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($surveyInstance['Survey']['name'], array('controller' => 'surveys', 'action' => 'view', $surveyInstance['Survey']['id'])); ?>
		</td>
		<td><?php echo h($surveyInstance['SurveyInstance']['name']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $surveyInstance['SurveyInstance']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $surveyInstance['SurveyInstance']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $surveyInstance['SurveyInstance']['id']), null, __('Are you sure you want to delete # %s?', $surveyInstance['SurveyInstance']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Survey Instance'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Surveys'), array('controller' => 'surveys', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey'), array('controller' => 'surveys', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Instance Objects'), array('controller' => 'survey_instance_objects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Instance Object'), array('controller' => 'survey_instance_objects', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Results'), array('controller' => 'survey_results', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Result'), array('controller' => 'survey_results', 'action' => 'add')); ?> </li>
	</ul>
</div>

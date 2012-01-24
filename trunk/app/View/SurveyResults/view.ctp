<div class="surveyResults view">
<h2><?php  echo __('Survey Result');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($surveyResult['SurveyResult']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Survey Instance'); ?></dt>
		<dd>
			<?php echo $this->Html->link($surveyResult['SurveyInstance']['name'], array('controller' => 'survey_instances', 'action' => 'view', $surveyResult['SurveyInstance']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date'); ?></dt>
		<dd>
			<?php echo h($surveyResult['SurveyResult']['date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Participant'); ?></dt>
		<dd>
			<?php echo $this->Html->link($surveyResult['Participant']['username'], array('controller' => 'participants', 'action' => 'view', $surveyResult['Participant']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Test'); ?></dt>
		<dd>
			<?php echo h($surveyResult['SurveyResult']['test']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Completed'); ?></dt>
		<dd>
			<?php echo h($surveyResult['SurveyResult']['completed']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Survey Result'), array('action' => 'edit', $surveyResult['SurveyResult']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Survey Result'), array('action' => 'delete', $surveyResult['SurveyResult']['id']), null, __('Are you sure you want to delete # %s?', $surveyResult['SurveyResult']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Results'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Result'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Instances'), array('controller' => 'survey_instances', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Instance'), array('controller' => 'survey_instances', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Participants'), array('controller' => 'participants', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Participant'), array('controller' => 'participants', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Result Answers'), array('controller' => 'survey_result_answers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Result Answer'), array('controller' => 'survey_result_answers', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Survey Result Answers');?></h3>
	<?php if (!empty($surveyResult['SurveyResultAnswer'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Survey Result Id'); ?></th>
		<th><?php echo __('Survey Object Instance Id'); ?></th>
		<th><?php echo __('Answer'); ?></th>
		<th><?php echo __('Time Spent'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($surveyResult['SurveyResultAnswer'] as $surveyResultAnswer): ?>
		<tr>
			<td><?php echo $surveyResultAnswer['id'];?></td>
			<td><?php echo $surveyResultAnswer['survey_result_id'];?></td>
			<td><?php echo $surveyResultAnswer['survey_object_instance_id'];?></td>
			<td><?php echo $surveyResultAnswer['answer'];?></td>
			<td><?php echo $surveyResultAnswer['time_spent'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'survey_result_answers', 'action' => 'view', $surveyResultAnswer['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'survey_result_answers', 'action' => 'edit', $surveyResultAnswer['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'survey_result_answers', 'action' => 'delete', $surveyResultAnswer['id']), null, __('Are you sure you want to delete # %s?', $surveyResultAnswer['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Survey Result Answer'), array('controller' => 'survey_result_answers', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>

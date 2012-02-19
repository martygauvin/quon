<div class="surveyResults view">
<h2><?php  echo __('Survey Result');?></h2>
	<dl>
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
			<?php echo $this->Html->link($surveyResult['Participant']['given_name']." ".$surveyResult['Participant']['surname'], array('controller' => 'participants', 'action' => 'view', $surveyResult['Participant']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Return to Results'), array('controller' => 'surveyResults', 'action' => 'index', $surveyResult['SurveyInstance']['id'])); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Survey Result Answers');?></h3>
	<?php if (!empty($surveyResult['SurveyResultAnswer'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Survey Object Instance Id'); ?></th>
		<th><?php echo __('Answer'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($surveyResult['SurveyResultAnswer'] as $surveyResultAnswer): ?>
		<tr>
			<?php print_r($surveyResultAnswer); ?>
			<td><?php echo $surveyResultAnswer['survey_object_instance_id'];?></td>
			<td><?php echo $surveyResultAnswer['answer'];?></td>
			<td><?php echo $surveyResultAnswer['time_spent'];?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<?php // TODO: Add EXPORT feature?>
			<li><?php echo $this->Html->link(__('New Survey Result Answer'), array('controller' => 'survey_result_answers', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>

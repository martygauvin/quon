<div class="surveyInstances view">
<h2><?php  echo __('Survey Instance');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($surveyInstance['SurveyInstance']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Survey'); ?></dt>
		<dd>
			<?php echo $this->Html->link($surveyInstance['Survey']['name'], array('controller' => 'surveys', 'action' => 'view', $surveyInstance['Survey']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($surveyInstance['SurveyInstance']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Survey Instance'), array('action' => 'edit', $surveyInstance['SurveyInstance']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Survey Instance'), array('action' => 'delete', $surveyInstance['SurveyInstance']['id']), null, __('Are you sure you want to delete # %s?', $surveyInstance['SurveyInstance']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Instances'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Instance'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Surveys'), array('controller' => 'surveys', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey'), array('controller' => 'surveys', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Instance Objects'), array('controller' => 'survey_instance_objects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Instance Object'), array('controller' => 'survey_instance_objects', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Results'), array('controller' => 'survey_results', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Result'), array('controller' => 'survey_results', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Survey Instance Objects');?></h3>
	<?php if (!empty($surveyInstance['SurveyInstanceObject'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Survey Instance Id'); ?></th>
		<th><?php echo __('Survey Object Id'); ?></th>
		<th><?php echo __('Order'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($surveyInstance['SurveyInstanceObject'] as $surveyInstanceObject): ?>
		<tr>
			<td><?php echo $surveyInstanceObject['id'];?></td>
			<td><?php echo $surveyInstanceObject['survey_instance_id'];?></td>
			<td><?php echo $surveyInstanceObject['survey_object_id'];?></td>
			<td><?php echo $surveyInstanceObject['order'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'survey_instance_objects', 'action' => 'view', $surveyInstanceObject['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'survey_instance_objects', 'action' => 'edit', $surveyInstanceObject['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'survey_instance_objects', 'action' => 'delete', $surveyInstanceObject['id']), null, __('Are you sure you want to delete # %s?', $surveyInstanceObject['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Survey Instance Object'), array('controller' => 'survey_instance_objects', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Survey Results');?></h3>
	<?php if (!empty($surveyInstance['SurveyResult'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Survey Instance Id'); ?></th>
		<th><?php echo __('Date'); ?></th>
		<th><?php echo __('Participant Id'); ?></th>
		<th><?php echo __('Test'); ?></th>
		<th><?php echo __('Completed'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($surveyInstance['SurveyResult'] as $surveyResult): ?>
		<tr>
			<td><?php echo $surveyResult['id'];?></td>
			<td><?php echo $surveyResult['survey_instance_id'];?></td>
			<td><?php echo $surveyResult['date'];?></td>
			<td><?php echo $surveyResult['participant_id'];?></td>
			<td><?php echo $surveyResult['test'];?></td>
			<td><?php echo $surveyResult['completed'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'survey_results', 'action' => 'view', $surveyResult['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'survey_results', 'action' => 'edit', $surveyResult['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'survey_results', 'action' => 'delete', $surveyResult['id']), null, __('Are you sure you want to delete # %s?', $surveyResult['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Survey Result'), array('controller' => 'survey_results', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>

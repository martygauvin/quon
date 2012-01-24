<div class="surveyObjects view">
<h2><?php  echo __('Survey Object');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($surveyObject['SurveyObject']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Survey'); ?></dt>
		<dd>
			<?php echo $this->Html->link($surveyObject['Survey']['name'], array('controller' => 'surveys', 'action' => 'view', $surveyObject['Survey']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($surveyObject['SurveyObject']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($surveyObject['SurveyObject']['type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Published'); ?></dt>
		<dd>
			<?php echo h($surveyObject['SurveyObject']['published']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Survey Object'), array('action' => 'edit', $surveyObject['SurveyObject']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Survey Object'), array('action' => 'delete', $surveyObject['SurveyObject']['id']), null, __('Are you sure you want to delete # %s?', $surveyObject['SurveyObject']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Objects'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Object'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Surveys'), array('controller' => 'surveys', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey'), array('controller' => 'surveys', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Instance Objects'), array('controller' => 'survey_instance_objects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Instance Object'), array('controller' => 'survey_instance_objects', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Object Attributes'), array('controller' => 'survey_object_attributes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Object Attribute'), array('controller' => 'survey_object_attributes', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Survey Instance Objects');?></h3>
	<?php if (!empty($surveyObject['SurveyInstanceObject'])):?>
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
		foreach ($surveyObject['SurveyInstanceObject'] as $surveyInstanceObject): ?>
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
	<h3><?php echo __('Related Survey Object Attributes');?></h3>
	<?php if (!empty($surveyObject['SurveyObjectAttribute'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Survey Object Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Value'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($surveyObject['SurveyObjectAttribute'] as $surveyObjectAttribute): ?>
		<tr>
			<td><?php echo $surveyObjectAttribute['id'];?></td>
			<td><?php echo $surveyObjectAttribute['survey_object_id'];?></td>
			<td><?php echo $surveyObjectAttribute['name'];?></td>
			<td><?php echo $surveyObjectAttribute['value'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'survey_object_attributes', 'action' => 'view', $surveyObjectAttribute['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'survey_object_attributes', 'action' => 'edit', $surveyObjectAttribute['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'survey_object_attributes', 'action' => 'delete', $surveyObjectAttribute['id']), null, __('Are you sure you want to delete # %s?', $surveyObjectAttribute['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Survey Object Attribute'), array('controller' => 'survey_object_attributes', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>

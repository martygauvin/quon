<div class="surveyInstanceObjects view">
<h2><?php  echo __('Survey Instance Object');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($surveyInstanceObject['SurveyInstanceObject']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Survey Instance'); ?></dt>
		<dd>
			<?php echo $this->Html->link($surveyInstanceObject['SurveyInstance']['name'], array('controller' => 'survey_instances', 'action' => 'view', $surveyInstanceObject['SurveyInstance']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Survey Object'); ?></dt>
		<dd>
			<?php echo $this->Html->link($surveyInstanceObject['SurveyObject']['name'], array('controller' => 'survey_objects', 'action' => 'view', $surveyInstanceObject['SurveyObject']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Order'); ?></dt>
		<dd>
			<?php echo h($surveyInstanceObject['SurveyInstanceObject']['order']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Survey Instance Object'), array('action' => 'edit', $surveyInstanceObject['SurveyInstanceObject']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Survey Instance Object'), array('action' => 'delete', $surveyInstanceObject['SurveyInstanceObject']['id']), null, __('Are you sure you want to delete # %s?', $surveyInstanceObject['SurveyInstanceObject']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Instance Objects'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Instance Object'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Instances'), array('controller' => 'survey_instances', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Instance'), array('controller' => 'survey_instances', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Survey Objects'), array('controller' => 'survey_objects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey Object'), array('controller' => 'survey_objects', 'action' => 'add')); ?> </li>
	</ul>
</div>

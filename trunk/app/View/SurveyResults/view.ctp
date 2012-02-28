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
		<th><?php echo __('Survey Object'); ?></th>
		<th><?php echo __('Answer'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($surveyResultAnswers as $surveyResultAnswer): ?>
		
		<tr>
			<td><?php echo $surveyResultAnswer['SurveyInstanceObject']['SurveyObject']['name'];?></td>
			<td><?php echo $surveyResultAnswer['SurveyResultAnswer']['answer'];?></td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

</div>

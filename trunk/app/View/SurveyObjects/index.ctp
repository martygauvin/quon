<div class="surveyObjects index">
	<h2><?php echo __('Survey Objects');?></h2>
	<h3>Survey: <?php echo $survey['Survey']['name']; ?></h3>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('type');?></th>
			<th><?php echo $this->Paginator->sort('published');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($surveyObjects as $surveyObject): ?>
	<tr>
		<td><?php echo h($surveyObject['SurveyObject']['name']); ?>&nbsp;</td>
		<td><?php echo h($this->Question->idToName($surveyObject['SurveyObject']['type'])); ?>&nbsp;</td>
		<td><?php if ($surveyObject['SurveyObject']['published']) echo h('Yes'); else echo h('No'); ?>&nbsp;</td>
		<td class="actions">
			<?php 
				echo $this->Html->link(__('Attributes'), array('controller' => 'survey_object_attributes', 'action' => 'index', $surveyObject['SurveyObject']['id']));
				echo $this->Html->link(__('Preview'), array('action' => 'preview', $surveyObject['SurveyObject']['id']), array('target' => '_blank'));
				if (!$surveyObject['SurveyObject']['published'])
				{
					echo $this->Html->link(__('Edit'), array('action' => 'edit', $surveyObject['SurveyObject']['id'])); 
					echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $surveyObject['SurveyObject']['id']), null, __('Are you sure you want to delete # %s?', $surveyObject['SurveyObject']['id'])); 
				}
				else
				{
					echo $this->Html->link(__('Duplicate'), array('action' => 'duplicate', $surveyObject['SurveyObject']['id']));
				}
			?>
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
		<li><?php echo $this->Html->link(__('New Survey Object'), array('action' => 'add', $survey['Survey']['id'], $this->Paginator->counter('{:page}'))); ?></li>
	</ul>
	<br/><br/>
	<ul>
		<li><?php echo $this->Html->link(__('Return to Survey'), array('controller' => 'surveys', 'action' => 'edit', $survey['Survey']['id'])); ?> </li>
	</ul>
</div>

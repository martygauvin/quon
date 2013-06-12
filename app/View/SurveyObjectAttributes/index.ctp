<div class="surveyObjectAttributes index">
	<h2><?php echo __('Survey Object Attributes');?></h2>
	<h3>Survey Object: <?php echo $surveyObject['SurveyObject']['name']?></h3>

<?php
	if ($surveyObject['SurveyObject']['published'] == 1 && $survey['Survey']['locked_edit'] == 1)
	{
?>
	<h2><font color="red">WARNING: You are editing a published object</font></h2>
<?php
	}
?>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('value');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	$questionHelper = $this->Question->getHelper($surveyObject['SurveyObject']['type']);
	
	foreach ($surveyObjectAttributes as $surveyObjectAttribute): 
		$attribute = $questionHelper->getAttribute($surveyObjectAttribute['SurveyObjectAttribute']['name']);	
	?>
	<tr>
		<td><?php 
			echo h($attribute['name']);
		?>&nbsp;</td>
		<td><?php echo h($surveyObjectAttribute['SurveyObjectAttribute']['value']); ?>&nbsp;</td>
		<td class="actions">
			<?php 
				if (!$surveyObject['SurveyObject']['published'] || $survey['Survey']['locked_edit'] == 1)
				{
					echo $this->Html->link(__('Edit'), array('action' => 'edit', $surveyObjectAttribute['SurveyObjectAttribute']['id']));
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
		<li><?php echo $this->Html->link(__('Preview Survey Object'), array('controller' => 'survey_objects', 'action' => 'preview', $surveyObject['SurveyObject']['id']), array('target' => '_blank')); ?></li>
	</ul>
	<br /><br />
	<ul>
		<li><?php echo $this->Html->link(__('Return to Survey Objects'), array('controller' => 'survey_objects', 'action' => 'index', $surveyObject['SurveyObject']['survey_id'])); ?> </li>
	</ul>
</div>

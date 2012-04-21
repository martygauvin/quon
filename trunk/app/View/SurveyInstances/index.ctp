<div class="surveyInstances index">
	<script type="text/javascript">
		$(function() {
			$( "#live_dialog" ).dialog( {autoOpen: false});
		});
	</script>

	<h2><?php echo __('Survey Instances');?></h2>
	Survey: <?php echo $survey['Survey']['name'];?><br/><br/>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th>Version</th>
			<th>State</th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($surveyInstances as $surveyInstance): ?>
	<tr>
		<td><?php echo h($surveyInstance['SurveyInstance']['name']); ?>&nbsp;</td>
		<td>
		<?php 
			
			if ($survey['Survey']['live_instance'] == $surveyInstance['SurveyInstance']['id'])
				echo "Live";
			else if ($surveyInstance['SurveyInstance']['locked'])
				echo "Expired";
			else
				echo "Open";
		?>
		</td>
		<td class="actions">
			<?php 
			if (!$surveyInstance['SurveyInstance']['locked']) {
				echo $this->Html->link(__('Edit'), array('action' => 'edit', $surveyInstance['SurveyInstance']['id'])); 
				echo $this->Html->link(__('Publish'), array('action' => 'publish', $surveyInstance['SurveyInstance']['id'])); 
				echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $surveyInstance['SurveyInstance']['id'], $survey['Survey']['id']), null, __('Are you sure you want to delete ?')); 
				echo $this->Html->link(__('Preview'), array('action' => 'preview', $surveyInstance['SurveyInstance']['id']), array('target' => '_new'));
			}
			else if ($survey['Survey']['live_instance'] == $surveyInstance['SurveyInstance']['id'])
			{
				echo $this->Html->link(__('View'), array('action' => 'view', $surveyInstance['SurveyInstance']['id']));
				echo $this->Form->postLink(__('Close'), array('action' => 'close', $surveyInstance['SurveyInstance']['id'], $survey['Survey']['id']), null, __('Are you sure you want to close ?'));		
				echo $this->Html->link(__('Results'), array('controller' => 'surveyResults', 'action' => 'index', $surveyInstance['SurveyInstance']['id']));
				echo $this->Html->link(__('Live Link'), '#', array('onClick' => '$("#live_dialog").dialog("open")'));
				echo $this->Html->link(__('Preview'), array('action' => 'preview', $surveyInstance['SurveyInstance']['id']), array('target' => '_new'));
			}
			else
			{
				echo $this->Html->link(__('View'), array('action' => 'view', $surveyInstance['SurveyInstance']['id']));
				echo $this->Html->link(__('Results'), array('controller' => 'surveyResults', 'action' => 'index', $surveyInstance['SurveyInstance']['id']));
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
		<li><?php echo $this->Html->link(__('New Survey Instance'), array('action' => 'add', $survey['Survey']['id'])); ?></li>
	</ul>
	<br/><br/>
	<ul>
		<li><?php echo $this->Html->link(__('Return to Survey'), array('controller' => 'surveys', 'action' => 'edit', $survey['Survey']['id'])); ?> </li>
	</ul>
</div>

<div id="live_dialog" title="Live Link" style="display: none;">
	<p>This is the link that needs to be provided to survey participants: <?php echo $this->Html->url(array('controller' => 'public', 'action' => $survey['Survey']['short_name'] ), true);?>/</p>
</div>

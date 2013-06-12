<div class="surveyResults index">
	<h2><?php echo __('Survey Results');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('survey_instance_id');?></th>
			<th><?php echo $this->Paginator->sort('date');?></th>
			<th><?php echo $this->Paginator->sort('participant_id');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($surveyResults as $surveyResult): ?>
	<tr>		
		<td><?php echo h($surveyResult['SurveyResult']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($surveyResult['SurveyInstance']['name'], array('controller' => 'survey_instances', 'action' => 'view', $surveyResult['SurveyInstance']['id'])); ?>
		</td>
		<td><?php echo h($surveyResult['SurveyResult']['date']); ?>&nbsp;</td>
		<td>
			<?php if (!$surveyResult['SurveyResult']['participant_id'])
					echo "Anonymous";
				  else if (!$surveyResult['Participant']['username'])
				  	echo "Deleted Participant";
				  else 
					echo $this->Html->link($surveyResult['Participant']['username'], array('controller' => 'participants', 'action' => 'edit', $surveyResult['Participant']['id'])); 
			?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $surveyResult['SurveyResult']['id'])); ?>
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
<script type="text/javascript">
//<![CDATA[
	function exportDialog() {
		$('#exportDialog').dialog({
		modal: true,
		buttons: {
			Export: function() {
				$("#SurveyResultsExportForm").submit();
				$(this).dialog("close");
			},
			Cancel: function() {
				$(this).dialog("close");
			}
		}
		});
		return false;
	}
//]]>
</script>
<div id="exportDialog" title="Export" style="display: none;">
<?php
echo $this->Form->create('SurveyResults', array('action' => 'export'));
echo $this->Form->label('blankResponse', 'Value for empty response:');
echo $this->Form->text('blankResponse', array('default' => '*'));
echo $this->Form->label('nullResponse', 'Value for unvisited questions:');
echo $this->Form->text('nullResponse', array('default' => '.'));
echo $this->Form->hidden('surveyInstanceId', array('default' => $surveyInstance['SurveyInstance']['id']));
echo $this->Form->end();
?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Export Results'), array('controller' => 'surveyResults', 'action' => 'export', $surveyInstance['SurveyInstance']['id']), array('onClick' => 'javascript:return exportDialog();')); ?></li>
	</ul>
	<br/><br/>
	<ul>
		<li><?php echo $this->Html->link(__('Return to Survey'), array('controller' => 'surveyInstances', 'action' => 'index', $survey['Survey']['id'])); ?> </li>
	</ul>
</div>

<div class="surveys index">
	<h2><?php echo __('Surveys');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('group_id', 'Research Group');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('short_name');?></th>
			<th><?php echo $this->Paginator->sort('type');?></th>
			<th><?php echo $this->Paginator->sort('multiple_run');?></th>
			<th><?php echo $this->Paginator->sort('user_id', 'Owner');?></th>
			<th><?php echo $this->Paginator->sort('live_instance');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($surveys as $survey): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($survey['Group']['name'], array('controller' => 'groups', 'action' => 'view', $survey['Group']['id'])); ?>
		</td>
		<td><?php echo h($survey['Survey']['name']); ?>&nbsp;</td>
		<td><?php echo h($survey['Survey']['short_name']); ?>&nbsp;</td>
		<td><?php 
			if ($survey['Survey']['type'] == Survey::type_public)
				echo h("Public");
			else if ($survey['Survey']['type'] == Survey::type_private)
				echo h("Private");
			else if ($survey['Survey']['type'] == Survey::type_identified)
				echo h("Identified");
			 
		?>&nbsp;</td>
		<td><?php 
			if ($survey['Survey']['multiple_run'] == 0)
				echo h("No");
			else
				echo h("Yes"); 
		?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($survey['User']['username'], array('controller' => 'users', 'action' => 'view', $survey['User']['id'])); ?>
		</td>
		<td><?php 
			if ($survey['Survey']['live_instance'] == '')
				echo h("None");
			else
				echo $survey['Survey']['live_instance'];
		?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $survey['Survey']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $survey['Survey']['id']), null, __('Are you sure you want to delete # %s?', $survey['Survey']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('Create New Survey'), array('action' => 'add')); ?></li>
	</ul>	
	<br/><br/>
	<ul>
		<li><?php echo $this->Html->link(__('Return to Dashboard'), array('controller' => 'dashboard', 'action' => 'index')); ?> </li>
	</ul>
</div>

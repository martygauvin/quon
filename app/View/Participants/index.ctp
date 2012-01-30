<div class="participants index">
	<h2><?php echo __('Participants');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('survey_id');?></th>
			<th><?php echo $this->Paginator->sort('given_name');?></th>
			<th><?php echo $this->Paginator->sort('surname');?></th>
			<th><?php echo $this->Paginator->sort('dob');?></th>
			<th><?php echo $this->Paginator->sort('username');?></th>
			<th><?php echo $this->Paginator->sort('email');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($participants as $participant): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($participant['Survey']['name'], array('controller' => 'surveys', 'action' => 'edit', $participant['Survey']['id'])); ?>
		</td>
		<td><?php echo h($participant['Participant']['given_name']); ?>&nbsp;</td>
		<td><?php echo h($participant['Participant']['surname']); ?>&nbsp;</td>
		<td><?php echo h($participant['Participant']['dob']); ?>&nbsp;</td>
		<td><?php echo h($participant['Participant']['username']); ?>&nbsp;</td>
		<td><?php echo h($participant['Participant']['email']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Survey Results'), array('controller' => 'surveyresults', 'action' => 'view', $participant['Participant']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $participant['Participant']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $participant['Participant']['id']), null, __('Are you sure you want to delete # %s?', $participant['Participant']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('Create New Participant'), array('action' => 'add')); ?></li>
	</ul>
	<br/><br/>
	<ul>
		<li><?php echo $this->Html->link(__('Return to Dashboard'), array('controller' => 'dashboard', 'action' => 'index')); ?> </li>
	</ul>
</div>

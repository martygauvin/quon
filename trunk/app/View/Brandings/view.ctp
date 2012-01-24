<div class="brandings view">
<h2><?php  echo __('Branding');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($branding['Branding']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($branding['Branding']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Css'); ?></dt>
		<dd>
			<?php echo h($branding['Branding']['css']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Survey'); ?></dt>
		<dd>
			<?php echo $this->Html->link($branding['Survey']['name'], array('controller' => 'surveys', 'action' => 'view', $branding['Survey']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Branding'), array('action' => 'edit', $branding['Branding']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Branding'), array('action' => 'delete', $branding['Branding']['id']), null, __('Are you sure you want to delete # %s?', $branding['Branding']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Brandings'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Branding'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Surveys'), array('controller' => 'surveys', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Survey'), array('controller' => 'surveys', 'action' => 'add')); ?> </li>
	</ul>
</div>

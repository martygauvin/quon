<div class="dashboard">

<?	
	if ($type == 'admin')
	{
		// Administrator menu
?>
<h2>System Administrator Menu</h2>

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('System Setup', true), '/configurations/'); ?></li>
		<li><?php echo $this->Html->link(__('Research Users', true), '/users/'); ?></li>	
		<li><?php echo $this->Html->link(__('Research Groups', true), '/groups/'); ?></li>
	</ul>
	<ul>
		<li><?php echo $this->Html->link(__('Logout', true), '/users/logout'); ?></li>
	</ul>
</div>

<?
	}
	else
	{
		// Researcher menu
?>

<h2>Researcher Menu</h2>

<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Surveys', true), '/surveys/'); ?></li>
		<li><?php echo $this->Html->link(__('Participants', true), '/participants/'); ?></li>
	</ul>
	<ul>
		<li><?php echo $this->Html->link(__('Logout', true), '/users/logout'); ?></li>
	</ul>
</div>

<?
	}
?>
	
</div>
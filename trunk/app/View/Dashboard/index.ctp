<div class="dashboard">

<p><h2>System Administrator Menu</h2></p>

<?	
	if ($type == 'admin')
	{
		// Administrator menu
?>
<div class="actions">
	<ul>
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
	

<div class="surveyResultAnswer form">

<script type="text/javascript">
function questionSubmit($direction)
{
	self.close();
	return false;
}

</script>

<?php echo $this->Form->create('Public', array('url' => array('controller' => 'public', 'action' => 'answer')));?>
	<fieldset>
	<?php
		$questionHelper = $this->Question->getHelper($surveyObject['SurveyObject']['type']);
		
		$show_next = true;
		$questionHelper->render($this->Form, $surveyObjectAttributes, $show_next);
	?>
	</fieldset>
<?php 
	echo $this->Form->submit('Back', array('class' => 'buttonLeft', 'onClick' => 'javascript:return questionSubmit(\'back\');'));	
	
	if ($show_next)
		echo $this->Form->submit('Next', array('class' => 'buttonRight', 'onClick' => 'javascript:return questionSubmit(\'next\');'));
	
	echo $this->Form->end();
?>
</div>

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
		$show_next = true;
		
		if (isset($meta) && $meta == 'true')
		{
			$cnt = 0;
			foreach ($surveyObject as $metaObject)
			{
				$questionHelper = $this->Question->getHelper($metaObject['SurveyObject']['type']);
				$questionHelper->render($this->Form, $surveyObjectAttributes[$cnt], $show_next);
				$cnt++;
				
				echo "<br/><br/><hr/><br/>";
				
			}
		}
		else
		{
			$questionHelper = $this->Question->getHelper($surveyObject['SurveyObject']['type']);
			$questionHelper->render($this->Form, $surveyObjectAttributes, $show_next);
		}
	?>
	</fieldset>
<?php 
	echo $this->Form->submit('Close', array('class' => 'buttonLeft', 'onClick' => 'javascript:return questionSubmit(\'back\');'));	
	
	echo $this->Form->end();
?>
</div>

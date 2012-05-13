<div id="logo">
	<?php 
		if (array_key_exists(SurveyAttribute::attribute_logo, $surveyAttributes))
		{
			echo "<img src='".$this->Html->url("../".$surveyAttributes[SurveyAttribute::attribute_logo], true)."'/><br/><br/>";
		}
	
	?>
</div>

<div class="complete form">

<?php 
	if ($preview)
	{
?>
You have completed the preview of the survey.
<?php 
	}
	else
	{
?>
Thank you for completing the '<?php echo $survey['Survey']['name'];?>' survey.
<?php 
	}
?>

</div>
<?php 
	$this->Branding->applyBranding($surveyAttributes);
?>

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
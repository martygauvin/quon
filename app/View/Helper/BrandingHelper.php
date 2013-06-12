<?php
App::uses('AppHelper', 'View/Helper');

class BrandingHelper extends AppHelper {
	var $helpers = array('Html');
	
	// Factory-level methods
	function applyBranding($surveyAttributes) {
		echo "<div id='logo'>";

		if (array_key_exists(SurveyAttribute::attribute_logo, $surveyAttributes))
		{
			echo "<img alt='logo' src='".$this->Html->url("../".$surveyAttributes[SurveyAttribute::attribute_logo], true)."'/>";
		}
	
		if (array_key_exists(SurveyAttribute::attribute_stylesheet, $surveyAttributes))
		{
			echo "<link rel='stylesheet' type='text/css' href='".$this->Html->url("../".$surveyAttributes[SurveyAttribute::attribute_stylesheet], true)."'/>";
		}
		
		if (array_key_exists(SurveyAttribute::attribute_mobilestyle, $surveyAttributes))
		{
			preg_match('/(iPhone|iPad|Android|MIDP|AvantGo|BlackBerry|J2ME|Opera Mini|DoCoMo|NetFront|Nokia|PalmOS|PalmSource|portalmmm|Plucker|ReqwirelessWeb|SonyEricsson|Symbian|UP\.Browser|Windows CE|Xiino)/i', $_SERVER['HTTP_USER_AGENT'], $match);
			if (!empty($match)) {
				echo "<link rel='stylesheet' type='text/css' href='".$this->Html->url("../".$surveyAttributes[SurveyAttribute::attribute_mobilestyle], true)."'/>";
			}
		}
		
		if (array_key_exists(SurveyAttribute::attribute_javascript, $surveyAttributes))
		{
			echo "<script type='text/javascript' src='".$this->Html->url("../".$surveyAttributes[SurveyAttribute::attribute_javascript], true)."'></script>";
		}
		
		if (array_key_exists(SurveyAttribute::attribute_mobilescript, $surveyAttributes))
		{
			preg_match('/(iPhone|Android|MIDP|AvantGo|BlackBerry|J2ME|Opera Mini|DoCoMo|NetFront|Nokia|PalmOS|PalmSource|portalmmm|Plucker|ReqwirelessWeb|SonyEricsson|Symbian|UP\.Browser|Windows CE|Xiino)/i', $_SERVER['HTTP_USER_AGENT'], $match);
			if (!empty($match)) {
				echo "<script type='text/javascript' src='".$this->Html->url("../".$surveyAttributes[SurveyAttribute::attribute_mobilescript], true)."'></script>";
			}
		}
		
		echo "</div>";
	}
	
}
?>

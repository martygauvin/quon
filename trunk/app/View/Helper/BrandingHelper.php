<?php
App::uses('AppHelper', 'View/Helper');

class BrandingHelper extends AppHelper {
	var $helpers = array('Html');
	
	// Factory-level methods
	function applyBranding($surveyAttributes) {
		echo "<div id='logo'>";

		if (array_key_exists(SurveyAttribute::attribute_logo, $surveyAttributes))
		{
			echo "<img src='".$this->Html->url("../".$surveyAttributes[SurveyAttribute::attribute_logo], true)."'/><br/><br/>";
		}
	
		if (array_key_exists(SurveyAttribute::attribute_stylesheet, $surveyAttributes))
		{
			echo "<link rel='stylesheet' type='text/css' href='".$this->Html->url("../".$surveyAttributes[SurveyAttribute::attribute_stylesheet], true)."'/>";
		}
		
		if (array_key_exists(SurveyAttribute::attribute_mobilestyle, $surveyAttributes))
		{
			preg_match('/(iPhone|Android|MIDP|AvantGo|BlackBerry|J2ME|Opera Mini|DoCoMo|NetFront|Nokia|PalmOS|PalmSource|portalmmm|Plucker|ReqwirelessWeb|SonyEricsson|Symbian|UP\.Browser|Windows CE|Xiino)/i', $_SERVER['HTTP_USER_AGENT'], $match);
			if (!empty($match)) {
				echo "<link rel='stylesheet' type='text/css' href='".$this->Html->url("../".$surveyAttributes[SurveyAttribute::attribute_mobilestyle], true)."'/>";
			}
		}
		
		echo "</div>";
	}
	
}
?>
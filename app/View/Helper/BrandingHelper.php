<?php
/**
 * BrandingHelper
 * @package View.Helper
 */
App::uses('AppHelper', 'View/Helper');

/**
 * Class to help with branding of survey for a particular look/device
 */
class BrandingHelper extends AppHelper {
	/** The helpers to use.*/
	var $helpers = array('Html');
	
	// Factory-level methods
	/**
	 * Applies branding to the survey.
	 * @param unknown_type $surveyAttributes The survey attributes to read branding information from
	 */
	function applyBranding($surveyAttributes) {
		echo "<div id='logo'>";

		if (array_key_exists(SurveyAttribute::attribute_logo, $surveyAttributes))
		{
			echo "<img src='".$this->Html->url("../".$surveyAttributes[SurveyAttribute::attribute_logo], true)."'/>";
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

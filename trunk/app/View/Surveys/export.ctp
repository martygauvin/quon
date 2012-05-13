<?php
header("Content-type:text/xml");
header('Content-disposition:attachment;filename="'.$survey['Survey']['short_name'].'_metadata_'.date(DATE_ATOM).'.xml"');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$baseUrl = $_SERVER['HTTP_HOST'].$this->base;
$originatingSource = $protocol.$baseUrl; 
$institutionName = $institution['Configuration']['value'];
$surveyKey = $baseUrl.'/key/survey/'.$survey['Survey']['id'];

echo '<?xml version="1.0"?>'."\n";
echo '<registryObjects xmlns="http://ands.org.au/standards/rif-cs/registryObjects"'."\n";
echo '  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'."\n";
echo '  xsi:schemaLocation="http://ands.org.au/standards/rif-cs/registryObjects'."\n";
echo '  http://services.ands.org.au/documentation/rifcs/schema/registryObjects.xsd">'."\n";

$researchGroupKey = $group['Group']['external_identifier'];
if (!$researchGroupKey) {
	$researchGroupKey = $baseUrl.'/key/user_group/'.$group['Group']['id'];
	$researchGroupIdentifier = $baseUrl.'/user_group/'.$group['Group']['id'];
	$researchGroupName = $group['Group']['name'];
	
	echo '  <registryObject group="'.$institutionName.'">'."\n";
	echo '    <key>'.$researchGroupKey.'</key>'."\n";
	echo '    <originatingSource>'.$originatingSource.'</originatingSource>'."\n";
	echo '    <party type="group">'."\n";
	echo '      <identifier type="uri">'.$researchGroupIdentifier.'</identifier>'."\n";
	echo '      <name type="primary">'."\n";
	echo '        <namePart>'.$researchGroupName.'</namePart>'."\n";
	echo '      </name>'."\n";
	echo '      <relatedObject>'."\n";
	echo '        <key>'.$surveyKey.'</key>'."\n";
	echo '        <relation type="isCollectorOf"></relation>'."\n";
	echo '      </relatedObject>'."\n";
	echo '    </party>'."\n";
	echo '  </registryObject>'."\n";
	echo "\n";
}

$researcherKeys = array();
foreach ($researchers as $researcher) {
	$researcherKey = $researcher['User']['external_identifier'];
	
	if (!$researcherKey) {
		$researcherKey = $baseUrl.'/key/user/'.$researcher['User']['id'];
		$researcherIdentifier = $baseUrl.'/user/'.$researcher['User']['id'];
		$researcherGivenName = $researcher['User']['given_name'];
		$researcherSurname = $researcher['User']['surname'];
		
		echo '  <registryObject group="'.$institutionName.'">'."\n";
		echo '    <key>'.$researcherKey.'</key>'."\n";
		echo '    <originatingSource>'.$originatingSource.'</originatingSource>'."\n";
		echo '    <party type="person">'."\n";
		echo '      <identifier type="uri">'.$researcherIdentifier.'</identifier>'."\n";
		echo '      <name type="primary">'."\n";
		echo '        <namePart type="given">'.$researcherGivenName.'</namePart>'."\n";
		echo '        <namePart type="family">'.$researcherSurname.'</namePart>'."\n";
		echo '      </name>'."\n";
		echo '      <relatedObject>'."\n";
		echo '        <key>'.$researchGroupKey.'</key>'."\n";
		echo '        <relation type="isMemberOf"></relation>'."\n";
		echo '      </relatedObject>'."\n";
		echo '      <relatedObject>'."\n";
		echo '        <key>'.$surveyKey.'</key>'."\n";
		echo '        <relation type="isCollectorOf"></relation>'."\n";
		echo '      </relatedObject>'."\n";
		echo '    </party>'."\n";
		echo '  </registryObject>'."\n";
		echo "\n";
	}
	$researcherKeys[] = $researcherKey;
}

$surveyIdentifier = $baseUrl.'/survey/'.$survey['Survey']['id'];
$surveyName = $survey['Survey']['name'];
$surveyDescription = $metadata['SurveyMetadata']['description'];
$surveySignificanceStatement = 'Collection contains '.$significance.' completed surveys';
$surveyUrl = $protocol.$baseUrl.'/public/'.$survey['Survey']['short_name'];
$surveyAccessRights = $metadata['SurveyMetadata']['access_rights'];

echo '  <registryObject group="'.$institutionName.'">'."\n";
echo '    <key>'.$surveyKey.'</key>'."\n";
echo '    <originatingSource>'.$originatingSource.'</originatingSource>'."\n";
echo '    <collection type="dataset">'."\n";
echo '      <identifier type="uri">'.$surveyIdentifier.'</identifier>'."\n";
echo '      <name type="primary">'."\n";
echo '        <namePart>'.$surveyName.'</namePart>'."\n";
echo '      </name>'."\n";
echo '      <description type="full">'.$surveyDescription.'</description>'."\n";
echo '      <description type="significanceStatement">'.$surveySignificanceStatement.'</description>'."\n";
echo '      <location>'."\n";
echo '        <address>'."\n";
echo '          <electronic type="url">'."\n";
echo '            <value>'.$surveyUrl.'</value>'."\n";
echo '          </electronic>'."\n";
echo '        </address>'."\n";
echo '      </location>'."\n";
echo '      <relatedObject>'."\n";
echo '        <key>'.$researchGroupKey.'</key>'."\n";
echo '        <relation type="hasCollector"></relation>'."\n";
echo '      </relatedObject>'."\n";
foreach ($researcherKeys as $researcherKey) {
	echo '      <relatedObject>'."\n";
	echo '        <key>'.$researcherKey.'</key>'."\n";
	echo '        <relation type="hasCollector"></relation>'."\n";
	echo '      </relatedObject>'."\n";
}
echo '      <relatedObject>'."\n";
echo '        <key>'.$researchGroupKey.'</key>'."\n";
echo '        <relation type="isManagedBy"></relation>'."\n";
echo '      </relatedObject>'."\n";
echo '      <rights>'."\n";
echo '        <accessRights>'.$surveyAccessRights.'</accessRights>'."\n";
echo '      </rights>'."\n";
echo '    </collection>'."\n";
echo '  </registryObject>'."\n";

echo '</registryObjects>';
?>
<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
/**
 * Surveys Controller
 *
 * @property Survey $Survey
 */

// TODO: Add "return URL" feature to display on auto-generated final page

class SurveysController extends AppController {
	public $uses = array('Survey', 'SurveyInstance', 'SurveyMetadata', 'User', 'Configuration', 'Group', 'SurveyAttribute', 'SurveyResult');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Survey->recursive = 0;
		$this->paginate = array(
			'conditions' => array('Survey.group_id IN (select User_Group.group_id from user_groups as User_Group where User_Group.user_id='.$this->Auth->user('id').')')		
		);
		$this->set('surveys', $this->paginate());
	}
	
	/**
	 * metadata method
	 *
	 * @param string $id
	 * @return void
	 */
	public function metadata($id = null) {
		$this->Survey->id = $id;
		if (!$this->Survey->exists()) {
			throw new NotFoundException(__('Invalid survey'));
		}
	
		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$survey = $this->Survey->read(null, $id);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			
			$existing = $this->SurveyMetadata->findBySurveyId($id);
			if (!$existing) {
				$this->SurveyMetadata->create();
				$this->request->data['SurveyMetadata']['survey_id'] = $id;
			}
			
			if ($this->SurveyMetadata->save($this->request->data)) {
				$this->Session->setFlash(__('The survey metadata has been saved'));
				$this->redirect(array('action' => 'metadata', $id));
			} else {
				$this->Session->setFlash(__('The survey could not be saved. Please, try again.'));
			}
		} else {
			$redboxLocation = $this->Configuration->findByName('ReDBox publish location');
			$publishLocation = $redboxLocation['Configuration']['value'];
			$publishSupported = isset($publishLocation) && "" <> $publishLocation;
			$this->set('publishSupported', $publishSupported);
			$this->request->data = $this->SurveyMetadata->findBySurveyId($id);
			$this->set('survey', $survey);
		}
	}
	
	/**
	 * export method
	 *
	 * @return void
	 */
	public function export($survey_id = null) {
		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$survey = $this->Survey->read(null, $survey_id);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}
	
		// Disable layout engine and debugging
		$this->layout = "";
		
		$institution = $this->Configuration->findByName('Institution');
		$group = $this->Group->findById($survey['Survey']['group_id']);
		$metadata = $this->SurveyMetadata->findBySurveyId($survey_id);
		$researchers = $group['User'];
		
		$significance = $this->SurveyResult->find('count', array('conditions' => array('SurveyInstance.survey_id' => $survey_id)));
		
		$this->set('institution', $institution);
		$this->set('group', $group);
		$this->set('survey', $survey);
		$this->set('metadata', $metadata);
		$this->set('researchers', $researchers);
		$this->set('significance', $significance);
	}
	
	/**
	 * publish method
	 * 
	 * Writes an XML file that can be injested by ReDBox to the ReDBox publish location.
	 * 
	 * @param int $survey_id
	 */
	public function publish($survey_id = null) {
		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$survey = $this->Survey->read(null, $survey_id);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}
		$redboxLocation = $this->Configuration->findByName('ReDBox publish location');
		$publishLocation = $redboxLocation['Configuration']['value'];
		
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$baseUrl = $_SERVER['HTTP_HOST'].$this->base;
		$group = $this->Group->findById($survey['Survey']['group_id']);
		$researchGroupKey = $group['Group']['external_identifier'];
		if (!$researchGroupKey) {
			$researchGroupKey = $baseUrl.'/key/user_group/'.$group['Group']['id'];
		}
		
		$metadata = $this->SurveyMetadata->findBySurveyId($survey_id);
		$group = $this->Group->findById($survey['Survey']['group_id']);
		$researchers = $group['User'];
	
		$doc = new DOMDocument('1.0', 'utf-8');
		$doc->formatOutput = true;
		$redboxCollection = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:RedboxCollection');
		$doc->appendChild($redboxCollection);
		$redboxCollection->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$redboxCollection->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:my', 'http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47');
		$redboxCollection->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xd', 'http://schemas.microsoft.com/office/infopath/2003');

		$title = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:Title');
		$title->appendChild($doc->createTextNode($survey['Survey']['name']));
		$redboxCollection->appendChild($title);
		
		$type = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:Type');
		$type->appendChild($doc->createTextNode('dataset'));
		$redboxCollection->appendChild($type);
		
		$created = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:DateCreated');
		$created->appendChild($doc->createTextNode(date('Y-m-d')));
		$redboxCollection->appendChild($created);
		
		$description = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:Description');
		$description->appendChild($doc->createTextNode($metadata['SurveyMetadata']['description']));
		$redboxCollection->appendChild($description);
		
		$creators = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:Creators');
		$redboxCollection->appendChild($creators);
		
		foreach ($researchers as $researcher) {
			$researcherId = $researcher['external_identifier'];
			if (!$researcherId) {
				$researcherId = $baseUrl.'/key/user/'.$researcher['id'];
			}
			
			$creator = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:Creator');
			$creators->appendChild($creator);
			
			$creatorGiven = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:CreatorGiven');
			$creatorGiven->appendChild($doc->createTextNode($researcher['given_name']));
			$creator->appendChild($creatorGiven);
			
			$creatorFamily = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:CreatorFamily');
			$creatorFamily->appendChild($doc->createTextNode($researcher['surname']));
			$creator->appendChild($creatorFamily);
			
			$creatorId = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:CreatorID');
			$creatorId->appendChild($doc->createTextNode($researcherId));
			$creator->appendChild($creatorId);
			
			$creatorAffiliation = $doc->createElementNs('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:CreatorAffiliation');
			$creatorAffiliation->appendChild($doc->createTextNode($group['Group']['name']));
			$creator->appendChild($creatorAffiliation);
			
			$creatorAffiliationId = $doc->createElementNs('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:CreatorAffiliationId');
			$creatorAffiliationId->appendChild($doc->createTextNode($researchGroupKey));
			$creator->appendChild($creatorAffiliationId);
		}
		
		$primaryContact = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:PrimaryContact');
		$redboxCollection->appendChild($primaryContact);
		
		$primaryContactFirstName = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:PrimaryContactFirstName');
		$primaryContactFirstName->appendChild($doc->createTextNode($user['User']['given_name']));
		$primaryContact->appendChild($primaryContactFirstName);
		
		$primaryContactFamilyName = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:PrimaryContactFamilyName');
		$primaryContactFamilyName->appendChild($doc->createTextNode($user['User']['surname']));
		$primaryContact->appendChild($primaryContactFamilyName);
		
		$primaryContactEmail = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:PrimaryContactEmail');
		$primaryContactEmail->appendChild($doc->createTextNode($user['User']['email']));
		$primaryContact->appendChild($primaryContactEmail);
		
		$rights = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:Rights');
		$redboxCollection->appendChild($rights);
		
		$rightsAccess = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:RightsAccess');
		$rightsAccess->appendChild($doc->createTextNode($metadata['SurveyMetadata']['access_rights']));
		$rights->appendChild($rightsAccess);
		
		$identifier = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:Identifier');
		$redboxCollection->appendChild($identifier);
		
		$identifierType = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:IdentifierType');
		$identifierType->appendChild($doc->createTextNode('uri'));
		$identifier->appendChild($identifierType);
		
		$identifierValue = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:IdentifierValue');
		$identifierValue->appendChild($doc->createTextNode($baseUrl.'/survey/'.$survey['Survey']['id']));
		$identifier->appendChild($identifierValue);
		
		$identifierUseMetadataId = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:IdentifierUseMetadataID');
		$identifierUseMetadataId->appendChild($doc->createTextNode('false'));
		$identifier->appendChild($identifierUseMetadataId);
		
		$location = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:Location');
		$redboxCollection->appendChild($location);
		
		$locationURLs = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:LocationURLs');
		$location->appendChild($locationURLs);
		
		$locationURL = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:LocationURL');
		$locationURLs->appendChild($locationURL);
		
		$locationURLValue = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:LocationURLValue');
		$locationURLValue->appendChild($doc->createTextNode($protocol.$baseUrl.'/public/'.$survey['Survey']['short_name']));
		$locationURL->appendChild($locationURLValue);
		
		$submissionDetails = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:SubmissionDetails');
		$redboxCollection->appendChild($submissionDetails);
		
		$workflowSource = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:WorkflowSource');
		$workflowSource->appendChild($doc->createTextNode($protocol.$baseUrl));
		$submissionDetails->appendChild($workflowSource);
		
		$contactPersonName = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:ContactPersonName');
		$contactPersonName->appendChild($doc->createTextNode($user['User']['given_name'].' '.$user['User']['surname']));
		$submissionDetails->appendChild($contactPersonName);
		
		$contactPersonEmail = $doc->createElementNS('http://schemas.microsoft.com/office/infopath/2003/myXSD/2011-09-26T07:17:47', 'my:ContactPersonEmail');
		$contactPersonEmail->appendChild($doc->createTextNode($user['User']['email']));
		$submissionDetails->appendChild($contactPersonEmail);
		
		$doc->save($publishLocation.'/'.$survey['Survey']['short_name'].'_'.$survey_id.'.xml');
		
		$this->redirect(array('action' => 'metadata', $survey_id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			// Permission check to ensure a user is allowed to add a survey to this group
			$user = $this->User->read(null, $this->Auth->user('id'));
			if (!$this->SurveyAuthorisation->checkResearcherPermissionToGroup($user, $this->request->data['Survey']['group_id']))
			{
				$this->Session->setFlash(__('Permission Denied'));
				$this->redirect(array('action' => 'index'));
			}
			
			$success = true;
			
			$short_name = $this->request->data['Survey']['short_name'];
			$existing = $this->Survey->find('first', array('conditions' => array('short_name' => $short_name)));
			
			if ($existing)
			{
				$this->Session->setFlash(__('Survey with that short name already exists'));
				$success = false;
			}
			
			if ($success)
			{	
				$this->Survey->create();
				$this->request->data['Survey']['user_id'] = $this->Auth->user('id');
				if (!$this->Survey->save($this->request->data)) {
					$this->Session->setFlash(__('The survey could not be saved. Please, try again.'));
					$success = false;
				}
			}
			
			if ($success)
			{
				$this->SurveyInstance->create();
				$surveyInstance = array();
				$surveyInstance['SurveyInstance']['survey_id'] = $this->Survey->getInsertId();
				$surveyInstance['SurveyInstance']['name'] = "1.0";
				
				if ($this->SurveyInstance->save($surveyInstance)) {
					$this->Session->setFlash(__('The survey has been saved'));
				} else {
					$this->Session->setFlash(__('The survey could not be saved. Please, try again.'));
					$success = false;
				}
			}
						
			if ($success == true)
			{
				$this->redirect(array('action' => 'index'));
			}
		}
		$groups = $this->Survey->Group->find('list', array('conditions' => array('Group.id IN (select User_Group.group_id from user_groups as User_Group where User_Group.user_id='.$this->Auth->user('id').')')));
		$this->set(compact('groups'));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Survey->id = $id;
		if (!$this->Survey->exists()) {
			throw new NotFoundException(__('Invalid survey'));
		}
		
		// Permission check to ensure a user is allowed to edit this survey 
		$user = $this->User->read(null, $this->Auth->user('id'));
		$survey = $this->Survey->read(null, $id);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Survey->save($this->request->data)) {
				
				$success = true;
				
				if ($this->request->data['Survey']['logo']['name'])
				{
					$fileOK = $this->uploadFiles('uploads', $this->request->data['Survey']['logo'], $id);
					
					if(array_key_exists('urls', $fileOK)) {
						
						$logo = $this->SurveyAttribute->find('first',
							array('conditions' => array('survey_id' => $id, 
														'SurveyAttribute.name' => SurveyAttribute::attribute_logo)));
						
						$logo['SurveyAttribute']['name'] = SurveyAttribute::attribute_logo;
						$logo['SurveyAttribute']['survey_id'] = $id;
						$logo['SurveyAttribute']['value'] = $fileOK['urls'][0];
						
						$this->SurveyAttribute->save($logo);
					}
					else
					{
						$this->Session->setFlash(__('Failed to process image upload'));
						$success = false;
					}
				}
				
				if ($this->request->data['Survey']['stylesheet']['name'])
				{
					$fileOK = $this->uploadFiles('uploads', $this->request->data['Survey']['stylesheet'], $id);
						
					if(array_key_exists('urls', $fileOK)) {
				
						$style = $this->SurveyAttribute->find('first',
							array('conditions' => array('survey_id' => $id,
														'SurveyAttribute.name' => SurveyAttribute::attribute_stylesheet)));
				
						$style['SurveyAttribute']['name'] = SurveyAttribute::attribute_stylesheet;
						$style['SurveyAttribute']['survey_id'] = $id;
						$style['SurveyAttribute']['value'] = $fileOK['urls'][0];
				
						$this->SurveyAttribute->save($style);
					}
					else
					{
						$this->Session->setFlash(__('Failed to process stylesheet upload'));
						$success = false;
					}
				}
				
				if ($this->request->data['Survey']['mobilestylesheet']['name'])
				{
					$fileOK = $this->uploadFiles('uploads', $this->request->data['Survey']['mobilestylesheet'], $id);
				
					if(array_key_exists('urls', $fileOK)) {
				
						$style = $this->SurveyAttribute->find('first',
							array('conditions' => array('survey_id' => $id,
														'SurveyAttribute.name' => SurveyAttribute::attribute_mobilestyle)));
				
						$style['SurveyAttribute']['name'] = SurveyAttribute::attribute_mobilestyle;
						$style['SurveyAttribute']['survey_id'] = $id;
						$style['SurveyAttribute']['value'] = $fileOK['urls'][0];
				
						$this->SurveyAttribute->save($style);
					}
					else
					{
						$this->Session->setFlash(__('Failed to process mobile stylesheet upload'));
						$success = false;
					}
				}
				
				if ($success)
				{
					$this->Session->setFlash(__('The survey has been saved'));
					$this->redirect(array('action' => 'index'));
				}
				
			} else {
				$this->Session->setFlash(__('The survey could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Survey->read(null, $id);
		}
		$groups = $this->Survey->Group->find('list');
		$this->set(compact('groups'));
		
		$surveyAttributes = $this->SurveyAttribute->find('all',
			array('conditions' => array('SurveyAttribute.survey_id' => $id)));
		$this->set('surveyAttributes', $this->flatten_attributes($surveyAttributes));
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Survey->id = $id;
		if (!$this->Survey->exists()) {
			throw new NotFoundException(__('Invalid survey'));
		}
		
		// Permission check to ensure a user is allowed to delete this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$survey = $this->Survey->read(null, $id);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->Survey->delete()) {
			$this->Session->setFlash(__('Survey deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Survey was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
/**
* isAuthorized method
* @param  user the logged in user, or null if unauthenticated
*
* @return boolean representing if a user can access this controller
*/
	public function isAuthorized($user = null) {
		if ($user != null && $user['type'] == User::type_admin)
			return false;
		else if ($user != null && $user['type'] == User::type_researcher)
			return true;
		else
			return false;
	}
}

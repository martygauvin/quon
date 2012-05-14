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
				$this->redirect(array('action' => 'edit', $id));
			} else {
				$this->Session->setFlash(__('The survey could not be saved. Please, try again.'));
			}
		} else {
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

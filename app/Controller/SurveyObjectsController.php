<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
App::uses('SurveyObject', 'Model');
/**
 * SurveyObjects Controller
 *
 * @property SurveyObject $SurveyObject
 */
class SurveyObjectsController extends AppController {
	public $uses = array('SurveyObject', 'Survey', 'SurveyObjectAttribute', 'User');
	public $helpers = array('Form', 'Html', 'Js', 'Time', 'Question');
	
/**
 * index method
 *
 * @return void
 */
	public function index($survey_id = null) {
		// Permission check to ensure a user is allowed view this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$survey = $this->Survey->read(null, $survey_id);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}
		
		$this->SurveyObject->recursive = 0;
		$this->paginate = array('conditions' => array('Survey.id' => $survey_id));
		$this->set('surveyObjects', $this->paginate());
		$this->set('survey', $this->Survey->read(null, $survey_id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($survey_id = null) {
		if ($this->request->is('post')) {
			// Permission check to ensure a user is allowed to add a survey to this group
			$user = $this->User->read(null, $this->Auth->user('id'));
			$survey = $this->Survey->read(null, $survey_id);
			if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
			{
				$this->Session->setFlash(__('Permission Denied'));
				$this->redirect(array('action' => 'index', $survey_id));
			}			
			
			$success = true;
			
			$this->SurveyObject->create();
			$this->SurveyObject->set('survey_id', $survey_id);
			if (!$this->SurveyObject->save($this->request->data)) {
				$success = false;
				$this->Session->setFlash(__('The survey object could not be saved. Please, try again.'));
			}
			
			// TODO: Broken MVC - find a better way to access a helper from a controller
			$view = new View($this);
        	$questionFactory = $view->loadHelper('Question');
        	$questionHelper = $questionFactory->getHelper($this->request->data['SurveyObject']['type']);
        	$attributes = $questionHelper->getAttributes();
        	
        	$cnt = 0;
        	foreach ($attributes as $attribute)
        	{
        		$attObj = $this->SurveyObjectAttribute->create();
        		$attObj['SurveyObjectAttribute']['survey_object_id'] = $this->SurveyObject->getInsertId();
        		$attObj['SurveyObjectAttribute']['name'] = $cnt;
        		if ($this->SurveyObjectAttribute->save($attObj))
        		{
        			$this->Session->setFlash(__('The survey object has been saved'));
        		}
        		else
        		{
        			$success = false;
        			$this->Session->setFlash(__('The survey object could not be saved. Please, try again.'));
        		}
        		$cnt++;
        		
        	}
        	
			if ($success == true)
				$this->redirect(array('action' => 'index', $survey_id));
		}
		$this->set('survey_id', $survey_id);
		
	}
	
/**
* duplicate method
*
* @param string $id
* @return void
*/
	public function duplicate($id = null) {
		$this->SurveyObject->id = $id;
		if (!$this->SurveyObject->exists()) {
			throw new NotFoundException(__('Invalid survey object'));
		}
		
		// Permission check to ensure a user is allowed to duplicate an object in this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyObject = $this->SurveyObject->read(null, $id);
		$survey = $this->Survey->read(null, $surveyObject['SurveyObject']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('action' => 'index', $survey['Survey']['id']));
		}
		
		$this->SurveyObject->create();
		$surveyObject['SurveyObject']['id'] = null;
		$surveyObject['SurveyObject']['published'] = false;
		if (!$this->SurveyObject->save($surveyObject)) {
			$success = false;
			$this->Session->setFlash(__('The survey object could not be saved. Please, try again.'));
		}
			
		$attributes = $this->SurveyObjectAttribute->find('all', 
			array('conditions' => array('survey_object_id' => $id)));
		
        foreach ($attributes as $attribute)
        {
        	$attribute['SurveyObjectAttribute']['id'] = null;
        	$attribute['SurveyObjectAttribute']['survey_object_id'] = $this->SurveyObject->getLastInsertID();
        	$this->SurveyObjectAttribute->save($attribute);
        }
        	
		$this->redirect(array('action' => 'index', $survey['Survey']['id']));
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->SurveyObject->id = $id;
		if (!$this->SurveyObject->exists()) {
			throw new NotFoundException(__('Invalid survey object'));
		}
		
		// Permission check to ensure a user is allowed to add a survey to this group
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyObject = $this->SurveyObject->read(null, $id);
		$survey = $this->Survey->read(null, $surveyObject['SurveyObject']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('action' => 'index', $survey['Survey']['id']));
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SurveyObject->save($this->request->data)) {
				$this->Session->setFlash(__('The survey object has been saved'));
				$this->redirect(array('action' => 'index', $survey['Survey']['id']));
			} else {
				$this->Session->setFlash(__('The survey object could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->SurveyObject->read(null, $id);
		}
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null, $survey_id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->SurveyObject->id = $id;
		if (!$this->SurveyObject->exists()) {
			throw new NotFoundException(__('Invalid survey object'));
		}
		
		// Permission check to ensure a user is allowed to add a survey to this group
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyObject = $this->SurveyObject->read(null, $id);
		$survey = $this->Survey->read(null, $surveyObject['SurveyObject']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('action' => 'index', $survey['Survey']['id']));
		}
		
		if ($this->SurveyObject->delete()) {
			$this->Session->setFlash(__('Survey object deleted'));
			$this->redirect(array('action' => 'index', $survey['Survey']['id']));
		}
		$this->Session->setFlash(__('Survey object was not deleted'));
		$this->redirect(array('action' => 'index', $survey_id));
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

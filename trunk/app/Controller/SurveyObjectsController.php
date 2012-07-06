<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
App::uses('SurveyObject', 'Model');
/**
 * SurveyObjects Controller
 * @package Controller
 * @property SurveyObject $SurveyObject
 */
class SurveyObjectsController extends AppController {
	public $uses = array('SurveyObject', 'Survey', 'SurveyObjectAttribute', 'User');
	public $helpers = array('Form', 'Html', 'Js', 'Time', 'Question');

	/**
	 * index method.
	 * 
	 * Lists SurveyObjects for the survey with the given id.
	 * 
	 * @param int $survey_id The id of the survey to display the objects for
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
	 * add method.
	 *
	 * Adds a survey object if post is used.
	 * Otherwise allows entry of details to create a new survey object.
	 */
	public function add($survey_id = null, $page_id = 1) {
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
				
			$duplicateCheck = $this->SurveyObject->find('first',
					array('conditions' => array('SurveyObject.name' => $this->request->data['SurveyObject']['name'],
							'SurveyObject.survey_id' => $survey['Survey']['id'])));
			if ($duplicateCheck)
			{
				$success = false;
				$this->Session->setFlash("Survey Object with that name already exists");
			}
				
			if ($success)
			{
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
			}
			 
			if ($success == true)
			{
				// TODO: Redirect the researcher to the last page of objects after adding a new object. Note that this is harder than it sounds !
				$this->redirect(array('action' => 'index', $survey_id, 'page'=>$page_id));
			}
		}
		$this->set('survey_id', $survey_id);
		$this->set('page_id', $page_id);
	}

	/**
	 * duplicate method.
	 * 
	 * Duplicates the survey object with the given id.
	 * 
	 * @param int $id The id of the survey object to duplicate
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

		$success = true;
		$this->SurveyObject->create();
		$surveyObject['SurveyObject']['id'] = null;
		$surveyObject['SurveyObject']['published'] = false;
		$surveyObject['SurveyObject']['name'] .= " - duplicate";
		if (!$this->SurveyObject->save($surveyObject)) {
			$success = false;
			$this->Session->setFlash(__('The survey object could not be duplicated. Please, try again.'));
		}
			
		if ($success)
		{
			$attributes = $this->SurveyObjectAttribute->find('all',
					array('conditions' => array('survey_object_id' => $id)));
				
			foreach ($attributes as $attribute)
			{
				$attribute['SurveyObjectAttribute']['id'] = null;
				$attribute['SurveyObjectAttribute']['survey_object_id'] = $this->SurveyObject->getLastInsertID();

				if (!$this->SurveyObjectAttribute->save($attribute))
				{
					$success = false;
					$this->Session->setFlash(__('The survey object could not be duplicated. Please, try again.'));
				}
			}
		}

		if ($success)
			$this->redirect(array('action' => 'edit', $this->SurveyObject->getLastInsertID()));
		else
			$this->redirect(array('action' => 'index', $survey['Survey']['id']));
	}

	/**
	 * edit method.
	 *
	 * Updates the values of the survey object with the given id if post or put request used.
	 * Otherwise displays information about survey object to be edited.
	 *
	 * @param int $id The id of the survey object to edit
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
	 * delete method.
	 *
	 * Deletes the survey object with the given id if a post request is used.
	 *
	 * @param int $id The id of the survey object to delete
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
	 * preview method.
	 * 
	 * Opens a preview of the survey object in a new window.
	 *
	 * @param int $id The id of the survey object to preview
	 */
	public function preview($id = null) {
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

		$surveyObjectAttributes = $this->SurveyObjectAttribute->find('all',
				array('conditions' => array('survey_object_id' => $surveyObject['SurveyObject']['id'])));


		$this->set('survey', $survey);
		$this->set('surveyObject', $surveyObject);
		$this->set('surveyObjectAttributes', $surveyObjectAttributes);

	}


	/**
	 * isAuthorized method.
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

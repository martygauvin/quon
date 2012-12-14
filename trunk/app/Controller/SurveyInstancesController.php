<?php
/**
 * SurveyInstances Controller.
 * @package Controller
 */
App::uses('AppController', 'Controller');
App::uses('User', 'Model');

/**
 * SurveyInstances Controller
 * @property SurveyInstance $SurveyInstance
 */
class SurveyInstancesController extends AppController {
	/** The objects that are used.*/
	public $uses = array('SurveyInstance', 'Survey', 'SurveyInstanceObject', 'SurveyObject', 'SurveyResult', 'User');

	/**
	 * index method.
	 * 
	 * Lists instances of survey with given id.
	 * 
	 * @param int $survey_id The id of the survey to display the instances for
	 */
	public function index($survey_id = null) {
		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$survey = $this->Survey->read(null, $survey_id);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}

		$this->paginate = array('conditions' => array('Survey.id' => $survey_id));
		$this->SurveyInstance->recursive = 0;
		$this->set('surveyInstances', $this->paginate());
		$this->set('survey', $this->Survey->read(null, $survey_id));
	}

	/**
	 * add method.
	 * 
	 * Adds a survey instance if a post method is used.
	 * Otherwise displays details to allow entry of new survey instance.
	 * 
	 * @param int survey_id The id of the survey to add a new instance for
	 */
	public function add($survey_id = null) {
		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$survey = $this->Survey->read(null, $survey_id);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}

		if ($this->request->is('post')) {
			$this->SurveyInstance->create();
			if ($this->SurveyInstance->save($this->request->data)) {
				$surveyInstance = $this->SurveyInstance->read(null, $this->SurveyInstance->getLastInsertID());

				if ($survey['Survey']['live_instance'])
				{
					// Copy ordering from existing live instance
					$objects = $this->SurveyInstance->SurveyInstanceObject->find('all',
							array('conditions' => array('survey_instance_id' => $survey['Survey']['live_instance'])));

					foreach ($objects as $object)
					{
						$this->SurveyInstance->SurveyInstanceObject->create();
						$object['SurveyInstanceObject']['id'] = null;
						$object['SurveyInstanceObject']['survey_instance_id'] = $surveyInstance['SurveyInstance']['id'];
						$this->SurveyInstance->SurveyInstanceObject->save($object);
					}
				}

				$this->Session->setFlash(__('The survey instance has been saved'));
				$this->redirect(array('action' => 'index', $surveyInstance['SurveyInstance']['survey_id']));
			} else {
				$this->Session->setFlash(__('The survey instance could not be saved. Please, try again.'));
			}
		}
		$this->set('survey_id', $survey_id);
	}

	/**
	 * close method.
	 * 
	 * Closes the survey instance with the given id, stopping anybody from being able to take the survey.
	 *
	 * @param int $id The id of the survey instance to close
	 */
	public function close($id = null) {
		$this->SurveyInstance->id = $id;
		if (!$this->SurveyInstance->exists()) {
			throw new NotFoundException(__('Invalid survey instance'));
		}

		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyInstance = $this->SurveyInstance->read(null, $id);
		$survey = $this->Survey->read(null, $surveyInstance['SurveyInstance']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}

		$surveyInstance['SurveyInstance']['locked'] = true;
		$this->SurveyInstance->save($surveyInstance);

		$survey['Survey']['live_instance'] = NULL;
		$this->Survey->save($survey);

		$this->Session->setFlash(__('Survey Instance now closed'));
		$this->redirect(array('action' => 'index', $survey['Survey']['id']));
	}

	/**
	 * publish method.
	 * 
	 * Makes the survey instance with the given id the live survey instance that participants can complete.
	 * 
	 * @param int $id The id of the survey instance to publish
	 */
	public function publish($id = null) {
		$this->SurveyInstance->id = $id;
		if (!$this->SurveyInstance->exists()) {
			throw new NotFoundException(__('Invalid survey instance'));
		}

		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyInstance = $this->SurveyInstance->read(null, $id);
		$survey = $this->Survey->read(null, $surveyInstance['SurveyInstance']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}

		$surveyInstance['SurveyInstance']['locked'] = true;
		$this->SurveyInstance->save($surveyInstance);

		$instanceObjects = $this->SurveyInstance->SurveyInstanceObject->find('all',
				array('conditions' => array('SurveyInstanceObject.survey_instance_id' => $id)));

		foreach ($instanceObjects as $instanceObject)
		{
			$object = $this->SurveyObject->find('first',
					array('conditions' => array('SurveyObject.id' => $instanceObject['SurveyInstanceObject']['survey_object_id'])));
				
			$object['SurveyObject']['published'] = true;
			$this->SurveyObject->save($object);
		}

		$surveyInstance['Survey']['live_instance'] = $surveyInstance['SurveyInstance']['id'];
		$this->SurveyInstance->Survey->save($surveyInstance['Survey']);

		$this->Session->setFlash(__('Survey Instance now live'));
		$this->redirect(array('action' => 'index', $survey['Survey']['id']));
	}
	
	/**
	 * edit method.
	 * 
	 * Edits the survey instance with the given id.
	 *
	 * @param int $id The id of the survey instance to edit
	 */
	public function edit($id = null) {
		$this->SurveyInstance->id = $id;
		if (!$this->SurveyInstance->exists()) {
			throw new NotFoundException(__('Invalid survey instance'));
		}

		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyInstance = $this->SurveyInstance->read(null, $id);
		$survey = $this->Survey->read(null, $surveyInstance['SurveyInstance']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			$this->SurveyInstance->SurveyInstanceObject->deleteAll(array('SurveyInstanceObject.survey_instance_id' => $id));
			$cnt = 0;
			foreach ($this->request->data['SurveyInstanceObject']['survey_object_id'] as $surveyObject)
			{
				if ($surveyObject)
				{
					$cnt++;
					$this->SurveyInstance->SurveyInstanceObject->create();
					$data = array();
					$data['SurveyInstanceObject']['survey_instance_id'] = $id;
					$data['SurveyInstanceObject']['survey_object_id'] = $surveyObject;
					$data['SurveyInstanceObject']['order'] = $cnt;
					$this->SurveyInstance->SurveyInstanceObject->save($data);
				}
					
			}

			$this->Session->setFlash(__('The survey instance has been saved'));
		}

		$this->request->data = $this->SurveyInstance->read(null, $id);
		$this->set('surveyInstance', $this->SurveyInstance->read(null, $id));
			
		$surveyInstanceObjects = $this->SurveyInstance->SurveyInstanceObject->find('all',
				array('order' => 'SurveyInstanceObject.order',
				  'conditions' => 'SurveyInstanceObject.survey_instance_id = '.$id));
		$this->set(compact('surveyInstanceObjects'));

		$surveyObjects = $this->SurveyInstance->Survey->SurveyObject->find('list',
				array('conditions' => array('SurveyObject.survey_id' => $surveyInstance['SurveyInstance']['survey_id'])));
		$this->set(compact('surveyObjects'));

		$surveyInstanceObjectMax = $this->SurveyInstance->SurveyInstanceObject->find('first',
				array('fields' => 'max(SurveyInstanceObject.order) as morder',
						'conditions' => 'SurveyInstanceObject.survey_instance_id = '.$id));
		$this->set('surveyInstanceObjectMax', $surveyInstanceObjectMax);

	}

	/**
	 * view method.
	 * 
	 * Displays the survey instance with the given id.
	 * 
	 * @param int $id The id of the survey instance to display
	 */
	public function view($id = null) {
		$this->SurveyInstance->id = $id;
		if (!$this->SurveyInstance->exists()) {
			throw new NotFoundException(__('Invalid survey instance'));
		}

		// Permission check to ensure a user is allowed to view this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyInstance = $this->SurveyInstance->read(null, $id);
		$survey = $this->Survey->read(null, $surveyInstance['SurveyInstance']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}

		$this->request->data = $this->SurveyInstance->read(null, $id);
		$this->set('surveyInstance', $this->SurveyInstance->read(null, $id));
			
		$surveyInstanceObjects = $this->SurveyInstance->SurveyInstanceObject->find('all',
				array('order' => 'SurveyInstanceObject.order',
						'conditions' => 'SurveyInstanceObject.survey_instance_id = '.$id));
		$this->set(compact('surveyInstanceObjects'));

		$surveyObjects = $this->SurveyInstance->Survey->SurveyObject->find('list',
				array('conditions' => array('SurveyObject.survey_id' => $surveyInstance['SurveyInstance']['survey_id'])));
		$this->set(compact('surveyObjects'));

		$surveyInstanceObjectMax = $this->SurveyInstance->SurveyInstanceObject->find('first',
				array('fields' => 'max(SurveyInstanceObject.order) as morder',
						'conditions' => 'SurveyInstanceObject.survey_instance_id = '.$id));
		$this->set('surveyInstanceObjectMax', $surveyInstanceObjectMax);

	}

	/**
	 * delete method.
	 *
	 * Deletes the survey instance with the given id.
	 *
	 * @param int $id The id of the survey instance to delete
	 * @param int $survey_id The id of the survey the instance is an instance of
	 */
	public function delete($id = null, $survey_id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->SurveyInstance->id = $id;
		if (!$this->SurveyInstance->exists()) {
			throw new NotFoundException(__('Invalid survey instance'));
		}

		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyInstance = $this->SurveyInstance->read(null, $id);
		$survey = $this->Survey->read(null, $surveyInstance['SurveyInstance']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}

		if ($this->SurveyInstance->delete()) {
			$this->Session->setFlash(__('Survey instance deleted'));
			$this->redirect(array('action' => 'index', $survey_id));
		}
		$this->Session->setFlash(__('Survey instance was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

	/**
	 * preview method.
	 * 
	 * Previews the survey instance with the given id.
	 *
	 * @param int $id The id of the survey instance to preview
	 */
	public function preview($id = null) {
		$this->SurveyInstance->id = $id;
		if (!$this->SurveyInstance->exists()) {
			throw new NotFoundException(__('Invalid survey instance'));
		}

		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyInstance = $this->SurveyInstance->read(null, $id);
		$survey = $this->Survey->read(null, $surveyInstance['SurveyInstance']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}

		$this->SurveyResult->create();
		$data = array();
		$data['SurveyResult']['participant_id'] = 0;
		$data['SurveyResult']['survey_instance_id'] = $id;
		$data['SurveyResult']['date'] = date('Y-m-d h:i:s');
		$data['SurveyResult']['test'] = true;
		$this->SurveyResult->save($data);


		$firstObject = $this->SurveyInstanceObject->find('first',
				array('conditions' => array('survey_instance_id' => $id),
						'order' => 'SurveyInstanceObject.order'));
			
		$this->redirect(array('controller' => 'public', 'action' => 'question', $this->SurveyResult->getLastInsertID(), $firstObject['SurveyInstanceObject']['id']));

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

<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
/**
 * Participants Controller
 * @package Controller
 * @property Participant $Participant
 */
class ParticipantsController extends AppController {
	public $uses = array('Participant', 'Survey', 'UserGroup', 'User');

	/**
	 * index method.
	 * 
	 * Lists particpiants the user has access to.
	 */
	public function index() {
		$this->Participant->recursive = 0;
		// Only include participants in surveys this researcher has access to
		// TODO: This is a messy approach to sub queries. Need to review this across all controllers.
		$this->paginate = array(
				'conditions' => array('Survey.group_id IN (select User_Group.group_id from user_groups as User_Group where User_Group.user_id='.$this->Auth->user('id').')')
		);
		$this->set('participants', $this->paginate());
	}

	/**
	 * add method.
	 * 
	 * Adds a participant to the system
	 */
	public function add() {
		if ($this->request->is('post')) {
			// Check that the researcher has permission to add a participant to this survey
			$user = $this->User->read(null, $this->Auth->user('id'));
			$survey = $this->Survey->read(null, $this->request->data['Participant']['survey_id']);
			if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
			{
				$this->Session->setFlash(__('Permission Denied'));
				$this->redirect(array('action' => 'index'));
			}
				
			// Create the participant
			$this->Participant->create();
			$this->request->data['Participant']['password'] = AuthComponent::password($this->request->data['Participant']['password']);
				
			if ($this->Participant->save($this->request->data)) {
				$this->Session->setFlash(__('The participant has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The participant could not be saved. Please, try again.'));
			}
		}

		// Populate the survey options dropdown
		$surveys = $this->Participant->Survey->find('list', array(
				'conditions' => array('Survey.group_id IN (select User_Group.group_id from user_groups as User_Group where User_Group.user_id='.$this->Auth->user('id').')'))
		);
		$this->set(compact('surveys'));
	}

	/**
	 * edit method.
	 * 
	 * Edits a participant in the system.
	 *
	 * @param int $id The id of the participant to edit
	 */
	public function edit($id = null) {
		$this->Participant->id = $id;
		if (!$this->Participant->exists()) {
			throw new NotFoundException(__('Invalid participant'));
		}

		// Check that the researcher has permission to edit this participant
		$participant = $this->Participant->read(null, $id);
		$user = $this->User->read(null, $this->Auth->user('id'));
		$survey = $this->Survey->read(null, $participant['Participant']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('action' => 'index'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			// TODO: This is breaking participant authentication when the password isn't changed upon update
			$this->request->data['Participant']['password'] = AuthComponent::password($this->request->data['Participant']['password']);

			if ($this->Participant->save($this->request->data)) {
				$this->Session->setFlash(__('The participant has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The participant could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Participant->read(null, $id);
		}

		// Populate the survey options dropdown
		$surveys = $this->Participant->Survey->find('list', array(
				'conditions' => array('Survey.group_id IN (select User_Group.group_id from user_groups as User_Group where User_Group.user_id='.$this->Auth->user('id').')'))
		);
		$this->set(compact('surveys'));
	}

	/**
	 * delete method.
	 * 
	 * Deletes a participant from the system.
	 *
	 * @param int $id The id of the participant to delete.
	 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Participant->id = $id;
		if (!$this->Participant->exists()) {
			throw new NotFoundException(__('Invalid participant'));
		}

		//Check that the researcher has permission to delete this participant
		$participant = $this->Participant->read(null, $id);
		$user = $this->User->read(null, $this->Auth->user('id'));
		$survey = $this->Survey->read(null, $participant['Participant']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('action' => 'index'));
		}

		if ($this->Participant->delete()) {
			$this->Session->setFlash(__('Participant deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Participant was not deleted'));
		$this->redirect(array('action' => 'index'));
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

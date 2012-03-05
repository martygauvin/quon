<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
/**
 * SurveyObjectAttributes Controller
 *
 * @property SurveyObjectAttribute $SurveyObjectAttribute
 */
class SurveyObjectAttributesController extends AppController {
	public $uses = array('SurveyObjectAttribute', 'SurveyObject', 'User', 'Survey');
	public $helpers = array('Form', 'Html', 'Js', 'Time', 'Question');

/**
 * index method
 *
 * @return void
 */
	public function index($survey_object_id = null) {
		// Permission check to ensure a user is allowed view this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyObject = $this->SurveyObject->read(null, $survey_object_id);
		$survey = $this->Survey->read(null, $surveyObject['SurveyObject']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}
		
		$this->SurveyObjectAttribute->recursive = 0;
		$this->paginate = array('conditions' => array('SurveyObject.id' => $survey_object_id));
		$this->set('surveyObjectAttributes', $this->paginate());
		
		$this->set('surveyObject', $surveyObject);
	}


/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->SurveyObjectAttribute->id = $id;
		if (!$this->SurveyObjectAttribute->exists()) {
			throw new NotFoundException(__('Invalid survey object attribute'));
		}
		// Permission check to ensure a user is allowed view this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyObjectAttribute = $this->SurveyObjectAttribute->read(null, $id);
		$surveyObject = $this->SurveyObject->read(null, $surveyObjectAttribute['SurveyObjectAttribute']['survey_object_id']);
		$survey = $this->Survey->read(null, $surveyObject['SurveyObject']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SurveyObjectAttribute->save($this->request->data)) {
				$this->Session->setFlash(__('The survey object attribute has been saved'));
				$this->redirect(array('action' => 'index', $this->request->data['SurveyObjectAttribute']['survey_object_id']));
			} else {
				$this->Session->setFlash(__('The survey object attribute could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->SurveyObjectAttribute->read(null, $id);
			$this->set('surveyObject', $surveyObject);
		}
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
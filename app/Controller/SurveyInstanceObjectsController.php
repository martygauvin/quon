<?php
App::uses('AppController', 'Controller');
App::uses('User', 'Model');

/**
 * SurveyInstanceObjects Controller
 *
 * @property SurveyInstanceObject $SurveyInstanceObject
 */
class SurveyInstanceObjectsController extends AppController {
	
	public $uses = array('SurveyInstanceObject', 'SurveyInstance', 'Survey', 'User');

/**
 * move_up method
 *
 * @param string $id
 * @return void
 */
	public function move_up($id = null) {
		$this->SurveyInstanceObject->id = $id;
		if (!$this->SurveyInstanceObject->exists()) {
			throw new NotFoundException(__('Invalid survey instance object'));
		}
		
		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyInstanceObject = $this->SurveyInstanceObject->read(null, $id);
		$surveyInstance = $this->SurveyInstance->read(null, $surveyInstanceObject['SurveyInstanceObject']['survey_instance_id']);
		$survey = $this->Survey->read(null, $surveyInstance['SurveyInstance']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}
		
		$above = $this->SurveyInstanceObject->find('first', 
			array('conditions' => array('survey_instance_id' => $surveyInstanceObject['SurveyInstanceObject']['survey_instance_id'],
										'order < ' => $surveyInstanceObject['SurveyInstanceObject']['order']),
			      'order' => 'SurveyInstanceObject.order desc',
			      'limit' => 1));
		
		if ($above)
		{
			$top_order = $above['SurveyInstanceObject']['order'];
			$bottom_order = $surveyInstanceObject['SurveyInstanceObject']['order'];
			
			$above['SurveyInstanceObject']['order'] = $bottom_order;
			$surveyInstanceObject['SurveyInstanceObject']['order'] = $top_order;
			
			$this->SurveyInstanceObject->save($above);
			$this->SurveyInstanceObject->save($surveyInstanceObject);
		}
		
		$this->redirect(array('controller' => 'surveyInstances', 'action' => 'edit', $surveyInstance['SurveyInstance']['id']));
	}
	
/**
* move_down method
*
* @param string $id
* @return void
*/
	public function move_down($id = null) {
		$this->SurveyInstanceObject->id = $id;
		if (!$this->SurveyInstanceObject->exists()) {
			throw new NotFoundException(__('Invalid survey instance object'));
		}
	
		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyInstanceObject = $this->SurveyInstanceObject->read(null, $id);
		$surveyInstance = $this->SurveyInstance->read(null, $surveyInstanceObject['SurveyInstanceObject']['survey_instance_id']);
		$survey = $this->Survey->read(null, $surveyInstance['SurveyInstance']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}
	
		$below = $this->SurveyInstanceObject->find('first', 
			array('conditions' => array('survey_instance_id' => $surveyInstanceObject['SurveyInstanceObject']['survey_instance_id'],
										'order > ' => $surveyInstanceObject['SurveyInstanceObject']['order']),
			      'order' => 'SurveyInstanceObject.order',
			      'limit' => 1));
		
		if ($below)
		{
			$bottom_order = $below['SurveyInstanceObject']['order'];
			$top_order = $surveyInstanceObject['SurveyInstanceObject']['order'];
			
			$below['SurveyInstanceObject']['order'] = $top_order;
			$surveyInstanceObject['SurveyInstanceObject']['order'] = $bottom_order;
			
			$this->SurveyInstanceObject->save($below);
			$this->SurveyInstanceObject->save($surveyInstanceObject);
		}
	
		$this->redirect(array('controller' => 'surveyInstances', 'action' => 'edit', $surveyInstance['SurveyInstance']['id']));
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
		$this->SurveyInstanceObject->id = $id;
		if (!$this->SurveyInstanceObject->exists()) {
			throw new NotFoundException(__('Invalid survey instance object'));
		}
		
		// Permission check to ensure a user is allowed to edit this survey
		$user = $this->User->read(null, $this->Auth->user('id'));
		$surveyInstanceObject = $this->SurveyInstanceObject->read(null, $id);
		$surveyInstance = $this->SurveyInstance->read(null, $surveyInstanceObject['SurveyInstanceObject']['survey_instance_id']);
		$survey = $this->Survey->read(null, $surveyInstance['SurveyInstance']['survey_id']);
		if (!$this->SurveyAuthorisation->checkResearcherPermissionToSurvey($user, $survey))
		{
			$this->Session->setFlash(__('Permission Denied'));
			$this->redirect(array('controller' => 'surveys', 'action' => 'index'));
		}
		
		if ($this->SurveyInstanceObject->delete()) {
			$this->Session->setFlash(__('Survey instance object deleted'));
			$this->redirect(array('controller' => 'surveyInstances', 'action' => 'edit', $surveyInstance['SurveyInstance']['id']));
		}
		$this->Session->setFlash(__('Survey instance object was not deleted'));
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

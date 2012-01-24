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
	public $uses = array('SurveyObject', 'Survey', 'SurveyObjectAttribute');
	public $helpers = array('Form', 'Html', 'Js', 'Time', 'Question');

/**
 * index method
 *
 * @return void
 */
	public function index($survey_id = null) {
		// TODO: Permission check to ensure a user can view objects in this survey
		$this->SurveyObject->recursive = 0;
		// TODO: Only list survey objects that belong to this survey
		$this->set('surveyObjects', $this->paginate());
		$this->set('survey', $this->Survey->read(null, $survey_id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($survey_id = null) {
		// TODO: Permission check to ensure a user can add objects to this survey
		if ($this->request->is('post')) {
			$success = true;
			
			$this->SurveyObject->create();
			if (!$this->SurveyObject->save($this->request->data)) {
				$success = false;
				$this->Session->setFlash(__('The survey object could not be saved. Please, try again.'));
			}
			
			// TODO: Broken MVC - find a better way to access a helper from a controller
			$view = new View($this);
        	$question = $view->loadHelper('Question');
			
        	$attributes = $question->getAttributes($this->request->data['SurveyObject']['type']);
        	echo $attributes;
        	
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
		$surveys = $this->SurveyObject->Survey->find('list');
		$this->set(compact('surveys'));
		$this->set('survey_id', $survey_id);
		
	}
	

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		// TODO: Permission check to ensure a user can edit objects in this survey
		$this->SurveyObject->id = $id;
		if (!$this->SurveyObject->exists()) {
			throw new NotFoundException(__('Invalid survey object'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SurveyObject->save($this->request->data)) {
				$this->Session->setFlash(__('The survey object has been saved'));
				// TODO: Redirect is losing the session ID
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The survey object could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->SurveyObject->read(null, $id);
		}
		$surveys = $this->SurveyObject->Survey->find('list');
		$this->set(compact('surveys'));
		$this->set('survey_id', $this->request->data['SurveyObject']['survey_id']);
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function delete($id = null, $survey_id = null) {
		// TODO: Permission check to ensure a user can delete objects from this survey
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->SurveyObject->id = $id;
		if (!$this->SurveyObject->exists()) {
			throw new NotFoundException(__('Invalid survey object'));
		}
		if ($this->SurveyObject->delete()) {
			$this->Session->setFlash(__('Survey object deleted'));
			// TODO: Redirect is losing the session ID
			$this->redirect(array('action' => 'index'));
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

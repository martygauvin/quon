<?php
App::uses('AppModel', 'Model');
/**
 * Survey Model
 *
 * A set of questions that a participant can answer
 *
 * @package Model
 * @property Group $Group
 * @property User $User
 * @property Branding $Branding
 * @property Participant $Participant
 * @property SurveyInstance $SurveyInstance
 * @property SurveyObject $SurveyObject
 */
class Survey extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	
	// Statics
	const type_anonymous = 0;
	const type_identified = 1;
	const type_authenticated = 2;
	const type_autoidentified = 3;
	
	public $findMethods = array('accessible' =>  true);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Participant' => array(
			'className' => 'Participant',
			'foreignKey' => 'survey_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'SurveyInstance' => array(
			'className' => 'SurveyInstance',
			'foreignKey' => 'survey_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'SurveyObject' => array(
			'className' => 'SurveyObject',
			'foreignKey' => 'survey_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	/**
	 * Finds surveys accessible to the given user.
	 * @param unknown_type $state either 'before' or 'after'
	 * @param unknown_type $query The query to perform 'before'
	 * @param unknown_type $results The results to return 'after'
	 */
	protected function _findAccessible($state, $query, $results = array()) {
		if ($state == 'before') {
			if (isset($query['user'])) {
				$user = $this->User->findById($query['user']);
				$groupids = array();
				foreach($user['UserGroup'] as $usergroup) {
					$groupids[] = $usergroup['group_id'];
				}
				$query['conditions']['Group.id'] = $groupids;
			}
			return $query;
		}
		return $results;
	}
}

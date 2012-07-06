<?php
/**
 * A component to check researchers' permissions.
 * @package Controller.Component
 */
class SurveyAuthorisationComponent extends Component {

	/**
	 * Helper to see if a researcher is authorised to access this survey.
	 * @param Model.User $user The user to check for
	 * @param Model.Survey $survey The survey to check for
	 * @return boolean true if the given user has permission to the given survey, false otherwise
	 */
	function checkResearcherPermissionToSurvey($user, $survey) {
		// A user has permission if the survey belongs to one of their groups
		foreach ($user['UserGroup'] as $group)
		{
			if ($survey['Survey']['group_id'] == $group['group_id'])
				return true;
		}

		return false;
	}

	/**
	 * Helper to see if a researcher is authorised to access a group.
	 * @param Model.User $user The user to check for
	 * @param int $group_id The id of the group to check
	 * @return boolean true if the given user belongs to the group with the given id, false otherwise 
	 */
	function checkResearcherPermissionToGroup($user, $group_id) {

		foreach ($user['UserGroup'] as $group)
		{
			if ($group_id == $group['group_id'])
				return true;
		}

		return false;
	}
}
?>
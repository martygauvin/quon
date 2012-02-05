<?php
class SurveyAuthorisationComponent extends Component {
	
/**
 * helper to see if a researcher is authorised to access this survey
 *
 * @return void
 */
    function checkResearcherPermissionToSurvey($user, $survey) {
    	
        foreach ($user['UserGroup'] as $group)
        {
        	if ($survey['Survey']['group_id'] == $group['group_id'])
        		return true;
        }
        
        return false;
    }
    
    /**
    * helper to see if a researcher is authorised to access a group
    *
    * @return void
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
<?php
App::uses('AppHelper', 'View/Helper');

class DropdownQuestionHelper extends AppHelper {	
    
	protected $attributes = array(0 => 'Question Text',
    							  1 => 'Options',
								  2 => 'Include "Other" option'
	);

}
?>
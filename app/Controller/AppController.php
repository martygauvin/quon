<?php
/**
 * App Controller
 * @package Controller
 */
class AppController extends Controller {
	
	// TODO: Review all controllers for places where we can replace ->read with recursive
	// model indexing generating by the model relationships
	
	// TODO: Review all controller redirects and view links to ensure controller references do
	// not include underscores
	
	// Default authentication component configuration. This takes affect for all
	// application components unless overridden at the component-level
	public $components = array(
	    'Auth' => array(
	        'loginAction' => array(
	            'controller' => 'users',
	            'action' => 'login',
	            'plugin' => 'users'
	        ),
	        'logoutRedirect' => array(
	        	'controller' => 'users',
	        	'action' => 'login'
			),
	        'authError' => 'Authentication failure',
	        'authenticate' => array(
	        	'Form' => array('userModel' => 'User')
	        )
	    ),
	    'Session',
	    'SurveyAuthorisation'
	);	
	
	// Defaut authentication hook setup.
	function beforeFilter(){
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
		$this->Auth->loginRedirect = array('controller' => 'dashboard', 'action' => 'index');
		$this->Auth->authorize = 'Controller';
	}
	
	/**
	 * Utility function to handle file uploads.
	 * @param string $folder The path to save the file
	 * @param unknown_type $formdata The form data to use
	 * @param string $itemId An optional id for teh item
	 * @return string The result of the operation
	 */
	function uploadFiles($folder, $formdata, $itemId = null) {
		// setup dir names absolute and relative
		$folder_url = WWW_ROOT.$folder;
		$rel_url = $folder;
	
		// create the folder if it does not exist
		if(!is_dir($folder_url)) {
			mkdir($folder_url);
		}
	
		// if itemId is set create an item folder
		if($itemId) {
			// set new absolute folder
			$folder_url = WWW_ROOT.$folder.'/'.$itemId;
			// set new relative folder
			$rel_url = $folder.'/'.$itemId;
			// create directory
			if(!is_dir($folder_url)) {
				mkdir($folder_url);
			}
		}
	
		// list of permitted file types, this is only images but documents can be added
		$permitted = array('image/gif','image/jpeg','image/pjpeg','image/png','text/css', 'text/javascript', 'application/javascript', 'application/x-javascript');
	
		// replace spaces with underscores
		$filename = str_replace(' ', '_', $formdata['name']);
		// assume filetype is false
		$typeOK = false;
		// check filetype is ok
		foreach($permitted as $type) {
			if($type == $formdata['type']) {
				$typeOK = true;
				break;
			}
		}

		// if file type ok upload the file
		if($typeOK) {
			// switch based on error code
			switch($formdata['error']) {
				case 0:
					// check filename already exists
					if(!file_exists($folder_url.'/'.$filename)) {
						// create full filename
						$full_url = $folder_url.'/'.$filename;
						$url = $rel_url.'/'.$filename;
						// upload the file
						$success = move_uploaded_file($formdata['tmp_name'], $url);
					} else {
						// create unique filename and upload file
						ini_set('date.timezone', 'Europe/London');
						$now = date('Y-m-d-His');
						$full_url = $folder_url.'/'.$now.$filename;
						$url = $rel_url.'/'.$now.$filename;
						$success = move_uploaded_file($formdata['tmp_name'], $url);
					}
					// if upload was successful
					if($success) {
						// save the url of the file
						$result['urls'][] = $url;
					} else {
						$result['errors'][] = "Error uploaded $filename. Please try again.";
					}
					break;
				case 3:
					// an error occured
					$result['errors'][] = "Error uploading $filename. Please try again.";
					break;
				default:
					// an error occured
					$result['errors'][] = "System error uploading $filename. Contact webmaster.";
				break;
			}
		} elseif($formdata['error'] == 4) {
			// no file was selected for upload
			$result['nofiles'][] = "No file Selected";
		} else {
			// unacceptable file type
			$result['errors'][] = "$filename cannot be uploaded. Acceptable file types: gif, jpg, png, css, js.";
		}
		
		return $result;
	}
	
	/**
	* Utility method for providing flat access to persisted attributes
	* @param array of SQL results
	*
	* @return flat array of name/value pairs
	*/
	protected function flatten_attributes($attributes)
	{
		$flat_attributes = array();
	
		foreach ($attributes as $attribute)
		{
			$name = $attribute['SurveyAttribute']['name'];
			$value = $attribute['SurveyAttribute']['value'];
			$flat_attributes[$name] = $value;
		}
			
		return $flat_attributes;
	}
}
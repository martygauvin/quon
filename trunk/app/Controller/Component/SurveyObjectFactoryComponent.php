<?php
/**
 * A factory to access other survey object specific components.
 * @package Controller.Component
 */
class SurveyObjectFactoryComponent extends Component {
	private static $typeList = array(
									 0 => 'Text',
									 1  => 'RadioButton',
									 2  => 'Checkbox',
									 3  => 'Dropdown',
									 4  => 'RankOrder',
									 5  => 'LikertScale',
									 6  => 'Informational',
									 7  => 'Calendar',
//									 8  => 'Branch',
									 9  => 'ButtonOption',
//									 10 => 'Calculation',
									 11 => 'DistributionOfPoints',
//									 12 => 'Meta',
	);
	
	function getComponent($type) 
	{
		if (key_exists($type, self::$typeList))
		{
			$componentName = self::$typeList[$type];
			
			$controller = new Controller($this);
			
			// Load required components
			$controller->Components->load('PreviousAnswer');
			
			return $controller->Components->load($componentName);		
		}
		else
		{
			return false;
		}
	}
}
?>
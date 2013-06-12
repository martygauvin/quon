<?php
/**
 * A component to assist with rendering the DistributionOfPoints object type.
 * @package Controller.Component
 */
class DistributionOfPointsComponent extends PreviousAnswerComponent {
		function augment($attributes, $surveyObjectInstance, $surveyInstance, $surveyResult, $controller) {
			$positions = array(0, 1);
			return parent::augment($attributes, $surveyObjectInstance, $surveyInstance, $surveyResult, $controller, $positions);
		}
}
?>
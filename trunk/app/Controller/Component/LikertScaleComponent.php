<?php
/**
 * A component to assist with rendering the LikertScale object type.
 * @package Controller.Component
 */
class LikertScaleComponent extends PreviousAnswerComponent {
		function augment($attributes, $surveyObjectInstance, $surveyInstance, $surveyResult, $controller) {
			$positions = array(0, 1, 2);
			return parent::augment($attributes, $surveyObjectInstance, $surveyInstance, $surveyResult, $controller, $positions);
		}
}
?>
# Introduction #

This page specifies information relevant to a developer looking to understand or enhance the Quon system. It includes an overview of the system architecture, the frameworks and methodologies being used, and steps required to further development of the system. This documentation should be read in conjunction with the UserManual.


# Details #

## System Architecture ##

The system follows a typical Model-View-Controller (MVC) architecture, with the model in the `app/Model` directory, the views in the `app/View` directory, and the controllers in the `app/Controller` directory.

![http://quon.googlecode.com/svn/wiki/images/MVC-Process.png](http://quon.googlecode.com/svn/wiki/images/MVC-Process.png)

## Model Details ##

The system is based on the idea of survey objects, each of which have certain attributes. A survey object can be thought of as a question, branch, or calculation. Survey object attributes specify details such as the question text to display, where to branch based on particular conditions, and what calculations to perform.

Survey objects are collected into survey instances, which specify a particular series of objects that should be run through for that instance. Each instance is a part of a survey that contains information detailing the purpose of the survey and who has access to it.

Surveys can be anonymous (meaning that anybody can start the survey without any authentication), identified (meaning that a username is required to start the survey), auto-identified (meaning that a new username is created when entered for a survey, and the survey then continues as an identified survey), or authenticated (where a username and password is required to take the survey).

Surveys are administered by research users, who are in user groups. Administrator users manage users and groups. Each survey has a creator and belongs to a particular group. Any member of the group can see the survey.

Administrator users are also responsible for system-level configurations.

This section explains the purpose of each component of the model (stored as tables in a relational database).

### configurations ###

This table defines system-level configuration options using name/value pairs. This includes information such as the name of the institution the installation is a part of, the URL of Mint and export location for ReDBoX, and whether the TinyMCE image manager should be supported.

| **Column** | **Type** | **Null** | **Default** | **Description** |
|:-----------|:---------|:---------|:------------|:----------------|
| id         | int(11)  | No       | None        | The primary key |
| name       | varchar(50) | No       | None        | A key describing the purpose of the configuration |
| value      | varchar(500) | No       | None        | The value of the configuration |

### users ###

This table defines researchers and administrators who should be able to log into the system to manage surveys or system settings and users and groups (respectively).

| **Column** | **Type** | **Null** | **Default** | **Description** |
|:-----------|:---------|:---------|:------------|:----------------|
| id         | int(11)  | No       | None        | The primary key |
| type       | int(11)  | No       | None        | The user type (i.e. whether a researcher or an admin) |
| username   | varchar(50) | No       | None        | The user's log in name |
| password   | varchar(200) | No       | None        | The user's password (salted and hashed) |
| given\_name | varchar(50) | No       | None        | The user's given name |
| surname    | varchar(50) | No       | None        | The user's surname |
| email      | varchar(500) | No       | None        | The user's email address |
| researcher\_id | varchar(20) | Yes      | NULL        | The user's external id (for use in metadata) |

### groups ###

This table defines groups that users can belong to.

| **Column** | **Type** | **Null** | **Default** | **Description** |
|:-----------|:---------|:---------|:------------|:----------------|
| id         | int(11)  | No       | None        | The primary key |
| name       | varchar(50) | No       | None        | The name of the group |

### user\_groups ###

This table links users to groups.

| **Column** | **Type** | **Null** | **Default** | **Description** |
|:-----------|:---------|:---------|:------------|:----------------|
| id         | int(11)  | No       | None        | The primary key |
| user\_id   | int(11)  | No       | None        | The id of the user |
| group\_id  | int(11)	 | No       | None        | The id of the group |

### surveys ###

This table contains information about surveys created by users. This includes the survey name and type, as well as the user who created the survey, and which group the survey should be accessible for.

| **Column** | **Type** | **Null** | **Default** | **Description** |
|:-----------|:---------|:---------|:------------|:----------------|
| id         | int(11)  | No       | None        | The primary key |
| group\_id  | int(11)	 | No       | None        | The group the survey belongs to |
| name       | varchar(100) | No       | None        | The long name of the survey |
| short\_name | varchar(20) | No       | None        | The short name of the survey (used in URLs) |
| type       | int(11)  | No       | None        | The type of the survey (i.e., whether anonymous, authenticated, or authorised) |
| multiple\_run | tinyint(1) | No       | None        | Whether a participant can complete the survey multiple times |
| user\_id   | int(11)  | No       | None        | The id of the owner of the survey |
| live\_instance | int(11)  | Yes      | NULL        | The id of the live instance |
| locked\_edit | tinyint(1) | Yes      | NULL        | Whether the survey can be edited |

### survey\_metadatas ###

This table contains information required for export to ReDBoX, such as a description of the survey and access rights for the survey data.

| **Column** | **Type** | **Null** | **Default** | **Description** |
|:-----------|:---------|:---------|:------------|:----------------|
| id         | int(11)  | No       | None        |  The primary key |
| survey\_id | int(11)  | No       | None        | The link to the survey |
| description | text     | Yes      | NULL        | The description of the data collected |
| keywords   | text     | Yes      | NULL        | The keywords for the data collected |
| fields\_of\_research | varchar(10) | Yes      | NULL        | The FOR codes for the data collected |
| socio-economic\_objective | varchar(10) | Yes      | NULL        | The SEO codes for the data collected |
| retention\_period | text     | Yes      | NULL        | The retention period for the data collected |
| access\_rights | text     | Yes      | NULL        | The access rights for the data collected |

### survey\_attributes ###

This table contains any survey-level attributes. Used for holding details such as the logo and customised CSS to use for a survey.

| **Column** | **Type** | **Null** | **Default** | **Description** |
|:-----------|:---------|:---------|:------------|:----------------|
| id         | int(11)  | No       | None        | The primary key |
| survey\_id | int(11)  | No       | None        | The link to the survey |
| name       | varchar(20) | No       | None        | The name of the attribute (e.g. whether it's CSS or logo) |
| value      | text     | Yes      | NULL        | The value of the attribute (e.g. a file path) |

### participants ###

This table contains information about participants for various surveys, allowing identification and authentication of participants.

| **Column** | **Type** | **Null** | **Default** | **Description** |
|:-----------|:---------|:---------|:------------|:----------------|
| id         | int(11)  | No       | None        | The primary key |
| survey\_id | int(11)  | No       | None        | The survey the participant belongs to |
| given\_name | varchar(50) | No       | None        | The first name of the participant |
| surname    | varchar(50) | No       | None        | The surname of the participant |
| dob        | date     | Yes      | NULL        | The date of birth of the participant |
| username   | varchar(50) | No       | None        | The username for the participant |
| password   | varchar(200) | Yes      | NULL        | The (salted and hashed) password for the participant |
| email      | varchar(500) | Yes      | NULL        | The email address for the participant |

### survey\_instances ###

This table contains a name for different versions of a survey. It is used to create different versions of the survey, but still be able to determine where results come from.

| **Column** | **Type** | **Null** | **Default** | **Description** |
|:-----------|:---------|:---------|:------------|:----------------|
| id         | int(11)  | No       | None        | The primary key |
| survey\_id | int(11)  | No       | None        | The link to the survey |
| name       | varchar(50) | No       | None        | The name of the instance |
| locked     | int(1)   | Yes      | 0           | Whether the instance can be edited (0) or not (1) |

### survey\_objects ###

This table gives the name and type of each item in the survey (i.e. each question, branch, etc.).

| **Column** | **Type** | **Null** | **Default** | **Description** |
|:-----------|:---------|:---------|:------------|:----------------|
| id         | int(11)  | No       | None        | The primary key |
| survey\_id | int(11)  | No       | None        | The link to the survey |
| name       | varchar(50) | No       | None        | The name of the object |
| type       | int(11)  | No       | None        | The type of the object (types defined in http://code.google.com/p/quon/source/browse/trunk/app/View/Helper/QuestionHelper.php) |
| published  | tinyint(1) | No       | None        | Whether the object has been published (and thus cannot be edited) |

### survey\_object\_attributes ###

This table gives name/value pairs of attributes for survey objects. This is used, for example, to specify question text, or which options should be available for a particular question.

| **Column** | **Type** | **Null** | **Default** | **Description** |
|:-----------|:---------|:---------|:------------|:----------------|
| id         | int(11)  | No       | None        | The primary key |
| survey\_object\_id | int(11)  | No       | None        | The link to the survey object |
| name       | varchar(20) | No       | None        | The name of the attribute |
| value      | text     | Yes      | NULL        | The value of the attribute |

### survey\_instance\_objects ###

This table specifies which objects belong to a particular survey instance.

| **Column** | **Type** | **Null** | **Default** | **Description** |
|:-----------|:---------|:---------|:------------|:----------------|
| id         | int(11)  | No       | None        | The primary key |
| survey\_instance\_id | int(11)  | No       | None        | The link to the survey instance |
| survey\_object\_id | int(11)  | No       | None        | The link to the survey object |
| order      | int(11)  | No       | None        | The order this object should appear in the instance |

### survey\_results ###

This table specifies which participant has attempted a survey, and when that attempt occurred.

| **Column** | **Type** | **Null** | **Default** | **Description** |
|:-----------|:---------|:---------|:------------|:----------------|
| id         | int(11)  | No       | None        | The primary key |
| survey\_instance\_id | int(11)  | No       | None        | The link to the survey |
| date       | date     | No       | None        | The date of these results |
| participant\_id | int(11)  | No       | None        | The participant completing the survey |
| test       | tinyint(1) | No       | None        | Whether this is a test run or real data |
| completed  | tinyint(1) | No       | None        | Whether the survey has been completed or is still in progress |
| time\_spent | int(11)  | No       | None        | The time spent on the survey |
| pause\_count | int(11)  | Yes      | 0           | The number of times the survey has been paused |
| restart\_count | int(11)  | Yes      | 0           | The number of times the survey has been restarted |

### survey\_result\_answers ###

This table specifies the actual answers a participant has saved for a survey.

| **Column** | **Type** | **Null** | **Default** | **Description** |
|:-----------|:---------|:---------|:------------|:----------------|
| id         | int(11)  | No       | None        | The primary key |
| survey\_result\_id | int(11)  | No       | None        | The link to the survey result |
| survey\_instance\_object\_id | int(11)  | Yes      | NULL        | The instance object this is the answer for |
| answer     | text     | Yes      | NULL        | The answer entered by the participant |
| time\_spent | int(11)  | Yes      | NULL        | The time the participant spent on the question |

## Frameworks and Methodologies ##

Quon is based on the [CakePHP framework](http://cakephp.org), and installation essentially follows the [requirements of CakePHP](http://book.cakephp.org/2.0/en/installation.html).

QuON supports the commercial ImageManager plugin available from http://www.tinymce.com. This plugin allows the uploading of images and other media for insertion into survey pages.

## Adding Extensions ##

The main extension point for QuON is in the `app/View/Helper` directory, where various `QuestionHelper`s are defined. By modifying and extending `QuestionHelper`, new question types can be added to the system.

### Existing Question Types ###

This section describes the question types currently built into the system. For a more comprehensive overview, please see the user manual.

#### Branch ####

A branch jumps to a different survey object based on a condition. It can specify where to jump when the condition is true, and/or where to jump when the condition is false.

#### ButtonOption ####

A button option displays a series of buttons, and pressing one of the buttons moves to the next question.

Researchers can see the following attributes for button option questions:

![http://quon.googlecode.com/svn/wiki/images/ResearcherButtonOption.png](http://quon.googlecode.com/svn/wiki/images/ResearcherButtonOption.png)

Participants can see the following for button option questions:

![http://quon.googlecode.com/svn/wiki/images/UserButtonOption.png](http://quon.googlecode.com/svn/wiki/images/UserButtonOption.png)

#### Calculation ####

A calculation performs a numeric calculation based on previous answers.

#### Calendar ####

A calendar displays a calendar to allow a participant to select a date.

Researchers can see the following attributes for calendar questions:

![http://quon.googlecode.com/svn/wiki/images/ResearcherCalendar.png](http://quon.googlecode.com/svn/wiki/images/ResearcherCalendar.png)

Participants can see the following for calendar questions:

![http://quon.googlecode.com/svn/wiki/images/UserCalendar.png](http://quon.googlecode.com/svn/wiki/images/UserCalendar.png)

#### Checkbox ####

A checkbox displays a series of check boxes, allowing a participant to choose more than one option.

Researchers can see the following attributes for checkbox questions:

![http://quon.googlecode.com/svn/wiki/images/ResearcherCheckbox.png](http://quon.googlecode.com/svn/wiki/images/ResearcherCheckbox.png)

#### Dropdown ####

A dropdown displays a drop-down list of options, allowing a participant to choose one.

#### Informational ####

An informational displays information to a participant, until "next" is selected.

Researchers can see the following attributes for informational questions:

![http://quon.googlecode.com/svn/wiki/images/ResearcherInformational.png](http://quon.googlecode.com/svn/wiki/images/ResearcherInformational.png)

#### LikertScale ####

A LikertScale displays a series of Likert items to a participant.

Researchers can see the following attributes for Likert scale questions:

![http://quon.googlecode.com/svn/wiki/images/ResearcherLikertScale.png](http://quon.googlecode.com/svn/wiki/images/ResearcherLikertScale.png)

Participants can see the following for Likert scale questions:

![http://quon.googlecode.com/svn/wiki/images/UserLikertScale.png](http://quon.googlecode.com/svn/wiki/images/UserLikertScale.png)

#### RadioButton ####

A RadioButton display a series of radio buttons to a participant, allowing a single option to be selected.

Researchers can see the following attributes for radio button questions:

![http://quon.googlecode.com/svn/wiki/images/ResearcherRadioButton.png](http://quon.googlecode.com/svn/wiki/images/ResearcherRadioButton.png)

Participants can see the following for radio button questions:

![http://quon.googlecode.com/svn/wiki/images/UserRadioButton.png](http://quon.googlecode.com/svn/wiki/images/UserRadioButton.png)

#### RankOrder ####

A RankOrder allows a participant to rank options in an order.

Researchers can see the following attributes for rank order questions:

![http://quon.googlecode.com/svn/wiki/images/ResearcherRankOrder.png](http://quon.googlecode.com/svn/wiki/images/ResearcherRankOrder.png)

Participants can see the following for rank order questions:

![http://quon.googlecode.com/svn/wiki/images/UserRankOrder.png](http://quon.googlecode.com/svn/wiki/images/UserRankOrder.png)

#### Text ####

A text allows a participant to enter a textual response.

Researchers can see the following attributes for text questions:

![http://quon.googlecode.com/svn/wiki/images/ResearcherText.png](http://quon.googlecode.com/svn/wiki/images/ResearcherText.png)

### Adding New Question Types ###

To create a new question type called `Foo`, the following procedure should be followed (replace `Foo` with required type otherwise):

1. Edit `QuestionHelper`, adding a new value to `$typeList`. e.g.add `11 => 'Foo'`

2. Create a new file in `app/View/Helper` called `FooQuestionHelper.php`

3. Insert the following in `FooQuestionHelper.php`:
```
<?php
App::uses('AppHelper', 'View/Helper');

/**
 * A QuestionHelper that handles questions of type Foo
 */
class FooQuestionHelper extends QuestionHelper {	
	
	protected $attributes = array(0 => array('name' => 'Question Text', 'help' => 'Text to display when asking the user this question', 'type' => 'html')
// any other required attributes. Name is the name of the attribute. Help gives information that will be displayed when a researcher is editing the value. Type specifies whether the TinyMCE editor should be used for editing the attribute.
	);
	
	/**
	 * Specifies how question type Foo should be displayed.
	 * @param $form The form to use
	 * @param $attributes The values of the question attributes to use
	 * @param $previousAnswer The answer to display
	 * @param $show_next whether next should be shown
	 */
	function renderQuestion($form, $attributes, $previousAnswer, &$show_next)
	{	
		echo "Question: ".$attributes[0]."<br/><br/>"
		// TODO: Specify how question should be displayed 
	}

	/**
	 * Converts the given answer to a form storable in the database.
	 * @param $data The data to convert
	 * @param $attributes The value of attributes to use
	 * @return The answer to store in the database
	 */
	function serialiseAnswer($data, $attributes)
	{
		// TODO: Process answer as required
		return $data['Public']['answer'];
	}
	
	/**
	 * Determines if the given answer is valid.
	 * @param $data The data to check
	 * @param $attributes The value of the attributes to use
	 * @param &$error The error to display
	 * @return false if there is an error in the answer, true otherwise
	 */
	function validateAnswer($data, $attributes, &$error)
	{
		// TODO: Enter in validation logic
		return true;
	}
}
?>
```

4. Implement methods in `FooQuestionHelper.php` to ensure the required functionality.

For more examples of how to specify question types, look at the QuestionHelpers in the [QuON source repository](http://code.google.com/p/quon/source/browse/trunk/app/View/Helper/)
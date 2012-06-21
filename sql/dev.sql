-- Development data for use in QuON
-- All users have password the same as their username

UPDATE `configurations` SET `value` = 'University of Example' WHERE `name` = 'Institution';
UPDATE `configurations` SET `value` = '' WHERE `name` = 'Tiny MCE ImageManager';

INSERT INTO `users`(`id`,`type`,`username`,`password`) VALUES
	(2,1,'researcher','d01f5ed3c2173c7f691eff9294dac1c3d382983f'),
	(3,1,'researcher2','fc422153afa52bee20a48716c4f8d73c8b043bf7'),
	(4,1,'researcher3','511b1969f7036cd75bda744be60e328c2cceac10');

INSERT INTO `groups`(`id`,`name`) VALUES
	(2,'Other Group');

INSERT INTO `user_groups`(`user_id`,`group_id`) VALUES
	(2,1),
	(2,2),
	(3,1),
	(4,2);

INSERT INTO `surveys`(`id`,`group_id`,`name`,`short_name`,`type`,`multiple_run`,`user_id`) VALUES
	(1,1,'default group survey','dgs',0,0,1),
	(2,2,'other group survey','ogs',1,0,1);

INSERT INTO `participants`(`survey_id`,`given_name`,`surname`,`dob`,`username`,`password`,`email`) VALUES
	(2,'Test','Participant','2000-01-01','Participant2000','3a8db821405741db02734c052a363edc979a600e','participant2000@example.com');
	
INSERT INTO `survey_metadatas`(`survey_id`,`description`,`access_rights`) VALUES
	(1,'Simple survey','Public domain');

INSERT INTO `survey_instances`(`id`,`survey_id`,`name`,`locked`) VALUES
	(1,1,'1.0',1),
	(2,1,'Second',0),
	(3,2,'1.0',0);
	
INSERT INTO `survey_objects`(`id`,`survey_id`,`name`,`type`,`published`) VALUES
	(1,1,'text',0,1),
	(2,1,'radio',1,1),
	(3,1,'check',2,1),
	(4,1,'drop',3,1),
	(5,1,'rank',4,1),
	(6,1,'likert',5,1),
	(7,1,'info',6,1),
	(8,1,'calendar',7,1),
	(9,1,'button',9,1);

INSERT INTO `survey_object_attributes` (`id`, `survey_object_id`, `name`, `value`) VALUES
	(1,1,'0','text'),
	(2,1,'1',''),
	(3,1,'2',''),
	(4,1,'3',''),
	(5,2,'0','radio'),
	(6,2,'1','yes|no|maybe'),
	(7,2,'2',''),
	(8,2,'3','yes'),
	(9,3,'0','check'),
	(10,3,'1','First|Second|Third'),
	(11,3,'2','1'),
	(12,3,'3','2'),
	(13,3,'4',''),
	(14,3,'5',''),
	(15,4,'0','drop'),
	(16,4,'1','First|Second'),
	(17,4,'2',''),
	(18,5,'0','rank'),
	(19,5,'1','First|Second|Third'),
	(20,5,'2',''),
	(21,5,'3',''),
	(22,5,'4',''),
	(23,5,'5',''),
	(24,6,'0','Likert'),
	(25,6,'1','Strongly disagree|Disagree|Neither agree nor disagree|Agree|Strongly agree'),
	(26,6,'2','Item 1|Item 2|Item 3|Item 4'),
	(27,6,'3','yes'),
	(28,7,'0','<p>info</p>'),
	(29,8,'0','calendar'),
	(30,8,'1','MM yy'),
	(31,8,'2','value'),
	(32,8,'3','2001-07-09'),
	(33,8,'4','2010-07-09'),
	(34,8,'5',''),
	(35,9,'0','button'),
	(36,9,'1','First|Second'),
	(37,9,'2','');
		
INSERT INTO `survey_instance_objects`(`survey_instance_id`,`survey_object_id`,`order`) VALUES
	(1,1,1),
	(1,2,2),
	(1,3,3),
	(1,4,4),
	(1,5,5),
	(1,6,6),
	(1,7,7),
	(1,8,8),
	(1,9,9),
	(2,9,1),
	(2,8,2),
	(2,7,3),
	(2,6,4),
	(2,5,5),
	(2,4,6),
	(2,3,7),
	(2,2,8),
	(2,1,9);
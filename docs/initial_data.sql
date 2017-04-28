USE project_db;

INSERT INTO `roles` (`id`,`name`,`description`) VALUES (1,'admin','System administrator');
INSERT INTO `roles` (`id`,`name`,`description`) VALUES (2,'agent','Lecture privileges over all users');
INSERT INTO `roles` (`id`,`name`,`description`) VALUES (3,'customer','Login and edit personal info');

INSERT INTO `users` (`id`,`name`,`email`,`password`,`phone_number`,`created`,`modified`,`active`) VALUES (1,'Juan Manuel Mendez','juan.mendez.sanchez@hotmail.com','$2a$06$PF9zekPYHpGosbjvEZLe3eyL4MJOEBxOsCBjNqbqsw.I5GFPMtgPi','1234','2017-04-20 22:50:59','2017-04-21 20:06:49',1);
INSERT INTO `users` (`id`,`name`,`email`,`password`,`phone_number`,`created`,`modified`,`active`) VALUES (4,'Juan Manuel Mendez','juandx1@hotmail.com','$2y$10$qOfDE0e/G6lp4yFCJK12UeA9zP2o4ohq1VTWEGMo1Dl7zHVh/qYR2','3023724796','2017-04-21 19:26:38','2017-04-22 23:15:36',1);
INSERT INTO `users` (`id`,`name`,`email`,`password`,`phone_number`,`created`,`modified`,`active`) VALUES (6,'Juan Manuel Mendez','juandx1@gmail.com','$2a$06$WbD8kJ.YR8oKRfawIddTNOwpF4UjsZhmNx34RYsEwqCglu6XvgubO','23','2017-04-23 17:48:22','2017-04-23 17:48:22',1);

INSERT INTO `users_roles` (`user_id`,`role_id`) VALUES (1,1);
INSERT INTO `users_roles` (`user_id`,`role_id`) VALUES (4,2);
INSERT INTO `users_roles` (`user_id`,`role_id`) VALUES (6,3);

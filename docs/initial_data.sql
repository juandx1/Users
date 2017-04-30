USE project_db;
INSERT INTO `roles` (`id`,`name`,`description`) VALUES (1,'admin','System administrator');
INSERT INTO `roles` (`id`,`name`,`description`) VALUES (2,'agent','Lecture privileges over all users');
INSERT INTO `roles` (`id`,`name`,`description`) VALUES (3,'customer','Login and edit personal info');

/**
La contraseña del usuario administrador es juandx1
*/
INSERT INTO `users` (`id`,`name`,`email`,`password`,`phone_number`,`created`,`modified`,`active`) VALUES (1,'Juan Manuel Mendez','juan.mendez.sanchez@hotmail.com','$2a$06$PF9zekPYHpGosbjvEZLe3eyL4MJOEBxOsCBjNqbqsw.I5GFPMtgPi','1234','2017-04-20 22:50:59','2017-04-21 20:06:49',1);

INSERT INTO `users_roles` (`user_id`,`role_id`) VALUES (1,1);

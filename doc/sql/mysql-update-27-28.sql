ALTER TABLE  `users` CHANGE  `canChangePwd`  `canChangePwd` TINYINT( 1 ) NOT NULL DEFAULT  '1';
UPDATE `config` set `value` = '28' where `name` = 'version';
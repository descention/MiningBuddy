/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/doc/sql/ancient/mysql-update-8-9.txt,v 1.1 2007/06/24 14:31:49 mining Exp $
 *
 * Copyright (c) 2005, 2006, 2007 Christian Reiss.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms,
 * with or without modification, are permitted provided
 * that the following conditions are met:
 *
 * - Redistributions of source code must retain the above copyright notice,
 *   this list of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright
 *   notice, this list of conditions and the following disclaimer in the
 *   documentation and/or other materials provided with the distribution.
 *
 *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 *  "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 *  LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 *  FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 *  OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 *  SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
 *  TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA,
 *  OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
 *  OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 *  NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
 
 INSERT INTO config (name, value) VALUES ("divisions", "false");
 DROP TABLE IF EXISTS activations;
 
 ALTER TABLE `users` DROP column title;
 ALTER TABLE `users` ADD `division` int(3);
 ALTER TABLE `users` ADD `lastDivChange` int(15);
 ALTER TABLE `users` ADD `canManageDivisions` smallint(1) NOT NULL DEFAULT 0;
 UPDATE users SET division = NULL;
 
 DROP TABLE IF EXISTS `divisions`;
 CREATE TABLE `divisions` (
   `id` int(5) NOT NULL auto_increment,
   `sdescr` varchar(10) NOT NULL,
   `descr` blob,
   `minRank` int(5) NOT NULL,
   `maxMember` int(3) NOT NULL,
   PRIMARY KEY  (`id`),
   UNIQUE KEY `id` (`id`)
 ) TYPE=MyISAM;
 
 ALTER TABLE `users` ADD `canDeleteEvents` smallint(1);
 
 UPDATE config SET value='9' WHERE name='version';
  
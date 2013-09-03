/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/doc/sql/mysql-update-14-15.txt,v 1.5 2008/09/08 09:04:17 mining Exp $
 *
 * Copyright (c) 2005-2008 Christian Reiss.
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

DROP TABLE IF EXISTS `lotto_drawings`;
CREATE TABLE IF NOT EXISTS `lotto_drawings` (
	`id` int(5) NOT NULL auto_increment,
	`group` int(5) NOT NULL,
	`starter` int(5) NOT NULL,
	`starttime` int(16) NOT NULL,
	`closetime` int(16) NOT NULL,
	`closed` int(16) NOT NULL,
	`ticket_cost` int(30) NOT NULL DEFAULT 1000000,
	`prize_isMoney` smallint(1) NOT NULL DEFAULT 1,
	`prize` int(30) NOT NULL,
	`winning_ticket` int(5) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `lotto_groups`;
CREATE TABLE IF NOT EXISTS `lotto_groups` (
	`id` int(5) NOT NULL auto_increment,
	`name` varchar(80) NOT NULL,
	`is_jackpot` smallint(1) NOT NULL DEFAULT 1,
	`jackpot_amount` int(30) NOT NULL DEFAULT 0,
	PRIMARY KEY  (`id`),
 	KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `lotto_tickets`;
CREATE TABLE IF NOT EXISTS `lotto_tickets` (
	`id` int(5) NOT NULL auto_increment,
	`drawing` int(5) NOT NULL,
	`buytime` int(5) NOT NULL,
	`owner` int(5) NOT NULL,
	PRIMARY KEY  (`id`),
	KEY `drawing` (`drawing`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `users` ADD `lotto_isAdmin` smallint(1) DEFAULT 0;
ALTER TABLE `users` ADD `lotto_canPlayLotto` smallint(1) DEFAULT 1;

UPDATE config SET value='15' WHERE name='version';
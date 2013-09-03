/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/doc/sql/ancient/mysql-update-3-4.txt,v 1.1 2007/06/10 13:45:49 mining Exp $
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
 */;
 
ALTER TABLE `images` ADD `type` varchar(20);
ALTER TABLE `images` ADD `textColor` varchar(9);
ALTER TABLE `images` ADD `bgColor` varchar(9);
ALTER TABLE `images` ADD `width` int(5);
ALTER TABLE `images` ADD `height` int(5);
ALTER TABLE `joinups` ADD `shiptype` int(2);
ALTER TABLE `users` ADD `isLottoOfficial` tinyint(1);
ALTER TABLE `users` ADD `canPlayLotto` tinyint(1);
ALTER TABLE `users` ADD `isAccountant` tinyint(1);
ALTER TABLE `users` ADD `lottoCredit` int(5);
ALTER TABLE `users` ADD `lottoCreditsSpent` int(5);
ALTER TABLE `runs` ADD `isLocked` tinyint(1);
ALTER TABLE `joinups` ADD `status` int(1) not null default 0;
ALTER TABLE `joinups` ADD `remover` int(5) default NULL;

CREATE TABLE `lotteryTickets` (
  `id` int(5) NOT NULL auto_increment,
  `ticket` int(5) NOT NULL,
  `drawing` int(4) NOT NULL,
  `owner` int(5) NOT NULL default '-1',
  `isWinner` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=33;

CREATE TABLE `lotto` (
  `drawing` int(5) NOT NULL auto_increment,
  `opened` int(12) NOT NULL,
  `closed` int(12) NOT NULL,
  `isOpen` tinyint(1) NOT NULL default '0',
  `winningTicket` int(5) default NULL,
  `winner` int(5) default NULL,
  `potSize` int(8) default NULL,
  PRIMARY KEY  (`drawing`)
) TYPE=MyISAM AUTO_INCREMENT=3;

CREATE TABLE `onlinetime` (
  `userid` int(10) NOT NULL,
  `h00` int(1) NOT NULL default '0',
  `h01` int(1) NOT NULL default '0',
  `h02` int(1) NOT NULL default '0',
  `h03` int(1) NOT NULL default '0',
  `h04` int(1) NOT NULL default '0',
  `h05` int(1) NOT NULL default '0',
  `h06` int(1) NOT NULL default '0',
  `h07` int(1) NOT NULL default '0',
  `h08` int(1) NOT NULL default '0',
  `h09` int(1) NOT NULL default '0',
  `h10` int(1) NOT NULL default '0',
  `h11` int(1) NOT NULL default '0',
  `h12` int(1) NOT NULL default '0',
  `h13` int(1) NOT NULL default '0',
  `h14` int(1) NOT NULL default '0',
  `h15` int(1) NOT NULL default '0',
  `h16` int(1) NOT NULL default '0',
  `h17` int(1) NOT NULL default '0',
  `h18` int(1) NOT NULL default '0',
  `h19` int(1) NOT NULL default '0',
  `h20` int(1) NOT NULL default '0',
  `h21` int(1) NOT NULL default '0',
  `h22` int(1) NOT NULL default '0',
  `h23` int(1) NOT NULL default '0',
  PRIMARY KEY  (`userid`)
) TYPE=MyISAM;

CREATE TABLE `transactions` (
  `id` int(10) NOT NULL auto_increment,
  `time` int(12) NOT NULL,
  `owner` int(5) NOT NULL,
  `banker` int(5) NOT NULL,
  `type` int(2) NOT NULL default '0',
  `amount` int(15) NOT NULL default '0',
  `reason` varchar(500) NOT NULL default 'cash deposit',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=36;

CREATE TABLE `payoutRequests` (
  `request` int(6) NOT NULL auto_increment,
  `time` int(12) NOT NULL,
  `applicant` int(5) NOT NULL,
  `amount` int(12) NOT NULL,
  `payoutTime` int(12),
  `banker` int(5),
  PRIMARY KEY  (`request`)
) TYPE=MyISAM AUTO_INCREMENT=36;

UPDATE config SET value='4' WHERE name='version';
truncate images;
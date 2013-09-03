/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/doc/sql/ancient/mysql-update-1-2.txt,v 1.1 2007/06/10 13:45:49 mining Exp $
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
 
ALTER TABLE cans ADD COLUMN isFull int(1) NOT NULL DEFAULT 0;
ALTER TABLE cans ADD COLUMN miningrun int(5) DEFAULT -1;
ALTER TABLE `users` CHANGE `rank` `title` VARCHAR( 40 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'Miner';
ALTER TABLE users ADD COLUMN preferences blob;
ALTER TABLE users ADD COLUMN rank int(2) NOT NULL DEFAULT 0;
ALTER TABLE users ADD COLUMN optIn int(1) NOT NULL DEFAULT 1;

UPDATE config SET value='2' WHERE name='version';
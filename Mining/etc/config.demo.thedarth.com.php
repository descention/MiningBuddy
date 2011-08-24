<?PHP

/* 
 * MiningBuddy (http://miningbuddy.net)  
 * $Header: /usr/home/mining/cvs/mining/etc/config-release.php,v 1.40 2008/10/22 12:15:15 mining Exp $
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

/*
 * Welcome to the configuration file of MiningBuddy.
 * 
 * Copy this file to config.<yourhost>.php and edit it to reflect your settings.
 * The config.php file will never be overwritten on updates; but it
 * might need some manual tweaking if new settings are required in future
 * versions. This procedure is required because MiningBuddy is under active 
 * development and as such, new features are coded in on a regular basis which
 * may require new configuration settings.
 * 
 * If you do not know what your configuration file should be named,
 * just launch MiningBuddy by visiting it with your browser. It will
 * complain that the file could not be found.
 */

/*
 *  Step 1: Enter the information for your SQL Server below.
 * 
 *    Hint: If you get errors about mysqli or if you are having problems
 *          with your database, change the protocol from mysqli to mysql.
 */
$mysql_username = "arnoldj_mbdemo";
$mysql_password = "0nlyf0rd3m0";
$mysql_hostname = "localhost";
$mysql_dbname = "arnoldj_mbtest85";
$mysql_protocol = "mysqli";

/*
 * Step 2: Security
 * 
 * We do not store passwords in the database. Instead, they are encrypted
 * with a one-way only function. To add even more security the passwords
 * are encrypted again with a static key that should be as random as 
 * possible. Before your *first launch* of MiningBuddy enter a very random
 * string in there, around 30-40 characters in length.
 * 
 * The more random, the better.
 * 
 * DO NOT CHANGE THE SALT KEY ONCE YOU HAVE USERS! Changing the salt on a
 * production server will make ALL LOGINS FAIL!
 */
$SALT = "s98ss7fsc7fd2rf62ctcrlwztstnzve9toezexcsdhfgviuinusxcdtsvbrg";

/*
 * Step 3: Optional Modules
 * 
 * TIDY_ENABLE: This enabled or disables the use of Tidy. Rule of thumb:
 *              If you do not know what tidy is, you do no need it.
 *              Keep it turned off unless you *really* need it.
 */
$TIDY_ENABLE = false;

/*
 * Step 4: IGB Visual
 * 
 * IGB_VISUAL: This enables or disables the use of the IGB look or the Full
 *             Visual.  True used the light IGB look and False will use Full  
 *             Visual of standard Out of Game Browser.
 */
$IGB_VISUAL = false;

/*
 * Step 5: Enable MiningBuddy
 * 
 * Uncomment this line to enable MiningBuddy by removing the "//" in
 * front of the line.
 */
$HAVE_READ=true;

/* 
 * End configuration.
 * Changing anything below this line will cause the MiningBuddy to break,
 * grow wings, become a Minmatar and beeing sold into slavery.
 * 
 * Hint: You can change the CONF_VER number if you manually updated this 
 *       configuration file and you are sure that your configuration file
 *       should work with the new installation of MiningBuddy.
 *       To be on the safe side: Always use a fresh config-release.php
 *       file.
 */
include ('./etc/config-system.php');
$CONF_VER = "9";
?>
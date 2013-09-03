/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/doc/sql/ancient/mysql-update-7-8.txt,v 1.1 2007/06/10 13:45:49 mining Exp $
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
 
CREATE TABLE `templates` (
  `id` int(10) NOT NULL auto_increment,
  `identifier` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `descr` varchar (80),
  `template` blob,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
 
 
ALTER TABLE `users` ADD `deleted` smallint(1) NOT NULL DEFAULT 0;
ALTER TABLE `runs` ADD `oreGlue` int(4) NOT NULL DEFAULT 0;
 
 
INSERT INTO templates (identifier, type, descr, template) VALUES 
("activation", "email", "Account activation email","Hello {{USERNAME}} !

Your CEO has just confirmed your account, and we are more than
happy to present you your password.

Please use the password: {{NEWPASS}}

Please log in to you earliest convenience and change that
password to something easier to renember. Or write this one
down.

{{VERSION}} of {{SITENAME}}.

See you in the asteroid fields soon,

{{SITENAME}}.");





INSERT INTO templates (identifier, type, descr, template) VALUES 
("lostpass", "email", "Lost password email", "Hello {{USERNAME}} !

Someone from {{IP}} -possibly you- has recently visited the
{{VERSION}} of {{SITENAME}}.

However, you or he/she was not able to log in and has
requested a new password, which we are happy to supply to you:

{{NEWPASS}}

Please log in to you earliest convenience and change that
password to something easier to renember. Or write this one
down.

See you in the asteroid fields soon,

{{SITENAME}}.");





INSERT INTO templates (identifier, type, descr, template) VALUES 
("newevent", "email", "New event accounced email", "Greetings {{USER}}!
{{FLAGOFFICER}} has just announced a new Event!

Renember: If you want to come, please login with your
          account and join this event. This helps the
          officers in charge to know how many are
          coming.

-- 8< -- -- 8< -- -- 8< -- -- 8< -- -- 8< -- -- 8< -- 

EVENT INFORMATION

Mission ID          {{ID}}
Mission Type        {{TYPE}}
Description         {{SDESCR}}
Executing Officer   {{FLAGOFFICER}}
System              {{SYSTEM}}
Security            {{SECURITY}}
Starttime           {{STARTTIME}}
Estimated Duration  {{DURATION}}
Difficulty          {{RISK}}
Payment             {{PAYMENT}}
Collateral          {{COLLATERAL}}

Additional Notes:
{{NOTES}}

-- 8< -- -- 8< -- -- 8< -- -- 8< -- -- 8< -- -- 8< -- 

You recived this eMail because you are subscribed to
the eMail announcement system of MiningBuddy, 
belonging to {{SITENAME}}.
If you do not wish to recive any more eMails from
this site, please go to the following site, login
and opt-out (preferences page).

{{URL}}

-- 8< -- -- 8< -- -- 8< -- -- 8< -- -- 8< -- -- 8< -- 
powered by {{VERSION}}.");




INSERT INTO templates (identifier, type, descr, template) VALUES 
("accountactivated", "email", "Account activated email", "Hello {{USERNAME}} !

Your friendly corporation, {{CORP}},
has just created an account for you for the MiningBuddy
at {{SITE}}.

Your login name: {{USERNAME}}
Your Password  : {{PASSWORD}}

Please log in to you earliest convenience and change that
password to something easier to renember. Or write this one
down.

See you in the asteroid fields soon,

{{CREATOR}}
{{CORP}}.");




INSERT INTO templates (identifier, type, descr, template) VALUES 
("accountrequest", "email", "Account activation email", "Hello there, pilot!

Someone from the IP {{IP}}, possibly you, requested an
account for the MiningBuddy deployment located at

{{URL}}

The request was made on {{DATE}}.

If you requested it, you need to activate your account
by clicking on this link:

{{ACTIVATE}}

If the request was made in error, or not by you, do
not worry. If you do not do anything your email adress
will be blacklisted in our system and not be used again.

Thank you,
kind regards,
The CEO of {{CORP}}.");

INSERT INTO templates (identifier, type, descr, template) VALUES 
("receipt", "email", "Announcement after login", "{{DIVIDERTOP}}

Hello {{USERNAME}},

Thank you for attending mining operation #{{ID}}. Below is a lineup
of your operations achievement:

Ores Mined:
----------------------------------------------------------------------
{{ORESMINED}}
----------------------------------------------------------------------
                                        TOTAL VALUE: {{VALUE}}
                                   YOUR GROSS VALUE: {{GROSSSHARE}}
                                         CORP TAXES: {{CORPTAXES}}
                                          NET VALUE: {{NETVALUE}}
                                     YOUR NET SHARE: {{NETSHARE}}


The amonunt of {{NETSHARE}} has been credited to your account,
below is a short summary of your recent transactions:

----------------------------------------------------------------------
{{ACCOUNT}}
----------------------------------------------------------------------
{{ACCOUNTBALANCE}}

Thank you again for taking part in this mining operation. We hope to
see you in future runs as well!

{{SITENAME}}
{{URL}}

{{DIVIDERBOT}}");


INSERT INTO templates (identifier, type, descr, template) VALUES 
("motd", "announce", "Announcement after login", "");


UPDATE config SET value='8' WHERE name='version';
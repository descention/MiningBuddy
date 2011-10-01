<?php


/*
 * MiningBuddy (http://miningbuddy.net)
 * $Header: /usr/home/mining/cvs/mining/functions/registry.php,v 1.88 2008/09/08 09:04:17 mining Exp $
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
 * The central php registry.
 * Here all the PHP files are included
 */

/* Mainly HTML spawning functions */
require_once ('./functions/html/addEvent.php');
require_once ('./functions/html/addHaulPage.php');
require_once ('./functions/html/confirm.php');
require_once ('./functions/html/makeAddUserForm.php');
require_once ('./functions/html/makeCanPage.php');
require_once ('./functions/html/makeLoginPage.php');
require_once ('./functions/html/makeLostPassForm.php');
require_once ('./functions/html/makeNewOreRunPage.php');
require_once ('./functions/html/makeNotice.php');
require_once ('./functions/html/makeOreWorth.php');
require_once ('./functions/html/makeShipValue.php');
require_once ('./functions/html/makeRequestAccountPage.php');
require_once ('./functions/html/makeWelcome.php');
require_once ('./functions/html/onlineTime.php');
require_once ('./functions/html/showEvent.php');
require_once ('./functions/html/showEvents.php');
require_once ('./functions/html/showHierarchy.php');
require_once ('./functions/html/showOreValue.php');
require_once ('./functions/html/showShipValue.php');
require_once ('./functions/html/showRanks.php');
require_once ('./functions/html/showTransactions.php');
require_once ('./functions/html/makeMenu.php');
require_once ('./functions/html/makePreferences.php');
require_once ('./functions/html/globalStatistics.php');
require_once ('./functions/html/browser.php');
require_once ('./functions/html/profile.php');

/* Login Module */
require_once ('./functions/login/auth.php');
require_once ('./functions/login/authKeyIsValid.php');
require_once ('./functions/login/authVerify.php');
require_once ('./functions/login/createAuthKey.php');
require_once ('./functions/login/encryptPassword.php');
require_once ('./functions/login/getLogins.php');
require_once ('./functions/login/sanitize.php');
require_once ('./functions/login/showFailedLogins.php');
require_once ('./functions/login/checkBan.php');

/* Administrative Modules */
require_once ('./functions/admin/addNewUser.php');
require_once ('./functions/admin/deleteAPIKey.php');
require_once ('./functions/admin/editUser.php');
require_once ('./functions/admin/listUser.php');
require_once ('./functions/admin/listUsers.php');
require_once ('./functions/admin/setConfig.php');
require_once ('./functions/admin/getConfig.php');
require_once ('./functions/admin/configuration.php');
require_once ('./functions/admin/maintenance.php');
require_once ('./functions/admin/toggleLogin.php');
require_once ('./functions/admin/quickconfirm.php');

/* Database manipulating functions */
require_once ('./functions/database/makeDB.php');
require_once ('./functions/database/addCanToDatabase.php');
require_once ('./functions/database/addEventToDB.php');
require_once ('./functions/database/addHaul.php');
require_once ('./functions/database/addRank.php');
require_once ('./functions/database/addRun.php');
require_once ('./functions/database/changeOreValue.php');
require_once ('./functions/database/changeShipValue.php');
require_once ('./functions/database/createTransaction.php');
require_once ('./functions/database/delRank.php');
require_once ('./functions/database/deleteEvent.php');
require_once ('./functions/database/deletePayoutRequest.php');
require_once ('./functions/database/doPayout.php');
require_once ('./functions/database/editRanks.php');
require_once ('./functions/database/editTemplate.php');
require_once ('./functions/database/getCredits.php');
require_once ('./functions/database/getOreSettings.php');
require_once ('./functions/database/getShipSettings.php');
require_once ('./functions/database/getTemplate.php');
require_once ('./functions/database/getTotalHaulRuns.php');
require_once ('./functions/database/joinEvent.php');
require_once ('./functions/database/miningRunOpen.php');
require_once ('./functions/database/modConfiguration.php');
require_once ('./functions/database/modOnlineTime.php');
require_once ('./functions/database/popCan.php');
require_once ('./functions/database/requestPayout.php');
require_once ('./functions/database/sirchange.php');
require_once ('./functions/database/toggleCan.php');
require_once ('./functions/database/toggleCharity.php');
require_once ('./functions/database/userInRun.php');
require_once ('./functions/database/modProfile.php');

/* Classes */
require_once ('./classes/html_class.php');
require_once ('./classes/table_class.php');
require_once ('./classes/api_class.php');
require_once ('./classes/notice_class.php');
require_once ('./classes/transaction_class.php');
require_once ('./classes/email_class.php');
require_once ('./classes/preferences_class.php');
require_once ('./classes/user_class.php');
require_once ('./classes/graphic_class.php');
require_once ('./classes/solarSystem_class.php');
require_once ('./classes/profile_class.php');
require_once ('./classes/browserinfo_class.php');

/* User Management */
require_once ('./functions/usermngt/changeEmail.php');
require_once ('./functions/usermngt/validate.php');
require_once ('./functions/usermngt/toggleOptIn.php');
require_once ('./functions/usermngt/userExists.php');
require_once ('./functions/usermngt/usernameToID.php');
require_once ('./functions/usermngt/changePassword.php');
require_once ('./functions/usermngt/idToUsername.php');
require_once ('./functions/usermngt/lostPassword.php');
require_once ('./functions/usermngt/requestAccount.php');

/* Lotto module */
require_once ('./functions/lotto/lotto_createGroup.php');

/* Mathematical functions */
require_once ('./functions/math/addCredit.php');
require_once ('./functions/math/calcPayoutPercent.php');
require_once ('./functions/math/getTotalRuntime.php');
require_once ('./functions/math/getTotalWorth.php');
require_once ('./functions/math/getTransactions.php');
require_once ('./functions/math/makeEmailReceipt.php');
require_once ('./functions/math/manageWallet.php');
require_once ('./functions/math/numberToString.php');
require_once ('./functions/math/payout.php');
require_once ('./functions/math/totalIskOwned.php');
require_once ('./functions/math/transferMoney.php');
require_once ('./functions/math/calcualteTotalIskMined.php');
require_once ('./functions/math/calcTMEC.php');

/* Misc Functions */
require_once ('./functions/misc/changeCanPrefs.php');
require_once ('./functions/misc/checkEmailAdress.php');
require_once ('./functions/misc/doRessource.php');
require_once ('./functions/misc/humanTime.php');
require_once ('./functions/misc/makePermissionRow.php');
require_once ('./functions/misc/noNeg.php');
require_once ('./functions/misc/resolveRankID.php');
require_once ('./functions/misc/yesno.php');
require_once ('./functions/misc/getRank.php');
require_once ('./functions/misc/makeProfileLink.php');

/* Process handler functions */
require_once ('./functions/process/get.php');
require_once ('./functions/process/post.php');

/* Mining Operation Module */
require_once ('./functions/runs/deleteRun.php');
require_once ('./functions/runs/endRun.php');
require_once ('./functions/runs/getLocationOfRun.php');
require_once ('./functions/runs/joinAs.php');
require_once ('./functions/runs/joinRun.php');
require_once ('./functions/runs/kick.php');
require_once ('./functions/runs/leaveRun.php');
require_once ('./functions/runs/listRun.php');
require_once ('./functions/runs/listRuns.php');
require_once ('./functions/runs/runIsLocked.php');
require_once ('./functions/runs/runSupervisor.php');
require_once ('./functions/runs/sidebarOpenRuns.php');
require_once ('./functions/runs/toggleLock.php');

/* Debug functions */
require_once ('./functions/debug/breakpoint.php');
require_once ('./functions/debug/debug.php');

/* System functions */
require_once ('./functions/system/checkForUpdate.php');
require_once ('./functions/system/ctypeAlnum.php');
require_once ('./functions/system/errorHandler.php');
require_once ('./functions/system/mailUser.php');
require_once ('./functions/system/numericCheck.php');
require_once ('./functions/system/numericCheckBool.php');
?>

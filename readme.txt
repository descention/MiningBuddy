requires:
php5-common
php5-curl
php5-mysql

/* Not actually used yet. Will implement this and remove the images from the repo once most bugs are fixed.
For images/thumbnails of items, download the image dump from CCP for the current release.
Release: Retribution
Link: http://content.eveonline.com/data/Retribution_1.0_Types.zip
Extract to: ./images/
*/

You'll need to edit the following files for proper configuration:
./etc/config-release.php

If you're using a supported authentication method other than the base user authentication, you'll want to look in the following directory and configure your respective file.
./classes/auth/

Supported Authentication methods:
base user authentication
SMF 2.0 Rest API (in development)
Test Alliance Auth API 1.0

Post Install:
You'll want to go to "Edit Items" after login and add prices to items. A price must exist for an item to be enabled.
Additional Op types (default "Standard") may be added, such as Mining, Incursion, PI, etc... by adding a row to the 'opType' table.
My corp uses 

Issues I've had:

Web server buffer size was not big enough to save configuration changes (nginx):
http://stackoverflow.com/questions/2307231/how-to-avoid-nginx-upstream-sent-too-big-header-errors

Email isn't sent from my server, and thus is untested and has code to exclude email sending during account creation. If you wish to enable email, add/update the 'emailValidation' row (1 = enabled, 0 = disabled) in the 'config' table of the database.

My corporation does not use different percentages for pay based on ship type, so this has been temporarily disabled until other bugs may be worked out. 

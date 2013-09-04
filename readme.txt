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
You'll want to go to "Edit Items" and enable a few while adding prices. A price must exist for an item to be enabled.

Issues I've had:

Web server buffer size was not big enough to save configuration:
http://stackoverflow.com/questions/2307231/how-to-avoid-nginx-upstream-sent-too-big-header-errors


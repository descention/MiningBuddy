<?php

function Getbasesite($file)
{
	$find = '/';
	$after_find = substr(strrchr($file, $find), 1); 
	$strlen_str = strlen($after_find); 
	$result = substr($file, 0, -$strlen_str); 
	
	return $result; 
}

function SplitSQL($file, $delimiter = ';')
{
    set_time_limit(0);

    if (is_file($file) === true)
    {
        $file = fopen($file, 'r');

        if (is_resource($file) === true)
        {
            $query = array();

            while (feof($file) === false)
            {
                $query[] = fgets($file);

                if (preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1)
                {
                    $query = trim(implode('', $query));

                    if (mysql_query($query) === false)
                    {
                        echo '<h3>ERROR: ' . $query . '</h3>' . "\n";
                    }

                    else
                    {
                        echo '<h3>SUCCESS: ' . $query . '</h3>' . "\n";
                    }

                    while (ob_get_level() > 0)
                    {
                        ob_end_flush();
                    }

                    flush();
                }

                if (is_string($query) === true)
                {
                    $query = array();
                }
            }

            return fclose($file);
        }
    }

    return false;
}

$sitename = $_SERVER["HTTP_HOST"];
$sitenamepath = $_SERVER["SCRIPT_NAME"];
$pathsite = $sitename.$sitenamepath;

$upgrade=$_REQUEST['upgrade'];
include ("./etc/config.$sitename.php");

if ($upgrade=='1' ){
	$file = $pathsite;
	
	$db_conn = mysql_connect($mysql_hostname, $mysql_username, $mysql_password);
	mysql_select_db($mysql_dbname);

	echo "Apply Update for " . ($CURRENT[0]) . " to " . ($CURRENT[0] + 1);
	echo "<br>";
	
	SplitSQL("./doc/sql/mysql-update-" . ($CURRENT[0]) ."-".($CURRENT[0] + 1).".sql");
	
	echo "If 'ERROR' is printed above, you might want to copy this page and send a message to <a href='mailto:scott.mundorff@gmail.com'>Descention</a><br/>";
	
	echo "Upgrade Completed!";
	echo "<br>";
	echo "<a href=\"index.php\">Click here to Login</a>";
	
}else{
	echo "This is the $VERSION SQL upgrade page.<br>";
	echo "When you are ready to upgrade your Database for $sitename to the newest version click the link below<br>";
	echo "<a href=\"index.php?upgrade=1\"><h1>Upgrade Now to Database Version $SQLVER</h1> (from version " . ($SQLVER - 1) . ")</a><br>";
}
?>

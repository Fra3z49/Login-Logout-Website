<?php
define ('DBSERVER', "localhost"); //Database server
define ('DBUSERNAME', "root"); //Database username
define ('DBPASSWORD', ''); //Database password
define ('DBNAME', 'users'); //Database name

/* connect to my MySQL database */
$db = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);

//Check db connection

if ($db === false) {
    die("Error: connection error." . mysqli_connect_error());
}
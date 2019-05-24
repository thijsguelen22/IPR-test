<?php
require_once("./import.php");
require_once("../include/db_connector.php");

use Daveismyname\SqlImport\Import;

$filename = '../sql/CREATE Tables.sql';
$username = 'sa';
$password = 'tjP)uU!M__bP9HLbu)%t';
$database = 'IprojectConvert';
$host = 'DESKTOP-IMVVNNI';
$dropTables = true;
new Import($filename, $username, $password, $database, $host, $dropTables, true);

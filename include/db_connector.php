<?php
$hostname = "(localhost0";
$dbname = "(dbname)";
$username = "";
$pw = "";
try
{
  $dbh = new PDO("sqlsrv:Server=$hostname;Database=$dbname; ConnectionPooling=0", "$username", "$pw");
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
  die ( "Fout met de database: {$e->getMessage()} " );
}

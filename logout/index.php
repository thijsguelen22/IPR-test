<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/globalFunctions.inc.php");
checkSession();
$_SESSION = NULL;
session_destroy();
header("location: ".getProtocol().$_SERVER['SERVER_NAME']);
?>

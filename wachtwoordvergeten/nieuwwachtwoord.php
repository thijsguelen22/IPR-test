<?php
require_once("../include/globalFunctions.inc.php");
require_once("../include/db_connector.php");

$userName = $UserErr = $email = $emailErr = "";
$formErr = false;
$success = false;


if(isset($_POST['submit'])) {
  $email    = strtolower(!empty($_POST['email'])     ? $_POST['email'] : '');

  if(empty($_POST['email']) || $email == "") {
    $formErr = true;
    $emailErr = "Email kan niet leeg zijn";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
  {
    $emailErr = "email is niet geldig";
    $formErr = true;
  }

  if($formErr == false) {
    $success = true;
    $token = generatePasswdToken($dbh, $email);
    emailPasswordToken($dbh, $email, $token);
  }

}
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/foundation.css">
  <link rel="stylesheet" href="../css/app.css">
  <link rel="stylesheet" href="../css/footer.css">
  <link rel="stylesheet" href="../css/stylesheet.css">
  <title>Eenmaal Andermaal</title>
</head>

<body>
  <?php
  require_once("../include/header.php");

  if($success) {
    require_once("./success.php");
  } else {
    require_once("./form.php");
  }

  require_once("../include/footer.php");
  ?>

  <script src="../js/vendor/jquery.js"></script>
  <script src="../js/vendor/what-input.js"></script>
  <script src="../js/vendor/foundation.js"></script>
  <script src="../js/app.js"></script>
</body>

</html>

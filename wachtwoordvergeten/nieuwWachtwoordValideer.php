<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/db_connector.php");
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/globalFunctions.inc.php');

$passwdErr = $rePasswdErr = $GeneralErr = "";
$formErr = $done = false;

if(isset($_GET['token']) && $_GET['token'] != "") {
    $token = urldecode($_GET['token']);
    //echo $token;
    //echo '<br>$2y$10$jhhaQ0RnBXj6qqSuNmRLK.IAZPaBJQFeN29yd9Mixr4otInX8C856';
    $tokenVerify = verifyToken($dbh, $token);
    var_dump($tokenVerify);
    if($tokenVerify['code'] == 1) {
      $success = true;
    } else {
      $success = false;
    }
}
if(isset($_POST['submit'])) {
  $passwd =   $_POST['passwd'];
  $rePasswd = $_POST['passwd-repeat'];
  if($passwd != $rePasswd) {
    $passwdErr = "Wachtwoorden komen niet overeen";
    $formErr = true;
  }
  if(empty($_POST['passwd']) || $passwd == "") {
    $passwdErr = "Veld kan niet leeg zijn";
  } elseif(!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $passwd)){
      $passwdErr = "Het wachtwoord moet zowel letters als cijfers bevatten.";
        $CheckOnErrors = true;
    }
  if(empty($_POST['passwd-repeat']) || $rePasswd == "") {
    $rePasswdErr = "Veld kan niet leeg zijn";
  }

  if($formErr == false) {
    $passwd = password_hash($passwd, PASSWORD_DEFAULT);
    if(updatePassword($dbh, $passwd, $tokenVerify['data']['mailbox']) == 1) {
      $GeneralErr = "<h3>Nieuw wachtwoord succesvol opgeslagen. U kunt nu inloggen.</h3>";
    } else {
      $GeneralErr = '<h3 class="form-error" style="display: block">Er ging iets fout. Probeer het later opnieuw.</h3>';
    }
    $done = true;
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

    if($done) {
?>

<div class="row">
  <div class="column">
    <div class="register">
      <div class="callout">
        <?php echo $GeneralErr; ?>
      </div>
    </div>
  </div>
</div>


<?php
    } else {
      require_once("./newpassform.php");
    }
  } else {
    header("Location: ./invalidlink.php");
  }

  require_once("../include/footer.php");
  ?>

  <script src="../js/vendor/jquery.js"></script>
  <script src="../js/vendor/what-input.js"></script>
  <script src="../js/vendor/foundation.js"></script>
  <script src="../js/app.js"></script>
</body>

</html>

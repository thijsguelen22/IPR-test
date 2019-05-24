<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/include/db_connector.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/include/globalFunctions.inc.php");
    checkSession();

    if(isset($_SESSION['UserAccount']['LoggedIn']) && $_SESSION['UserAccount']['LoggedIn']  == true){
      $ret = 0;
      $userName = $_SESSION['UserAccount']['UserName'];
      $ret = deleteUser($dbh, $userName);
    }
    $_SESSION = null;
    session_destroy();
?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/foundation.css">
  <link rel="stylesheet" href="/css/app.css">
  <link rel="stylesheet" href="/css/footer.css">
  <link rel="stylesheet" href="/css/stylesheet.css">
  <title>Eenmaal Andermaal</title>
</head>

<body>
  <?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/header.php"); ?>
  <div class="row">
    <div class="column">
      <div class="login">
        <form action="" method="post"class="callout text-center">
          <div class="column">
            <div class="floated-label-wrapper">
              <?php
              if($ret == 1) {
                echo "<h1>Verwijderen succesvol.</h1>";
              } else {
                echo "<h1>Er ging iets fout. Probeer het later opnieuw.</h1>";
              }?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/footer.php"); ?>

  <script src="/js/vendor/jquery.js"></script>
  <script src="/js/vendor/what-input.js"></script>
  <script src="/js/vendor/foundation.js"></script>
  <script src="/js/app.js"></script>
</body>

</html>

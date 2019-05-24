<?php
//require_once("./db_connector.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/db_connector.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/globalFunctions.inc.php");
checkSession();

if(isset($_SESSION['UserAccount']) && $_SESSION['UserAccount']['LoggedIn']) {
  header("Location: ".getProtocol().$_SERVER['SERVER_NAME']);
}
checkBlockedUsers();

$UserErr = $PassErr = $RePassErr = $GeneralErr = $Success = $userName = $captchaErr = "";
$formErr = false;
if(isset($_POST['login']))
{
  $formErr = false;
  $userName = $_POST['username'];
  $passwd   = $_POST['passwd']  ;

  //controleer het voornaam veld
  if(empty($userName) || $userName == "")
  {
    $UserErr = "Gebruikersnaam kan niet leeg zijn";
    $formErr = true;
  }
  if(empty($passwd))
  {
    $PassErr = "Wachtwoord kan niet leeg zijn";
    $formErr = true;
  }
  if($formErr == false)
  {
    $BlockedUser = readBlockedUsers();
    if(isset($BlockedUser['Users'][$userName]['tries']) && isset($BlockedUser['Users'][$userName]['time']) && $BlockedUser['Users'][$userName]['tries'] > 2) {
      $blockedMins = round(($BlockedUser['Users'][$userName]['time'] - time())/60);
      if($blockedMins > 1) {
        $GeneralErr = "U bent wegens herhaaldelijk foutieve inlogpogingen geblokkeerd voor ".$blockedMins." minuten. Probeer het later opnieuw.";
      } else if($blockedMins == 1) {
        $GeneralErr = "U bent wegens herhaaldelijk foutieve inlogpogingen geblokkeerd voor één minuut. Probeer het later opnieuw.";
      } else if($blockedMins < 1 && isset($BlockedUser['Users'][$userName]['time']))  {
        $secs = $BlockedUser['Users'][$userName]['time'] - time();
        $GeneralErr = "U bent wegens herhaaldelijk foutieve inlogpogingen geblokkeerd voor ".$secs." seconden. Probeer het later opnieuw.";
      }
    } else {
      $UserSaved = checkLogin($dbh, $userName, $passwd);
      if($UserSaved['PDORetCode'] == 0) {
        $GeneralErr = "Er trad een onbekende fout op. Probeer het later opnieuw.";
      } else if($UserSaved['PDORetCode'] == 2) {
        $GeneralErr = "Gebruikersnaam en/of wachtwoord fout.";
        addLoginTry($userName);
      } else {
        $data = $UserSaved['data'];
        if(password_verify($passwd, $data['wachtwoord'])) {
          $_SESSION['UserAccount']['LoggedIn']    = true;
          $_SESSION['UserAccount']['UserName']    = $data['gebruikersnaam'];
          $_SESSION['UserAccount']['FirstName']   = $data['voornaam'];
          $_SESSION['UserAccount']['LastName']    = $data['achternaam'];
          $_SESSION['UserAccount']['PostalCode']  = $data['postcode'];
          header("Location: ".getProtocol().$_SERVER['SERVER_NAME']);
          } else if($data['isGeverifieerd'] == false) {
            $GeneralErr = "Uw account is nog niet geverifieerd. Controleer uw e-mail inbox en verifieer uw account.";
        } else {
          $GeneralErr = "Gebruikersnaam en/of wachtwoord fout.";
          addLoginTry($userName);
        }
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <?php require_once($_SERVER["DOCUMENT_ROOT"].'/include/html-header.php'); ?>

  <title>Eenmaal Andermaal</title>
</head>

<body>
  <?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/header.php"); ?>
    <span style="color:green">
      <?php echo $Success; ?></span>
      <div class="row">
        <div class="column">
          <div class="login">
            <form action="" method="post" class="callout text-center">
              <h2>Inloggen</h2>
              <div class="column">
                <p class="form-error" style="display: block;"><?php echo $GeneralErr; ?>
                <div class="floated-label-wrapper">
                  <input name="username" value="<?php echo $userName; ?>" placeholder="Gebruikersnaam" type="text">
                  <p class="form-error" style="display: block"><?php echo $UserErr; ?></p>
                </div>
                <div class="floated-label-wrapper">
                  <input name="passwd" placeholder="Wachtwoord" type="password">
                  <p class="form-error" style="display: block"><?php echo $PassErr; ?></p>
                </div>
                <div class="floated-label-wrapper">
                  <a href="../wachtwoordvergeten/nieuwwachtwoord.php">wachtwoord vergeten</a>
                </div>
                <div class="floated-label-wrapper">
                  <input class="button round" type="submit" name="login" value="Inloggen">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>


      <?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/footer.php"); ?>

      <script src="../js/vendor/jquery.js"></script>
      <script src="../js/vendor/what-input.js"></script>
      <script src="../js/vendor/foundation.js"></script>
      <script src="../js/app.js"></script>

      <script>
      $( document ).ready(function() {
        $(".g-rechapta").first().first().css( "margin: 0 auto !important" );
      });
    </script>

  </body>

  </html>

<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/db_connector.php";
require_once "../include/globalFunctions.inc.php";
checkSession();

$email = $emailErr = $GeneralErr = $captchaErr = "";
$succes = 0;
$CheckOnErrors = false;
$formErr = false;

if(isset($_POST['verstuur1'])) {
  $email    = strtolower(cleanInput($_POST['email']));
  $captcha  = $_POST['g-recaptcha-response'];
  $succes1 = checkMailUnique($dbh, $email);

  $succes2 = checkTempMailUnique($dbh, $email);
  if($succes2 == 1) {
    deleteTempUser($dbh, $email);
  }

  if($succes1 == 2) {
    $CheckOnErrors = true;
    $formErr = true;
  } else if($succes1 == 1) {
    $formErr = true;
    $CheckOnErrors = false;
    $emailErr = "Email adres is al in gebruik";
  }
  $captchaResponse = true;
  if(empty($_POST['g-recaptcha-response']) || $captcha == "") {
    $captchaErr = "Vul de captcha in";
    $formErr = true;
    $captchaResponse = false;
  } else {
    $secret = "6LfBeKMUAAAAAPB_LcQUNrWQCHedGplKUAVf0pMp";
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha;
    $data = file_get_contents($url); // put the contents of the file into a variable
    $captchaResponse = json_decode($data);
  }
  if($captchaResponse) {
    if($captchaResponse->success == false) {
      $captchaErr = "Vul de captcha in";
      $formErr = true;
    }
  }
  if(empty($_POST['email']) || $email == "") {
    $formErr = true;
    $emailErr = "Email kan niet leeg zijn";
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $emailErr = "Email is niet geldig";
    $formErr = true;
  }

  if($formErr == false) {
    $_SESSION['ID'] = hash("sha3-512", $email);

    $succes = addTempDBindex($dbh, $_SESSION['ID']);
    if($succes == 1) {
      $succes = addTempEmail($dbh, $email, $_SESSION['ID']);
      if($succes == 1){
        $CheckOnErrors = false;
        $code = "";
        for($count = 0; $count < 8; $count++){
          $code .= rand(0, 9);
        }
        $succes = addTempCode($dbh, $code, $_SESSION['ID']);
        if ($succes == 1) {
          $message = '<html>
          <head>
          <title>Email verificatie</title>
          </head>
          <body>
          <p>Welkom bij EenmaalAndermaal,</p> <br>
          <p>Uw verificatiecode is: ' . $code . '</p> <br>
          <p>Met vriendelijke groet, </p>
          <p> EenmaalAndermaal</p>
          </body>
          </html>';

          $header = 'MIME-Version: 1.0' . "\r\n" .
          'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
          'From: EenmaalAndermaal No-Reply <no-reply@eenmaalandermaal.com>';

          $sendTo = getTempInfo($dbh, $_SESSION['ID']);

          try {
            mail($sendTo['data']['mailbox'], "Verificatiecode EenmaalAndermaal", $message, $header);
          } catch(PDOException $e) {
            $Error = "De email kon niet worden verzonden, probeer het later nog eens.";
          }

          $CheckOnErrors = false;
        } else {
          $CheckOnErrors = true;
        }
      } else{
        $CheckOnErrors = true;
      }
    } else {
      $CheckOnErrors = true;
    }
  }

  if($CheckOnErrors == true) {
    if($formErr == false) {
      emptyTempDB($dbh, $_SESSION['ID']);
    }
    $GeneralErr = "Er trad een onbekende fout op. Probeer het later opnieuw.";
  } else if($formErr == false) {
    header("location: http://".$_SERVER['SERVER_NAME'].'/registreren/registreren_controlecode.php');
  }
}
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
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <script type="text/javascript">
      var ReCaptchaCallbackV3 = function() {
          grecaptcha.ready(function() {
              grecaptcha.execute("6Ld6eKMUAAAAAK1V-TqHeB19Czp_OzA60RMiWzjp").then(function(token) {

              });
          });
      };
  </script>
  <title>Eenmaal Andermaal</title>
</head>

<body>
  <?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/header.php"); ?>
  <!--<span style="color:red">-->
  <!--        --><?php //echo $GeneralErr; ?><!--</span>-->
  <div class="row">
    <div class="column">
      <div class="email">
        <form action="" method="POST" class="callout text-center">
          <h2>Registreren</h2>
          <div class="callout sand-background"><h6> <strong><u>Email</u></strong> &nbsp; > &nbsp; Controle code &nbsp; > &nbsp; Accountgegevens &nbsp; > &nbsp; Persoonsgegevens &nbsp; > &nbsp; Check gegevens </h6></div>
          <div class="column">
            <p class="form-error" style="display: block"><?php echo $GeneralErr;?></p>
            <p class="text-left bold"> Vul hier uw email adres is. U ontvangt een code via de mail. </p>
            <div class="floated-label-wrapper">
              <input name="email" placeholder="Email adres" type="email" required>
              <p class="form-error" style="display: block"><?php echo $emailErr;?></p>
            </div>
            <div class="cell rechapta-cell">
              <div class="g-recaptcha" data-sitekey="6LfBeKMUAAAAAFGSG8tiqiCjp1qo5DJsEQHGRpT9"></div>
              <p class="form-error" style="display: block"><?php echo $captchaErr; ?></p>
            </div>
            <div class="floated-label-wrapper">
              <input name="verstuur1" class="button round" type="submit" value="Verstuur">
            </div>
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

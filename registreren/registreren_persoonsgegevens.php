<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/db_connector.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/globalFunctions.inc.php");
checkSession();

$FirstName = $LastName = $Adress = $Addition = $PostalCode = $City = $Country = $Birthday = $Error = $phone1 = $Phone2 = "";
$firstNameErr = $lastNameErr = $adressErr = $adressErr = $adressAddErr = $postalCodeErr = $cityErr = $countryErr = $birthdayErr = $GeneralErr = $phone1Err = $Phone2Err = "";
$succes = 0;
$formErr = false;

if (isset($_POST['verstuur4'])) {

  if (!empty($_POST['firstname'])) {
    $FirstName = cleanInput($_POST['firstname']);
    if (!isCharactersOnly($FirstName) || !isMinimumLength($FirstName, 2)) {
      $firstNameErr = "Voornaam mag alleen uit karakters bestaan en moet minimaal 2 karakters lang zijn";
      $succes = 0;
      $formErr = true;
    } else {
      $succes = addTempFirstName($dbh, $FirstName, $_SESSION['ID']);
      if ($succes == 0){
        $formErr = true;
        $GeneralErr = "Er ging iets fout. Probeer het later opnieuw.";
      }
    }
  } else {
    $succes = 1;
  }
  if ($succes == 1) {
    $succes = 0;
    if (!empty($_POST["lastname"])) {
      $LastName = cleanInput($_POST['lastname']);
      if (!isCharactersOnly($LastName) || !isMinimumLength($LastName, 2)) {
        $formErr = true;
        $lastNameErr = "Achternaam mag alleen uit karakters bestaan en moet minimaal 2 karakters lang zijn";
        $succes = 0;
      } else {
        $succes = addTempLastName($dbh, $LastName, $_SESSION['ID']);
        if ($succes == 0){
          $formErr = true;
          $GeneralErr = "Er ging iets fout. Probeer het later opnieuw.";
        }
      }
    } else {
      $succes = 1;
    }
  }
  if ($succes == 1) {
    $succes = 0;
    if (!empty($_POST['birthday'])) {
      $Birthday = cleanInput($_POST['birthday']);
      $succes = addTempBirthday($dbh, $Birthday, $_SESSION['ID']);
      if ($succes == 0){
        $formErr = true;
        $GeneralErr = "Er ging iets fout. Probeer het later opnieuw.";
      }
    } else {
      $succes = 1;
    }
  }

  if ($succes == 1) {
    $succes = 0;
    if (!empty($_POST['phone1'])) {
      $Phone1 = cleanInput($_POST['phone1']);
      $succes = addTempPhone1($dbh, $Phone1, $_SESSION['ID']);
      if ($succes == 0){
        $formErr = true;
        $GeneralErr = "Er ging iets fout. Probeer het later opnieuw.";
      }
    } else {
      $succes = 1;
    }
  }

  if ($succes == 1) {
    $succes = 0;
    if (!empty($_POST['phone2'])) {
      $Phone2 = cleanInput($_POST['phone2']);
      $succes = addTempPhone2($dbh, $Phone2, $_SESSION['ID']);
      if ($succes == 0){
        $formErr = true;
        $GeneralErr = "Er ging iets fout. Probeer het later opnieuw.";
      }
    } else {
      $succes = 1;
    }
  }

  if($succes == 1) {
    $succes = 0;
    if (!empty($_POST['adress'])) {
      $Adress = cleanInput($_POST['adress']);
      $succes = addTempAdress($dbh, $Adress, $_SESSION['ID']);
      if ($succes == 0){
        $formErr = true;
        $GeneralErr = "Er ging iets fout. Probeer het later opnieuw.";
      }
    } else {
      $succes = 1;
    }
  }

  if ($succes == 1) {
    $succes = 0;
    if (!empty($_POST['addition'])) {
      $Addition = cleanInput($_POST['addition']);
      $succes = addTempAddition($dbh, $Addition, $_SESSION['ID']);
      if ($succes == 0){
        $formErr = true;
        $GeneralErr = "Er ging iets fout. Probeer het later opnieuw.";
      }
    } else {
      $succes = 1;
    }
  }

  if($succes == 1) {
    $succes = 0;
    if (!empty($_POST['postalcode'])) {
      $PostalCode = cleanInput($_POST['postalcode']);
      $succes = addTempPostalCode($dbh, $PostalCode, $_SESSION['ID']);
      if ($succes == 0){
        $formErr = true;
        $GeneralErr = "Er ging iets fout. Probeer het later opnieuw.";
      }
    } else {
      $succes = 1;
    }
  }

  if ($succes == 1) {
    $succes = 0;
    if (!empty($_POST['city'])) {
      $City = cleanInput($_POST['city']);
      $succes = addTempCity($dbh, $City, $_SESSION['ID']);
      if ($succes == 0){
        $formErr = true;
        $GeneralErr = "Er ging iets fout. Probeer het later opnieuw.";
      }
    } else {
      $succes = 1;
    }
  }

  if ($succes == 1) {
    $succes = 0;
    if (!empty($_POST['country'])) {
      $Country = cleanInput($_POST['country']);
      $succes = addTempCountry($dbh, $Country, $_SESSION['ID']);
      if ($succes == 0){
        $formErr = true;
        $GeneralErr = "Er ging iets fout. Probeer het later opnieuw.";
      }
    } else {
      $succes = 1;
    }
  }

  if ($succes == 1) {
    header("Location: ".getProtocol() . $_SERVER['SERVER_NAME'] . "/registreren/registreren_check.php");
  } else {
    //$showErr = 'style="display: block"';
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
  <title>Eenmaal Andermaal</title>
</head>

<body>
  <?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/header.php");?>

  <div class="row">
    <div class="column">
      <div class="register">
        <form class="callout text-center" action="" method="post">
          <h2>Registreren</h2>
          <div class="callout sand-background"><h6> Email &nbsp; > &nbsp; Controle code &nbsp; > &nbsp; Accountgegevens &nbsp; > &nbsp; <strong><u>Persoonsgegevens</u></strong> &nbsp; > &nbsp; Check gegevens </h6></div>
          <hr>
          <div class="column">
            <p class="form-error" style="display: block"><?php echo $GeneralErr; ?>
            <p class="text-left bold">Onderstaande gegevens zijn optioneel.<br>
              Wanneer u een item koopt kunnen deze gegevens voor u ingevuld worden.</p>
            </div>
            <div class="grid-x grid-padding-x">
              <div class="medium-6 cell">
                <input type="text" name="firstname" placeholder="Voornaam (Optioneel)" value="<?php echo $FirstName?>">
                <p class="form-error" style="display: block"><?php echo $firstNameErr; ?></p>
              </div>
              <div class="medium-6 cell">
                <input type="text" name="lastname" placeholder="Achternaam (Optioneel)" value="<?php echo $LastName;?>">
                <p class="form-error" style="display: block"><?php echo $lastNameErr; ?></p>
              </div>
            </div>
            <div class="column">
              <div class="floated-label-wrapper">
                <input type="date" name="birthday" placeholder="Geboortedatum (Optioneel)" value="<?php echo $Birthday; ?>" max="<?php echo date('Y-m-d', time()) ?>">
                <p class="form-error" style="display: block"><?php echo $birthdayErr; ?></p>
                <input type="number" name="phone1" placeholder="Telefoonnummer 1 (Optioneel)" value="<?php echo $Phone1; ?>">
                <p class="form-error" style="display: block"><?php echo $phone1Err; ?></p>
                <input type="number" name="phone2" placeholder="Telefoonnummer 2 (Optioneel)" value="<?php echo $Phone2; ?>">
                <p class="form-error" style="display: block"><?php echo $Phone2Err; ?></p>
                <input type="text" name="adress" placeholder="Straat en huisnummer (Optioneel)" value="">
                <p class="form-error" style="display: block"><?php echo $adressErr; ?></p>
                <input type="text" name="addition" placeholder="Toevoeging (Optioneel)" value="<?php echo $Addition; ?>">
                <p class="form-error" style="display: block"><?php echo $adressAddErr; ?></p>
                <input type="text" name="postalcode" placeholder="Postcode (Optioneel)" value="<?php echo $PostalCode; ?>">
                <p class="form-error" style="display: block"><?php echo $postalCodeErr; ?></p>
                <input type="text" name="city" placeholder="Plaatsnaam (Optioneel)" value="<?php $City; ?>">
                <p class="form-error" style="display: block"><?php echo $cityErr; ?></p>
                <input type="text" name="country" placeholder="Land (Optioneel)" value="<?php echo $Country; ?>">
                <p class="form-error" style="display: block"><?php echo $countryErr; ?></p>
              </div>
              <div class="floated-label-wrapper">
                <input type="submit" name="verstuur4" class="button round" value="Volgende">
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

<?php
// var_dump($_POST);
// require_once("./db_connector.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/db_connector.php");
require_once($_SERVER["DOCUMENT_ROOT"] . '/include/globalFunctions.inc.php');

$firstName = $lastName = $address = $postalcode = $place = $country = $emailErr = $birthday = "";

$formErr = false;

checkSession();
if(empty($_SESSION['UserAccount']) || $_SESSION['UserAccount']['LoggedIn'] == false) {
  header("Location: ../");
}



try {
  // var_dump($firstName);
  $stmt = $dbh->prepare('SELECT * FROM Gebruiker WHERE gebruikersnaam = :user');
  $prm = array(
    ':user'         => $_SESSION['UserAccount']['UserName']
  );
  $stmt->execute($prm);
  $data = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $Ret = 0;
  $data = false;
}

if($data != false) {
  $firstName  = $data['voornaam'];
  $lastName   = $data['achternaam'];
  $address    = $data['adres'];
  $postalcode = $data['postcode'];
  $place      = $data['plaatsnaam'];
  $country    = $data['land'];
  $email      = $data['mailbox'];
  $birthday   = $data['geboortedag'];
}

if (empty($editButton)) {
  $editButton = false;
}

if(isset($_POST['editButton'])) {
  $editable = '';
  $editButton = true;
}  else {
  $editable = 'disabled';
}
// var_dump($email);
if (isset($_POST['removeButton'])) {
  header("Location: ./verwijderGebruiker.php");
}

//email wijzigen moet gevraagt worden of het nieuwe email geferiveerd is.

if (isset($_POST['submit'])) {
  $userName   = $_SESSION['UserAccount']['UserName'];
  $firstName  = $_POST['firstname'];
  $lastName   = $_POST['lastname'];
  $adres      = $_POST['adres'];
  $postalcode = $_POST['postalcode'];
  $place      = $_POST['place'];
  $country    = $_POST['country'];
  $birthday   = $_POST['birthdate'];
  $email      = $_POST['email'];

  if (empty($_POST['email']) || $_POST['email'] == "")
  {
    $emailErr = "Email mag niet leeg zijn";
    $formErr = true;
  }
  else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
  {
    $emailErr = "email is niet geldig";
    $formErr = true;
  }

  if($formErr == false) {
    $query = "UPDATE  Gebruiker
    SET   voornaam    = :firstname,
    achternaam  = :lastname,
    adres       = :adres,
    postcode    = :postalcode,
    plaatsnaam  = :place,
    land        = :country,
    geboortedag = :birthday,
    mailbox     = :email
    WHERE gebruikersnaam = :user";
    try {
      // var_dump($firstName);
      $stmt = $dbh->prepare($query);
      $prm = array(
        ':user'         => $userName,
        ':firstname'    => $firstName,
        ':lastname'     => $lastName,
        ':adres'        => $adres,
        ':postalcode'   => $postalcode,
        ':place'        => $place,
        ':country'      => $country,
        ':birthday'     => $birthday,
        ':email'        => $email

      );
      $stmt->execute($prm);
      $Ret = 1;
    } catch (PDOException $e) {
      $Ret = 0;
      print_r($e->errorInfo);
      if($e->errorInfo[0] == 23000 && $e->errorInfo[1] == 2627) {
        $emailErr = "Email adres is al in gebruik";
      } else {
        $emailErr = "Er trad een onbekende fout op. Probeer het later opnieuw";
      }
    }
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
  <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/include/header.php"); ?>
  <div class="row">
    <div class="columns small-12 callout">
      <div class="columns small-12">
        <h2>Persoonlijk</h2>
        Druk op de knop "Wijzigen" onderaan om gegevens aan te passen.
        <br>
        Wanneer uw gegevens correct gewijzigd zijn druk op de knop "Opslaan" om de wijzigingen te bevestigen.
        <hr>
      </div>
      <form action="./index.php"  method="POST">
        <div class="row">

          <div class="columns small-6">
            <p>Voornaam</p>
            <input name="firstname" value="<?php echo $firstName; ?>" type="text" placeholder="Voornaam" <?php echo $editable ?>>

          </div>
          <div class="columns small-6">
            <p>Achternaam</p>
            <input name="lastname" value="<?php echo $lastName; ?>" type="text" placeholder="Achternaam" <?php echo $editable ?>>
          </div>
          <div class="columns small-6">
            <p>Email *</p>
            <input value="<?php echo $email; ?>" name="email" type="text" placeholder="Email@eenmaalandermaal.com" <?php echo $editable ?> required>
            <p class="form-error" style="display: block"><?php echo $emailErr; ?></p>
          </div>
          <div class="columns small-2">
            <p>Geboortedatum</p>
            <input value="<?php echo $birthday; ?>" name="birthdate" type="date" <?php echo $editable ?>>
          </div>
          <div class="columns small-2">
            <p>Telefoonnummer</p>
            <input type="text" placeholder="+31612345678" <?php echo $editable ?>>
          </div>
          <div class="columns small-2">
            <p>Telefoonnummer 2</p>
            <input type="text" placeholder="+31612345678" <?php echo $editable ?>>
          </div>
          <div class="columns small-12">
            <h2>Adresgegevens</h2>
            <hr>
          </div>
          <div class="columns small-6">
            <p>Adres</p>
            <input name="adres" value="<?php echo $address; ?>" type="text" placeholder="adreslaan" <?php echo $editable ?>>
          </div>
          <div class="columns small-3">
            <p>Postcode</p>
            <input value="<?php echo $postalcode; ?>"name="postalcode" type="text" placeholder="1234AB" <?php echo $editable ?>>
          </div>
          <div class="columns small-6">
            <p>Plaats</p>
            <input value="<?php echo $place; ?> "name="place" type="text" value="<?php echo $place; ?>" placeholder="Nijmegen" <?php echo $editable ?>>
          </div>
          <div class="columns small-6">
            <p>Land</p>
            <input value="<?php echo $country; ?>" name="country" type="text" placeholder="Nederland" <?php echo $editable ?>>
          </div>
          <div class="columns small-12">
            <?php if ($editButton == false) {
              echo    '<h2>Beoordelingen</h2>
              <hr>
              <p>Je hebt nog geen beoordelingen...</p>
              <hr>';           
            
            }
              ?>
            </div>
            <div class="columns small-12">
              <?php if ($editButton == false) {
                echo '
                <input name="removeButton" class="button alert" type="submit" value="Account verwijderen">
                ';}
                ?>
              </div>
              <?php
              if ($editButton == true) {
                echo '<div class="columns small-12">
                <h6 class="bold">De velden met een * moeten verplicht ingevuld worden.</h6>
                </div>';
              }
              ?>
              <div class="columns small-12">
                <?php if ($editButton == false) {
                  echo '<input name="editButton" class="button round" type="submit" value="Wijzigen" />';
                }
                else if ($editButton == true) {
                  echo '<form action="index.php" method="post">
                  <input name="submit" class="button round" type="submit" value="Opslaan" />
                  </form>';
                }
                ?>
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

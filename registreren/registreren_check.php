<?php
/*
----------TO-DO
Thijs-Jan Guelen 22-05-2019 16:19
+ Inserten van telefoonnummers gaat niet goed.
  Deze worden in de gebruikers tabel ge-insert en
  moeten in de tabel Gebruikertelefoon

+ Telefoonnummers worden geinsert ongeacht of
  de gebruiker er een invulde

+ Nette error afhandeling. De errors worden al
  gegenereerd. Moeten nu nog in een eigen variabele
  en ge-echoed worden onder de juiste input field.
  Emma, da's echt een makkie. Fix ik wel ;)

+ terug knop

+ dat was het volgens mij, Mits emma nog toevoegingen heeft.
*/
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/db_connector.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/globalFunctions.inc.php");
checkSession();

$userdata = getTempInfo($dbh, $_SESSION['ID']);
$error = "";

if(isset($_POST['verstuur5'])){
  $succes = addUserDB($dbh, $userdata['data']['gebruikersnaam'], $userdata['data']['mailbox'],
  $userdata['data']['voornaam'], $userdata['data']['achternaam'], $userdata['data']['geboortedag'],
  $userdata['data']['adres'], $userdata['data']['toevoeging'], $userdata['data']['postcode'],
  $userdata['data']['plaatsnaam'], $userdata['data']['land'], $userdata['data']['wachtwoord']);
  if($succes == 1) {
    $succes = 0;

    /*addUserPhoneDB moet aangepast worden
    Thijs-Jan Guelen 22-05-2019 17:23
    Query moet inserten naar de tabel Gebruikerstelefoon
    Ik heb alleen geen idee wat ik van volgnnr moet maken dus dit skip ik ff
    */
    if($userdata['data']['telefoon1'] != NULL) {
      $succes1 = addUserPhoneDB($dbh, $userdata['data']['gebruikersnaam'],$userdata['data']['telefoon1']);
    } else {
      $succes1 = 1;
    }

    if($userdata['data']['telefoon2'] != NULL) {
      $succes1 = addUserPhoneDB($dbh, $userdata['data']['gebruikersnaam'],$userdata['data']['telefoon2']);
    } else {
      $succes2 = 1;
    }

    if($succes1 == 1 && $succes2 == 1) {
      $succes = 1;
    } else {
      $succes = 0;
      deleteUser($dbh, $userdata['data']['gebruikersnaam']);
    }
    if($succes == 1){
      emptyTempDB($dbh, $_SESSION['ID']);
      header("location: http://".$_SERVER['SERVER_NAME'].'/login');
    } else {
      $error = "Er is een onbekende fout opgetreden, probeer het later nog eens. <br>";
    }
  } else {
    $error = "Er is een onbekende fout opgetreden, probeer het later nog eens. <br>";
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
  <?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/header.php"); ?>
  <div class="row">
    <div class="columns small-12 callout">
      <div class="text-center">
        <h3>Gegevens</h3>
        <div class="callout sand-background"><h6> Email &nbsp; > &nbsp; Controle code &nbsp; > &nbsp; Accountgegevens &nbsp; > &nbsp; Persoonsgegevens &nbsp; > &nbsp; <strong><u>Check gegevens</u></strong> </h6></div>
        <hr>
      </div>
      <form class="text-left" action="" method="post">
        <div class="row">
          <div class="columns small-12">
            <p>
              <?php echo $error ?>
              <strong>Uw gegevens:</strong>
              <br>
              <TABLE>
                <TR> <TD>Email:</TD> <TD><?php echo $userdata['data']['mailbox'] ?></TD> </TR>
                <TR> <TD>Gebruikersnaam:</TD> <TD><?php echo $userdata['data']['gebruikersnaam'] ?></TD> </TR>
                <TR> <TD></TD> </TR>
                <TR> <TD>Naam:</TD> <TD><?php echo $userdata['data']['voornaam'] ?> <?php echo $userdata['data']['achternaam'] ?></TD> </TR>
                <TR> <TD>Geboortedatum:</TD> <TD><?php echo $userdata['data']['geboortedag'] ?></TD> </TR>
                <TR> <TD>Telefoonnummer 1:</TD> <TD><?php echo $userdata['data']['telefoon1'] ?></TD> </TR>
                <TR> <TD>Telefoonnummer 2:</TD> <TD><?php echo $userdata['data']['telefoon2'] ?></TD> </TR>
                <TR> <TD>Adres:</TD> <TD><?php echo $userdata['data']['adres'] ?></TD> </TR>
                <TR> <TD>Toevoeging:</TD> <TD><?php echo $userdata['data']['toevoeging'] ?></TD> </TR>
                <TR> <TD>Postcode:</TD> <TD><?php echo $userdata['data']['postcode'] ?></TD> </TR>
                <TR> <TD>Plaatsnaam:</TD> <TD><?php echo $userdata['data']['plaatsnaam'] ?></TD> </TR>
                <TR> <TD>Land:</TD> <TD><?php echo $userdata['data']['land'] ?></TD> </TR>
              </TABLE>
            </p>
          </div>
          <div class="columns small-12 text-center">
            <input class="button round text-center" type="submit" value="Accepteren" name="verstuur5">
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

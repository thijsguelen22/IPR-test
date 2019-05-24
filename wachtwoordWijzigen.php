<?php
require_once("./db_connector.php");
require_once './globalFunctions.inc.php';


$password = $_POST[''];
$passwordHash = password_hash($password, PASSWORD_DEFAULT);


if(isset($_POST[''])){
  $ret = 0;
//   header("Location: ");
  $userName = $_SESSION['UserAccount']['UserName'];
  $query = "UPDATE  Gebruiker
            SET     wachtwoord     = :passwordHash
            WHERE   gebruikersnaam = :user";
  try {
    $stmt = $dbh->prepare($query);
    $prm = array(':user'=>$userName,'passwordHash'=>$passwordHash);
    $stmt->execute($prm);
    $Ret = 1;
  }
  catch(PDOException $e) {
    $Ret = 0;
  }
}



?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/stylesheet.css">
    <title>Eenmaal Andermaal</title>
</head>

<body>
<?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/header.php"); ?>

<div class="row">
    <div class="column">
        <div class="register">
            <form class="callout text-center" action="">
                <h2>Wijzig uw wachtwoord</h2>
                <hr>
                <div class="column">
                    <div class="column">
                    <strong><p class="text-left">Geef uw nieuwe wachtwoord op </p></strong>
                </div>
                   
                   
                <div class="grid-x grid-padding-x">
                    <div class="medium-6 cell">
                        <input type="password" name="psw1" placeholder="Wachtwoord" required>
                    </div>
                    <div class="medium-6 cell">
                        <input type="password" name="psw2" placeholder="Herhaal wachtwoord" required>
                    </div>
                </div>
                
                <div class="column">
                    <input class="button expanded" type="submit" value="Volgende">
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/footer.php"); ?>

<script src="js/vendor/jquery.js"></script>
<script src="js/vendor/what-input.js"></script>
<script src="js/vendor/foundation.js"></script>
<script src="js/app.js"></script>
</body>

</html>
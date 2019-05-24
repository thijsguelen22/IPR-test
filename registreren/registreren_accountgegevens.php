<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/include/db_connector.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/include/globalFunctions.inc.php");
    checkSession();

$username = $password = $passwordRepeat = $CheckOnErrors = "";
$GeneralErr = $UserErr = $PassErr = $RePassErr = "";

if(isset($_POST['verstuur3'])){
    $username = cleanInput($_POST['username']);
    $password = cleanInput($_POST['psw1']);
    $passwordRepeat = cleanInput($_POST['psw2']);

    // Username unique
    $UniqueName = isUsernameUnique($username, $dbh);
    if($UniqueName['PDORetCode'] == 0) {
        $CheckOnErrors = true;
        $GeneralErr = "Er trad een onbekende fout op. Probeer het later nog eens.";
        $UniqueName = false;
    } else {
        $UniqueName = $UniqueName['unused'];
    }

    // Check username
    if(empty($username)){
        $UserErr = "Kies een gebruikersnaam";
        $CheckOnErrors = true;
    } elseif(!isCharactersOnly($username)){
        $UserErr = "Gebruikersnaam mag alleen uit karakters bestaan.";
        $CheckOnErrors = true;
    } elseif(!$UniqueName){
        $UserErr = "Deze gebruikersnaam bestaat al.";
        $CheckOnErrors = true;
    }

    // Controleer wachtwoord
    if(empty($password)) {
        $PassErr = "Het wachtwoord mag niet leeg zijn.";
        $CheckOnErrors = true;
    } elseif(!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $password)){
        $PassErr = "Het wachtwoord moet zowel letters als cijfers bevatten.";
        $CheckOnErrors = true;
    }

    // Controleer het herhaal paswoord veld
    if(empty($passwordRepeat)){
        $RePassErr = "Herhaal je wachtwoord.";
        $CheckOnErrors = true;
    } elseif($password != $passwordRepeat){
        $RePassErr = "De 2 wachtwoordvelden komen niet met elkaar overeen";
        $CheckOnErrors = true;
    }

    if($CheckOnErrors != true) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $succes = addTempPassword($dbh, $password, $_SESSION['ID']);
        if($succes = 1){
            $succes = addTempUsername($dbh, $username, $_SESSION['ID']);
            if($succes == 1){
                header("location: http://".$_SERVER['SERVER_NAME'].'/registreren/registreren_persoonsgegevens.php');
            } else{
                $GeneralErr = "Er trad een onbekende fout op. Probeer het later nog eens.";
            }
        } else {
            $GeneralErr = "Er trad een onbekende fout op. Probeer het later nog eens.";
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
<?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/header.php"); ?>

<div class="row">
    <div class="column">
        <div class="register">
            <form class="callout text-center" action="" method="post">
                <h2>Registreren</h2>
                <div class="callout sand-background">
                    <h6> Email &nbsp; > &nbsp; Controle code &nbsp; > &nbsp; <strong><u>Accountgegevens</u></strong> &nbsp; > &nbsp; Persoonsgegevens &nbsp; > &nbsp; Check gegevens </h6>
                </div>
                <hr>
                <div class="column">
                    <span style="color:red"> <?php echo $GeneralErr; ?> </span>
                    <span style="color:red"> <?php echo ('<br>' . $UserErr); ?> </span>
                    <span style="color:red"> <?php echo ('<br>' . $PassErr); ?> </span>
                    <span style="color:red"> <?php echo ('<br>' . $RePassErr); ?> </span>
                    <strong><p class="text-left"> Accountgegevens</p></strong>
                    <div class="floated-label-wrapper">
                        <input type="text" name="username" placeholder="Gebruikersnaam" required>
                    </div>
                </div>
                <div class="grid-x grid-padding-x">
                    <div class="medium-6 cell">
                        <input type="password" name="psw1" placeholder="Wachtwoord" required>
                    </div>
                    <div class="medium-6 cell">
                        <input type="password" name="psw2" placeholder="Herhaal wachtwoord" required>
                    </div>
                </div>
                <p class="help-text" id="passwordHelpText">Het wachtwoord moet zowel letters als cijfers bevatten.</p>
                <div class="floated-label-wrapper">
                    <input type="submit" name="verstuur3" class="button round" value="Volgende">
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

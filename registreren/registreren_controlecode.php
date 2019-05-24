<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/include/db_connector.php");
    require_once($_SERVER["DOCUMENT_ROOT"] . "/include/globalFunctions.inc.php");
    checkSession();
    $Error = "";

    // Verwijderen wanneer live

    if(isset($_POST['verstuur2'])){
        $codeInput = cleanInput($_POST['code']);

        $codeDB = getTempCode($dbh, $_SESSION['ID']);
        if($codeDB == 0){
            $Error = "Er is een onbekende fout opgetreden, probeer het later opnieuw.";
        } else {
            if($codeDB['verificatie'] == $codeInput){
                header("location: http://".$_SERVER['SERVER_NAME'].'/registreren/registreren_accountgegevens.php');
            } else {
                $Error = "U heeft waarschijnlijk een typfout gemaakt. Probeer het opnieuw.";
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
<?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/include/header.php");
?>

<div class="row">
    <div class="column">
        <div class="email">
            <form action="" method="POST" class="callout text-center">
                <h2>Registreren</h2>
                <div class="callout sand-background"><h6> Email &nbsp; > &nbsp; <strong><u>Controle code</u></strong> &nbsp; > &nbsp; Accountgegevens &nbsp; > &nbsp; Persoonsgegevens &nbsp; > &nbsp; Check gegevens </h6></div>
                <div class="column">
                    <span style="color:red"> <?php echo $Error ?> </span>
                    <p class="text-left bold"> Vul de controle code in die u via de mail heeft ontvangen. </p>
                    <div class="floated-label-wrapper">
                        <input type="text" name="code" placeholder="Controle code">
                    </div>
                    <div class="floated-label-wrapper">
                        <input type="submit" name="verstuur2" class="button round" value="Volgende">
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

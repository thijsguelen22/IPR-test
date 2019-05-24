<?php
require_once("./db_connector.php");

if(isset($_POST['submit'])) {
    
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
                <h2>Vraag nieuw wachtwoord aan</h2>
                <hr>
                <div class="column">
                    <div class="column">
                    <strong><p class="text-left">Geef uw email op </p></strong>
                
                   
                   
                
                <div class="floated-label-wrapper">
                        <input name="username" value="" placeholder="Gebruikersnaam" type="text">
                        <p class="form-error" style="display: block"></p>
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
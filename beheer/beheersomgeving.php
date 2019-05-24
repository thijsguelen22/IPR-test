<?php
//require_once("./db_connector.php");

if(isset($_POST['rubrieken'])) {
    header("location: http://".$_SERVER['SERVER_NAME'].'/beheer/beheer_rubrieken.php');
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
                <div class="callout text-center">
                    <h2>Beheersomgeving</h2>
                    <hr>
                    <form action="" method="POST">
                    <div class="column">
                        <div class="grid-x grid-padding-x">
                            <div class="medium-6 cell">
                                <div class="column">
                                    <h3 class="text-left">Rubrieken beheren</h3>
                                    <strong>
                                        <p class="text-left">
                                            Ga hier naartoe om de rubrieken op de website te beheren.
                                            Hier kunt u rubrieken toevoegen, hernoemen, sorteren en uitfaseren.
                                        </p>
                                    </strong>
                                    <input class="button expanded" name="rubrieken" type="submit" value="Klik hier">
                                </div>
                            </div>
<!--                            <div class="medium-6 cell">-->
<!--                                <div class="column">-->
<!--                                    <h3 class="text-left"></h3>-->
<!--                                    <strong>-->
<!--                                        <p class="text-left">-->
<!--                                            -->
<!--                                        </p>-->
<!--                                    </strong>-->
<!--                                    <input class="button expanded" name="" type="submit" value="Klik hier">-->
<!--                                </div>-->
<!--                            </div>-->
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
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
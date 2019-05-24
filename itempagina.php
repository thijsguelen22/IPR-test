<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/db_connector.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/globalFunctions.inc.php");
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
    <script src="https://code.jquery.com/jquery-3.4.0.min.js" integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg=" crossorigin="anonymous"></script>
    <title>Eenmaal Andermaal</title>
</head>

<body>
    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/include/header.php"); ?>
    <div class="row columns">
        <nav aria-label="You are here:" role="navigation">
            <ul class="breadcrumbs">
                <li><a href="#">Home</a></li>
                <li><a href="#">Hoofdrubriek</a></li>
                <li class="disabled">subrubriek</li>
                <li>
                    <span class="show-for-sr">Current: </span> Veiling item #
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="medium-6 columns">
        <h3>Veiling item #</h3>
            <img class="thumbnail" src="https://placehold.it/650x350">
            <div class="row small-up-4">
                <div class="column">
                    <img class="thumbnail" src="https://placehold.it/250x200">
                </div>
                <div class="column">
                    <img class="thumbnail" src="https://placehold.it/250x200">
                </div>
                <div class="column">
                    <img class="thumbnail" src="https://placehold.it/250x200">
                </div>
                <div class="column">
                    <img class="thumbnail" src="https://placehold.it/250x200">
                </div>
            </div>
        </div>
        <div class="medium-6 large-5 columns">
        <h3>Biedingen</h3>
         

            <div class="row">
                <div class="small-3 columns">
                    <label for="middle-label" class="middle">bied:</label>
                </div>
                <div class="small-9 columns">
                    <input type="text" id="middle-label" placeholder="bod bedrag">
                </div>
            </div>
            <a href="#" class="button large expanded">Plaats bod</a>
            <div class="small secondary expanded button-group">
                <a class="button">Facebook</a>
                <a class="button">Twitter</a>
            </div>
        </div>
    </div>
    <div class="column row">
        <hr>
        <p>Beschrijving van Veiling item # </p>
        <hr>
        <ul class="tabs" data-tabs id="example-tabs">
            <li class="tabs-title is-active"><a href="#panel1" aria-selected="true">Reviews</a></li>
            <li class="tabs-title"><a href="#panel2">vergelijkbare producten</a></li>
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
            <div class="tabs-panel is-active" id="panel1">
                <h4>Reviews</h4>
                <div class="media-object stack-for-small">
                    <div class="media-object-section">
                        <img class="thumbnail" src="https://placehold.it/200x200">
                    </div>
                    <div class="media-object-section">
                        <h5>Mike Stevenson</h5>
                        <p>I'm going to improvise. Listen, there's something you should know about me... about inception. An idea is like a virus, resilient, highly contagious. The smallest seed of an idea can grow. It can grow to define or destroy you.</p>
                    </div>
                </div>
                <div class="media-object stack-for-small">
                    <div class="media-object-section">
                        <img class="thumbnail" src="https://placehold.it/200x200">
                    </div>
                    <div class="media-object-section">
                        <h5>Mike Stevenson</h5>
                        <p>I'm going to improvise. Listen, there's something you should know about me... about inception. An idea is like a virus, resilient, highly contagious. The smallest seed of an idea can grow. It can grow to define or destroy you</p>
                    </div>
                </div>
                <div class="media-object stack-for-small">
                    <div class="media-object-section">
                        <img class="thumbnail" src="https://placehold.it/200x200">
                    </div>
                    <div class="media-object-section">
                        <h5>Mike Stevenson</h5>
                        <p>I'm going to improvise. Listen, there's something you should know about me... about inception. An idea is like a virus, resilient, highly contagious. The smallest seed of an idea can grow. It can grow to define or destroy you</p>
                    </div>
                </div>
                <label>
                    My Review
                    <textarea placeholder="None"></textarea>
                </label>
                <button class="button">Submit Review</button>
            </div>
            <div class="tabs-panel" id="panel2">
                <div class="row medium-up-3 large-up-5">
                    <div class="column">
                        <img class="thumbnail" src="https://placehold.it/350x200">
                        <h5>vergelijkbare producten <small>$22</small></h5>
                        <p>In condimentum facilisis porta. Sed nec diam eu diam mattis viverra. Nulla fringilla, orci ac euismod semper, magna diam.</p>
                        <a href="#" class="button hollow tiny expanded">Buy Now</a>
                    </div>
                    <div class="column">
                        <img class="thumbnail" src="https://placehold.it/350x200">
                        <h5>Other Product <small>$22</small></h5>
                        <p>In condimentum facilisis porta. Sed nec diam eu diam mattis viverra. Nulla fringilla, orci ac euismod semper, magna diam.</p>
                        <a href="#" class="button hollow tiny expanded">Buy Now</a>
                    </div>
                    <div class="column">
                        <img class="thumbnail" src="https://placehold.it/350x200">
                        <h5>Other Product <small>$22</small></h5>
                        <p>In condimentum facilisis porta. Sed nec diam eu diam mattis viverra. Nulla fringilla, orci ac euismod semper, magna diam.</p>
                        <a href="#" class="button hollow tiny expanded">Buy Now</a>
                    </div>
                    <div class="column">
                        <img class="thumbnail" src="https://placehold.it/350x200">
                        <h5>Other Product <small>$22</small></h5>
                        <p>In condimentum facilisis porta. Sed nec diam eu diam mattis viverra. Nulla fringilla, orci ac euismod semper, magna diam.</p>
                        <a href="#" class="button hollow tiny expanded">Buy Now</a>
                    </div>
                    <div class="column">
                        <img class="thumbnail" src="https://placehold.it/350x200">
                        <h5>Other Product <small>$22</small></h5>
                        <p>In condimentum facilisis porta. Sed nec diam eu diam mattis viverra. Nulla fringilla, orci ac euismod semper, magna diam.</p>
                        <a href="#" class="button hollow tiny expanded">Buy Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php require_once($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php"); ?>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>
</body>

</html>
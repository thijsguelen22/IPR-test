<?php
  //require_once("./db_connector.php");


  $value = !empty($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
  if($value == "Laat me") {
    /*echo '<style>
    audio {
      display:none !important;
    }
    </style>
    <audio loop controls autoplay>
    <source type="audio/mpeg" src="https://iproject36.icasites.nl/include/rs.mp3">
    </audio>';*/
  } else if ($value == "" || empty($_POST['searchQuery'])) {
    header("Location: ./");
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
<?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/header.php");  ?>
    <div class="row collapse zoekresultaten">

        <div class="column">
            <?php
            $postalCodeSearch = $_POST['postalCode'];
            //$items = getAllItems($dbh);
            $items = getTwentyItems($dbh, $value);
            $html = "<div class=\"row\">";
            $storedDistance = array();
            foreach($items['data'] as $item){
                /*if(empty($storedDistance[$item['plaatsnaam']])) {
                    $distance = checkDistance($dbh, $postalCodeSearch, $item['plaatsnaam']);
                    $storedDistance[$item['plaatsnaam']] = $distance;
                } else {
                    $distance = $storedDistance[$item['plaatsnaam']];
                }*/
                //$image = getItemImage($dbh, $item['voorwerpnummer']);
                //if($distance < $_POST['dist']){
                $distance = 'nvt';
                $desc = strlen($item['beschrijving']) > 250 ? substr($item['beschrijving'],0,250)."..." : $item['beschrijving'];
                    $html.=
                        '<div class="small-3">
                            <div class="product-card callout">
                                <div class="product-card-thumbnail">
                                    <a href="#"><img src="https://placehold.it/180x180" alt="foto"/></a>
                                </div>
                                <h2 class="product-card-title"><a href="#">' . $item['titel'] . '</a></h2>
                                <span class="product-card-desc">' . $desc . '</span>
                                <span class="product-card-price"> Hoogste bod: €' . $item['verkoopprijs'] . '</span>
                                <span class="product-card-desc"> Eindigt: ' . $item['looptijdeindedag'] . '</span>
                                <span class="product-card-desc"> Afstand: ' . round($distance) . ' km </span>
                                <div class="product-card-colors">
                                  <button href="#" class="product-card-color-option"><img src="https://placehold.it/30x30"/></button>
                                  <button href="#" class="product-card-color-option"><img src="https://placehold.it/30x30"/></button>
                                  <button href="#" class="product-card-color-option"><img src="https://placehold.it/30x30"/></button>
                                </div>
                            </div>
                        </div>';

            }
            $html.='</div>';
            echo $html;
            ?>

            </div>
    </div>

    <?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/footer.php"); ?>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>
</body>

</html>

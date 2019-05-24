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
  <script
  src="https://code.jquery.com/jquery-3.4.0.min.js"
  integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg="
  crossorigin="anonymous"></script>
  <title>Eenmaal Andermaal</title>
</head>
<body>
  <?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/header.php"); ?>
  <div class="row">
    <div class="column">
        <div class="row">
        <div class="column">
            <div class="row">
                <div class="column">
                <h3>Koopjes</h3>
                <hr>
            </div>
        </div>

          <?php

          $html="<div class=\"row\">";

          $cheapestItems = fourCheapestItem($dbh);
          foreach($cheapestItems['data'] as $item){
            $rnd = rand(1, 10);
            $desc = strlen($item['beschrijving']) > 250 ? substr($item['beschrijving'],0,250)."..." : $item['beschrijving'];
              $html.=
                  '<div class="small-6">
                        <div class="product-card callout">
                                <div class="product-card-thumbnail">
                                    <a href="#"><img src="./images/'.$rnd.'.jpg" alt="foto"/></a>
                                </div>
                                <h2 class="product-card-title"><a href="#">' . $item['titel'] . '</a></h2>
                                <span class="product-card-desc">' . $desc . '</span>
                                <span class="product-card-price"> Hoogste bod: €' . $item['verkoopprijs'] . '</span>
                                <span class="product-card-desc"> Eindigt: ' . $item['looptijdeindedag'] . '</span>
                                <div class="product-card-colors">
                                  <button href="#" class="product-card-color-option"><img src="./images/'.$rnd.'.jpg"/></button>
                                  <button href="#" class="product-card-color-option"><img src="./images/'.$rnd.'.jpg"/></button>
                                  <button href="#" class="product-card-color-option"><img src="./images/'.$rnd.'.jpg"/></button>
                                </div>
                          </div>
                      </div>';
          }
          $html.="</div>
                    </div>";

          $html.="<div class=\"column homepagina\">
                    <div class=\"row\">
                        <div class=\"column\">
                            <h3>Eindigt</h3>
                            <hr>
                        </div>
                    </div>
                    <div class=\"row\">";

          $expiringItems = fourExpiringItems($dbh);
          foreach($expiringItems['data'] as $item){
            $rnd = rand(1, 10);
            $desc = strlen($item['beschrijving']) > 250 ? substr($item['beschrijving'],0,250)."..." : $item['beschrijving'];
              $html.=
                  '<div class="small-6">
                            <div class="product-card callout">
                                <div class="product-card-thumbnail">
                                    <a href="#"><img src="./images/'.$rnd.'.jpg" alt="foto"/></a>
                                </div>
                                <h2 class="product-card-title"><a href="#">' . $item['titel'] . '</a></h2>
                                <span class="product-card-desc">' . $desc . '</span>
                                <span class="product-card-price"> Hoogste bod: €' . $item['verkoopprijs'] . '</span>
                                <span class="product-card-desc"> Eindigt: ' . $item['looptijdeindedag'] . '</span>
                                <div class="product-card-colors">
                                  <button href="#" class="product-card-color-option"><img src="./images/'.$rnd.'.jpg"/></button>
                                  <button href="#" class="product-card-color-option"><img src="./images/'.$rnd.'.jpg"/></button>
                                  <button href="#" class="product-card-color-option"><img src="./images/'.$rnd.'.jpg"/></button>
                                </div>
                            </div>
                      </div>';
          }

          $html.="</div>
                   </div>";

          echo $html;
          ?>

<!--      <div class="row">-->
<!--          <div class="small-6">-->
<!--          <div class="product-card">-->
<!--            <div class="product-card-thumbnail">-->
<!--              <a href="#"><img src="https://placehold.it/180x180"/></a>-->
<!--            </div>-->
<!--            <h2 class="product-card-title"><a href="#">Product Name</a></h2>-->
<!--            <span class="product-card-desc">Product Description</span>-->
<!--            <span class="product-card-price">$9.99</span><span class="product-card-sale">$12.99</span>-->
<!--            <div class="product-card-colors">-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="small-6">-->
<!--          <div class="product-card">-->
<!--            <div class="product-card-thumbnail">-->
<!--              <a href="#"><img src="https://placehold.it/180x180"/></a>-->
<!--            </div>-->
<!--            <h2 class="product-card-title"><a href="#">Product Name</a></h2>-->
<!--            <span class="product-card-desc">Product Description</span>-->
<!--            <span class="product-card-price">$9.99</span><span class="product-card-sale">$12.99</span>-->
<!--            <div class="product-card-colors">-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--    <div class="column">-->
<!--      <div class="row">-->
<!--        <div class="column">-->
<!--          <h3>Eindigt</h3>-->
<!--          <hr>-->
<!--        </div>-->
<!--      </div>-->
<!--      <div class="row">-->
<!--        <div class="small-6">-->
<!--          <div class="product-card">-->
<!--            <div class="product-card-thumbnail">-->
<!--              <a href="#"><img src="https://placehold.it/180x180"/></a>-->
<!--            </div>-->
<!--            <h2 class="product-card-title"><a href="#">Product Name</a></h2>-->
<!--            <span class="product-card-desc">Product Description</span>-->
<!--            <span class="product-card-price">$9.99</span><span class="product-card-sale">$12.99</span>-->
<!--            <div class="product-card-colors">-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div class="small-6">-->
<!--          <div class="product-card">-->
<!--            <div class="product-card-thumbnail">-->
<!--              <a href="#"><img src="https://placehold.it/180x180"/></a>-->
<!--            </div>-->
<!--            <h2 class="product-card-title"><a href="#">Product Name</a></h2>-->
<!--            <span class="product-card-desc">Product Description</span>-->
<!--            <span class="product-card-price">$9.99</span><span class="product-card-sale">$12.99</span>-->
<!--            <div class="product-card-colors">-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--        <div class="row">-->
<!--        <div class="small-6">-->
<!--          <div class="product-card">-->
<!--            <div class="product-card-thumbnail">-->
<!--              <a href="#"><img src="https://placehold.it/180x180"/></a>-->
<!--            </div>-->
<!--            <h2 class="product-card-title"><a href="#">Product Name</a></h2>-->
<!--            <span class="product-card-desc">Product Description</span>-->
<!--            <span class="product-card-price">$9.99</span><span class="product-card-sale">$12.99</span>-->
<!--            <div class="product-card-colors">-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--              <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        </div>-->
<!--      </div>-->
        </div>
    </div>
    </div>
  </div>

<!--  <div class="row">-->
<!--    <div class="column">-->
<!--      <h3>Koopjes</h3>-->
<!--      <hr>-->
<!--    </div>-->
<!--  </div>-->

<!--  <div class="row">-->
<!---->
<!--    --><?php
//    $allData = getAllItems($dbh);
//    $rnd = 'i'.rand(1, 3);
//    $i1 = "https://placehold.it/180x180";
//    $i2 = "https://placehold.it/180x180";
//$i3 = "https://placehold.it/180x180";
//    foreach($allData['data'] as $var) {
//      $rnd = 'i'.rand(1, 3);
//     ?>
<!--    <div class="column small-3">-->
<!--      <div class="product-card">-->
<!--        <div class="product-card-thumbnail">-->
<!--          <a href="#"><img src="--><?php //echo $$rnd; ?><!--"/></a>-->
<!--        </div>-->
<!--        <h2 class="product-card-title"><a href="#">--><?php //echo $var['titel']; ?><!--</a></h2>-->
<!--        <span class="product-card-desc">--><?php //echo $var['beschrijving']; ?><!--</span>-->
<!--        <span class="product-card-price">--><?php //echo $var['verkoopprijs']; ?><!--</span>-->
<!--        <div class="product-card-colors">-->
<!--          <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--          <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--          <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--          <button href="#" class="product-card-color-option"><img src="https://placehold.it/60x60"/></button>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--  --><?php //} ?>
<!--  </div>-->


  <?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/footer.php"); ?>

  <script src="/js/vendor/jquery.js"></script>
  <script src="/js/vendor/what-input.js"></script>
  <script src="/js/vendor/foundation.js"></script>
  <script src="/js/app.js"></script>
</body>
</html>

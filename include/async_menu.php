<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/include/globalFunctions.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/db_connector.php");


$DBMainCategories = getMainCategories($dbh);
?>


<ul id="asyncMenu" class="vertical dropdown menu">
  <?php
  $html='<script src="/js/vendor/jquery.js"></script>
  <script src="/js/vendor/what-input.js"></script>
  <script src="/js/vendor/foundation.js"></script>
  <script src="/js/app.js"></script>';
  foreach ($DBMainCategories["data"] as $mainCategory) {
    $html.="<li><a href=\"#\">".$mainCategory["naam"]."</a>";

    $html.="<ul class=\"vertical menu nested\">";
    $DBSubCategories = getSubCategories($dbh, $mainCategory["nummer"]);
    foreach($DBSubCategories["data"] as $subCategory) {
      $html.="<li><a href=\"#\">".$subCategory["naam"]."</a>";

      $DBRubrieken = getSubCategories($dbh, $subCategory["nummer"]);
      if (count($DBRubrieken["data"]) > 0) {
        $html.="<ul class=\"vertical menu nested\">";
        foreach ($DBRubrieken["data"] as $Rubriek) {
          $html .= "<li><a href=\"#\">" . $Rubriek["naam"] . "</a></li>";
        }
        $html .= "</ul>";
      }
      $html.="</li>";

    }
    $html.="</ul>";
  }
  //$html .='</ul><script src="/js/vendor/jquery.js"></script>
  //<script src="/js/vendor/what-input.js"></script>
  //<script src="/js/vendor/foundation.js"></script>
  //<script src="/js/app.js"></script>';
  echo $html;
?>

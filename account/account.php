<?php

require_once '../include/db_connector.php';
require_once '../include/globalFunctions.inc.php';
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
  <?php require_once '../include/header.php'; ?>
  <div class="row">
    <div class="columns small-12 callout">
      <div class="columns small-12">
        <h4>
          <ul class="menu">
              <li class="is-active"><a>Account</a></li>
              <li><a href="index.php">Gegevens</a></li>
              <li><a></a></li>
          </ul>
        </h4>

        <hr>
          <div class="columns small-12 text-center">
              <p> Verkoper worden? Heel simpel! <br> Klik op onderstaande knop en volg de stappen.</p>
          <form class="align-center" action="" method="post">
              <input name="submit" class="button round" type="submit" value="Verkoper worden" />
          </form>
          </div>

          <hr>

          <div class="columns small-12">
              <h2>Veilingen</h2>
              <p> hier komen veilingen te staan waar u op heeft geboden. </p>
          </div>




      </div>
    </div>
  </div>

      <?php require_once '../include/footer.php'; ?>

      <script src="/js/vendor/jquery.js"></script>
      <script src="/js/vendor/what-input.js"></script>
      <script src="/js/vendor/foundation.js"></script>
      <script src="/js/app.js"></script>
    </body>

    </html>

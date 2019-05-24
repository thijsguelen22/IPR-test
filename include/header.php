<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/include/globalFunctions.inc.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/include/db_connector.php");

checkSession();

$searchPostalCode = "";
$DBMainCategories = getMainCategories($dbh);
$catDropDownData = CategoriesDropdown($DBMainCategories, $dbh, false);
if(isset($_SESSION['UserAccount']) && $_SESSION['UserAccount']['LoggedIn']) {
  $searchPostalCode = $_SESSION['UserAccount']['PostalCode'];
}
?>


<script
src="https://code.jquery.com/jquery-3.4.0.min.js"
integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg="
crossorigin="anonymous"></script>
<div class="header-form-wrapper">
<form class="header-form" action="<?php echo getProtocol().$_SERVER['SERVER_NAME'];?>/zoekresultaten.php" method="post">
  <div class="top-bar darkgreen-background">
    <div class="top-bar-center">
        <a href="<?php echo getProtocol().$_SERVER['SERVER_NAME']; ?>/"><img src="<?php echo getProtocol().$_SERVER['SERVER_NAME'];?>/images/logo/logo.png" alt="Logo" class="logo"></a>
    </div>
    <div class="top-bar-right">
      <ul class="menu">
        <li><input name="searchQuery" type="search" class="no-spacing" placeholder="Zoeken"></li>
      </ul>
    </div>
    <div class="top-bar-center">
      <ul class="menu">
        <li><input name="postalCode" value="<?php echo $searchPostalCode;?>" class="postcode-field no-spacing" type="text" maxlength="6" placeholder="Postcode"></li>
        <li>
          <select name="dist" class="dropdown">
              <option value="0" selected>Afstand</option>
              <option value="3">&lt; 3 km</option>
              <option value="5">&lt; 5 km</option>
              <option value="10">&lt; 10 km</option>
              <option value="15">&lt; 15 km</option>
              <option value="25">&lt; 25 km</option>
              <option value="50">&lt; 50 km</option>
              <option value="75">&lt; 75 km</option>
          </select>
        </li>
        <li><input type="submit" value="Zoek" class="button"/></li>
      </ul>
    </div>
  </div>
  <div class="top-bar green-background">
    <div class="top-bar-left">
      <ul class="dropdown menu responsive-menu" data-dropdown-menu>
        <li class="menu-text">EenmaalAndermaal</li>
        <li>
          <a href="#">Categorieën</a>
          <ul id="asyncMenu" class="vertical dropdown menu">
          </ul>
        </li>
        <li><a href="#">Over iConcepts</a></li>
      </ul>
    </div>
    <div class="top-bar-righ">
      <ul class="dropdown menu">
        <?php if(isset($_SESSION['UserAccount']) &&  $_SESSION['UserAccount']['LoggedIn']) { ?>
        <li><a href="<?php echo getProtocol().$_SERVER['SERVER_NAME']; ?>/account">Account</a></li>
        <li><a href="<?php echo getProtocol().$_SERVER['SERVER_NAME']; ?>/logout">Uitloggen</a></li>
      <?php } else {?>
        <li><a href="<?php echo getProtocol().$_SERVER['SERVER_NAME']; ?>/login">Inloggen</a></li>
        <li><a href="<?php echo getProtocol().$_SERVER['SERVER_NAME']; ?>/registreren/registreren.php">Registreren</a></li>
      <?php } ?>
      </ul>
    </div>
  </div>
</form>
</div>
<script>
$( "#mainCatDD" ).on("change", function(){
  var allSubs = '<select id="replaceableCatDD" class="item-spacing dropdown"><option value="" selected>Alle rubrieken</option></select>';

  var selectedVal = $( '#mainCatDD' ).val();
  var cat = $( '#mainCatDD' ).find(":selected").attr('class');
  if(cat == 'subCat') {
    $.get("./include/asyncDropdown.php?Location=search&ID="+selectedVal, function( newDD ) {
      $( "#replaceableCatDD" ).replaceWith(newDD);
    }, 'html');
  } else {
    $( "#replaceableCatDD" ).replaceWith(allSubs);
  }
});
</script>

<script>
$( window ).ready(function() {
  //console.log(window.location.protocol + "//" + window.location.host + "/");
  $.get(window.location.protocol + "//" + window.location.host + "/include/async_menu.php", function( menu ) {
    $( "#asyncMenu" ).replaceWith(menu);
  }, 'html')
  .done(function() {
    //$("#asyncMenu").css("display", "block");
  });
});

</script>
<br>

<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/include/globalFunctions.inc.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/include/db_connector.php');

$DBMainCategories = getMainCategories($dbh);
$skipMain         = true;
$catDropDownData  = CategoriesDropdown($DBMainCategories, $dbh, $skipMain);

$mainDD = mainCategoriesDropdown($DBMainCategories);

$newCatErr      = "new cat";
$subCatErr      = "sub cat";
$newRubriekErr  = "new rubriek";
$addMainCatErr  = "";
$saveReturn     = "";
$addSubCatDDErr = $addSubCatErr = $addRubriekErr = $addRubriekSubCatDDErr = $addRubriekMainCatDDErr = "";
$subCat = $mainCatName = $rubriek = "";

$renameMainCatDDErr = $renameMainCatErr = $renameMainCat = "";
$renameSubCatMainCatDDErr = $renameSubCat = $renameSubCatErr = $renameSubCatDDErr = "";
$renameRubriek = $renameRubriekErr = $renameRubriekDDErr = $renameRubriekRubriekDDErr = $sortErr = "";



$unhideAccordeon = "";

$formErr = false;

function createAccordeonUnfold($listItem, $label) {
  $ret ='<script>
  $( document ).ready(function() {
    $("#'.$listItem.'").find("#'.$label.'").attr("aria-expanded","true");
    $("#'.$listItem.'").find("#'.$label.'").attr("aria-selected","true");
    $("#'.$listItem.'").find("div[aria-labelledby='."'$label'".']").attr("aria-hidden","false");
    $("#'.$listItem.'").find("div[aria-labelledby='."'$label'".']").attr("style","display: block");
    $("#'.$listItem.'").attr("class","accordion-item is-active");
  });
  </script>';
  return $ret;
}
echo createAccordeonUnfold("add-acc", "add-acc-label").createAccordeonUnfold("add-main-acc", "add-main-acc-label");

if(isset($_POST['addMainCatBtn'])) {
  $mainCatName = htmlspecialchars(!empty($_POST['addMainCatName']) ? $_POST['addMainCatName'] : '');
  if(!isCategoryUnique($dbh, $mainCatName)['unique']) {
    $formErr          = true;
    $addMainCatErr    = "Categorie komt al voor";
    $unhideAccordeon  = createAccordeonUnfold("add-acc", "add-acc-label").createAccordeonUnfold("add-main-acc", "add-main-acc-label");
  } else if($mainCatName == "" || $mainCatName == NULL) {
    $formErr          = true;
    $addMainCatErr    = "Categorie kan niet leeg zijn";
    $unhideAccordeon  = createAccordeonUnfold("add-acc", "add-acc-label").createAccordeonUnfold("add-main-acc", "add-main-acc-label");
  }
  if($formErr == false) {
    if(insertNewCategory($dbh, $mainCatName)['PDORetCode'] == 1) {
      $saveReturn = "<h4>Categorie succesvol opgeslagen</h4>";
    } else {
      $saveReturn = '<h4 style="color: red;">Er ging iets fout. Probeer het later opnieuw.</h4>';
    }
  }
} else if(isset($_POST['addSubCatBtn'])) {
  $selectedMainCat  = !empty($_POST['addSubCatMainCatDD']) ? $_POST['addSubCatMainCatDD'] : '';
  $subCat           = htmlspecialchars(!empty($_POST['addSubCatInput']) ? $_POST['addSubCatInput'] : '');
  if(empty($_POST['addSubCatMainCatDD']) || $selectedMainCat == "") {
    $formErr          = true;
    $addSubCatDDErr   = "Selecteer een hoofd categorie";
    $unhideAccordeon  = createAccordeonUnfold("add-acc", "add-acc-label").createAccordeonUnfold("add-sub-acc", "add-sub-acc-label");
  }
  if(empty($_POST['addSubCatInput']) || $subCat == "") {
    //var_dump($_POST);
    //echo '<br>'.$subCat.'</br>';
    $formErr          = true;
    $unhideAccordeon  = createAccordeonUnfold("add-acc", "add-acc-label").createAccordeonUnfold("add-sub-acc", "add-sub-acc-label");
    $addSubCatErr     = "Vul een naam in";
  }
  if($formErr == false) {
    if(insertNewSubCategory($dbh, $selectedMainCat, $subCat)['PDORetCode'] == 1) {
      $saveReturn = "<h4>Sub categorie succesvol opgeslagen</h4>";
    } else {
      $saveReturn = '<h4 style="color: red;">Er ging iets fout. Probeer het later opnieuw.</h4>';
    }
  }
} else if(isset($_POST['addRubriekBtn'])) {
  $selectedMainCat  = !empty($_POST['addRubriekMainCatDD']) ? $_POST['addRubriekMainCatDD'] : '';
  $selectedSubCat   = !empty($_POST['addRubriekSubCatDD']) ? $_POST['addRubriekSubCatDD'] : '';
  $rubriek          = htmlspecialchars(!empty($_POST['rubriekName']) ? $_POST['rubriekName'] : '');

  if(empty($_POST['addRubriekMainCatDD']) || $selectedMainCat == "") {
    $formErr                = true;
    $addRubriekMainCatDDErr = "Selecteer een hoofd categorie";
    $unhideAccordeon        = createAccordeonUnfold("add-acc", "add-acc-label").createAccordeonUnfold("add-rubriek-acc", "add-rubriek-acc-label");
  }
  if(empty($_POST['addRubriekSubCatDD']) || $selectedSubCat == "") {
    $formErr                = true;
    $unhideAccordeon        = createAccordeonUnfold("add-acc", "add-acc-label").createAccordeonUnfold("add-rubriek-acc", "add-rubriek-acc-label");
    $addRubriekSubCatDDErr  = "Selecteer een sub categorie";
  }
  if(empty($_POST['rubriekName']) || $rubriek == "") {
    $formErr          = true;
    $unhideAccordeon  = createAccordeonUnfold("add-acc", "add-acc-label").createAccordeonUnfold("add-rubriek-acc", "add-rubriek-acc-label");
    $addRubriekErr    = "Vul een naam in";
  }

  if($formErr == false) {
    if(insertNewSubCategory($dbh, $selectedSubCat, $rubriek)['PDORetCode'] == 1) {
      $saveReturn = "<h4>Rubriek succesvol opgeslagen</h4>";
    } else {
      $saveReturn = '<h4 style="color: red;">Er ging iets fout. Probeer het later opnieuw.</h4>';
    }
  }
}

if(isset($_POST['renameMainCatBtn'])) {
  $selectedMainCat  = !empty($_POST['renameMainCatDD']) ? $_POST['renameMainCatDD'] : '';
  $renameMainCat    = htmlspecialchars(!empty($_POST['renameMainCatInput']) ? $_POST['renameMainCatInput'] : '');

  if(empty($_POST['renameMainCatInput']) || $renameMainCat == "") {
    $formErr            = true;
    $unhideAccordeon    = createAccordeonUnfold("mod-acc", "mod-acc-label").createAccordeonUnfold("mod-main-acc", "mod-main-acc-label");
    $renameMainCatErr   = "Vul een nieuwe naam in";
  } if(empty($_POST['renameMainCatDD']) || $selectedMainCat == "") {
    $formErr            = true;
    $unhideAccordeon    = createAccordeonUnfold("mod-acc", "mod-acc-label").createAccordeonUnfold("mod-main-acc", "mod-main-acc-label");
    $renameMainCatDDErr = "Kies een categorie";
  }

  if($formErr == false) {
    if(changeCategoryName($dbh, $renameMainCat, $selectedMainCat)['PDORetCode'] == 1) {
      $saveReturn = "<h4>Wijziging succesvol opgeslagen</h4>";
    } else {
      $saveReturn = '<h4 style="color: red;">Er ging iets fout. Probeer het later opnieuw.</h4>';
    }
  }
}
if(isset($_POST['renameSubCatBtn'])) {
  $selectedMainCat  = !empty($_POST['renameSubCatMainCatDD']) ? $_POST['renameSubCatMainCatDD'] : '';
  $selectedSubCat   = !empty($_POST['renameSubCatDD']) ? $_POST['renameSubCatDD'] : '';
  $renameSubCat     = htmlspecialchars(!empty($_POST['renameSubCatInput']) ? $_POST['renameSubCatInput'] : '');

  if(empty($_POST['renameSubCatMainCatDD']) || $selectedMainCat == "") {
    $formErr                    = true;
    $unhideAccordeon            = createAccordeonUnfold("mod-acc", "mod-acc-label").createAccordeonUnfold("mod-sub-acc", "mod-sub-acc-label");
    $renameSubCatMainCatDDErr   = "Kies een categorie";
  } if(empty($_POST['renameSubCatDD']) || $selectedSubCat == "") {
    $formErr            = true;
    $unhideAccordeon    = createAccordeonUnfold("mod-acc", "mod-acc-label").createAccordeonUnfold("mod-sub-acc", "mod-sub-acc-label");
    $renameSubCatDDErr  = "Kies een categorie";
  } if(empty($_POST['renameSubCatInput']) || $renameSubCat == "") {
    $formErr = true;
    $unhideAccordeon = createAccordeonUnfold("mod-acc", "mod-acc-label").createAccordeonUnfold("mod-sub-acc", "mod-sub-acc-label");
    $renameSubCatErr = "Vul een naam in";
  }

  if($formErr == false) {
    if(changeCategoryName($dbh, $renameSubCat, $selectedSubCat)['PDORetCode'] == 1) {
      $saveReturn = "<h4>Wijziging succesvol opgeslagen</h4>";
    } else {
      $saveReturn = '<h4 style="color: red;">Er ging iets fout. Probeer het later opnieuw.</h4>';
    }
  }
}

if(isset($_POST['renameRubriekBtn'])) {
  $selectedCat      = !empty($_POST['renameRubriekCatDD']) ? $_POST['renameRubriekCatDD'] : '';
  $selectedRubriek  = !empty($_POST['renameRubriekDD']) ? $_POST['renameRubriekDD'] : '';
  $renameRubriek    = htmlspecialchars(!empty($_POST['renameRubriek']) ? $_POST['renameRubriek'] : '') ;

  if(empty($_POST['renameRubriek']) || $renameRubriek == "") {
    $formErr          = true;
    $unhideAccordeon  = createAccordeonUnfold("mod-acc", "mod-acc-label").createAccordeonUnfold("mod-rubriek-acc", "mod-rubriek-acc-label");
    $renameRubriekErr = "Kies een categorie";

  } if(empty($_POST['renameRubriekDD']) || $selectedRubriek == "") {
    $formErr            = true;
    $unhideAccordeon    = createAccordeonUnfold("mod-acc", "mod-acc-label").createAccordeonUnfold("mod-rubriek-acc", "mod-rubriek-acc-label");
    $renameRubriekDDErr = "Vul een naam in";

  } if(empty($_POST['renameRubriekDD']) || $selectedCat == "") {
    $formErr                    = true;
    $unhideAccordeon            = createAccordeonUnfold("mod-acc", "mod-acc-label").createAccordeonUnfold("mod-rubriek-acc", "mod-rubriek-acc-label");
    $renameRubriekRubriekDDErr  = "Kies een categorie";
  }

  if($formErr == false) {
    if(changeCategoryName($dbh, $renameRubriek, $selectedRubriek)['PDORetCode'] == 1) {
      $saveReturn = "<h4>Wijziging succesvol opgeslagen</h4>";
    } else {
      $saveReturn = '<h4 style="color: red;">Er ging iets fout. Probeer het later opnieuw.</h4>';
    }
  }
}

if(isset($_POST['sort'])) {
  $sortMethod = !empty($_POST['sort-method']) ? $_POST['sort-method'] : '';

  if($sortMethod != "alfa-az" && $sortMethod != "alfa-za") {
    $formErr          = true;
    $unhideAccordeon  = createAccordeonUnfold("sort-acc", "sort-acc-label");
    $sortErr          = "Kies een optie";
  } else {
    changeStoredSetting("CatSortMethod", $sortMethod);
    $saveReturn = "<h4>Wijziging succesvol opgeslagen</h4>";
  }
}


?>

<!DOCTYPE html>
<html>

<head>
  <?php require_once($_SERVER["DOCUMENT_ROOT"].'/include/html-header.php'); ?>
  <title>Eenmaal Andermaal</title>
</head>

<body>
  <?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/header.php"); ?>
  <div class="row">
    <div class="column small-12">
      <h1>Beheer</h1>
      <?php echo $saveReturn; ?>
    </div>
    <div class="column beheercontent">
      <div class="column small-12">
        <ul class="accordion" data-accordion data-multi-expand="true" data-allow-all-closed="true" >
          <li class="accordion-item" id="add-acc" data-accordion-item>
            <!-- Accordion tab title -->
            <a href="#" class="accordion-title">Toevoegen</a>

            <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
            <div class="accordion-content" data-tab-content>
              <div class="small-12 border">
                <form method="POST" action="">
                  <fieldset>
                    <ul class="accordion" data-accordion data-multi-expand="true" data-allow-all-closed="true" >
                      <li class="accordion-item" id="add-main-acc" data-accordion-item>
                        <!-- Accordion tab title -->
                        <a href="#" class="accordion-title">Hoofd categorie</a>

                        <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
                        <div class="accordion-content" data-tab-content>
                          <div class="small-12">
                            <fieldset>
                              <legend>Hoofd categorie</legend>
                              Naam Categorie:
                              <p class="form-error"><?php echo $addMainCatErr; ?></p>
                              <input type="text" value="<?php echo $mainCatName;?>" name="addMainCatName" placeholder="Naam...">
                              <input type="submit" name="addMainCatBtn" value="Toevoegen" class="button round">
                            </fieldset>
                          </div>
                        </div>
                      </li>
                      <li class="accordion-item" id="add-sub-acc" data-accordion-item>
                        <!-- Accordion tab title -->
                        <a href="#" class="accordion-title">Sub categorie</a>

                        <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
                        <div class="accordion-content" data-tab-content>
                          <div class="small-12 border">
                            <fieldset>
                              <legend>Sub categorie</legend>
                              Kies een categorie:
                              <label>
                                <span id="subCatDDField">
                                  <select name="addSubCatMainCatDD" id="addSubCatDD">
                                    <?php echo $mainDD; ?>
                                  </select>
                                  <p class="form-error"><?php echo $addSubCatDDErr; ?></p>
                                </span>
                              </label>
                              <span id="rubriekFieldAddSub">
                                Naam:
                                <input id="addSubCatInput" value="<?php echo $subCat;?>" name="addSubCatInput" type="text" placeholder="Naam...">
                                <p class="form-error"><?php echo $addSubCatErr; ?></p>
                              </span>
                              <input type="submit" name="addSubCatBtn" value="Toevoegen" class="button round">
                            </fieldset>
                          </div>
                        </div>
                      </li>

                      <li class="accordion-item" id="add-rubriek-acc" data-accordion-item>
                        <!-- Accordion tab title -->
                        <a href="#" class="accordion-title">Rubriek</a>

                        <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
                        <div class="accordion-content" data-tab-content>
                          <div class="small-12 border">
                            <fieldset>
                              <legend>Rubriek</legend>
                              <span id="addRubriekMainCatDDField">
                                Kies een categorie:
                                <label>
                                  <select name="addRubriekMainCatDD" id="addRubriekMainCatDD">
                                    <?php echo $mainDD; ?>
                                  </select>
                                  <p class="form-error"><?php echo $addRubriekMainCatDDErr; ?></p>
                                </label>
                              </span>
                              <span id="addRubriekSubCatDDField">
                                Kies een sub categorie
                                <label>
                                  <span id="addRubriekSubCatDDWrap">
                                    <select name="addRubriekSubCatDD" id="addRubriekSubCatDD">
                                      <option selected disabled value=""></option>
                                    </select>
                                    <p class="form-error"><?php echo $addRubriekSubCatDDErr; ?></p>
                                  </span>
                                </label>
                              </span>
                              Rubriek naam:
                              <input type="text" value="<?php echo $rubriek;?>" name="rubriekName" placeholder="Naam...">
                              <p class="form-error"><?php echo $addRubriekErr; ?></p>
                              <input type="submit" name="addRubriekBtn" value="Toevoegen" class="button round">
                            </fieldset>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </fieldset>
                </form>
              </div>
            </div>
          </li>
          <!-- ... -->

          <li id="mod-acc" class="accordion-item" data-accordion-item>
            <!-- Accordion tab title -->
            <a href="#" class="accordion-title">Hernoemen</a>

            <!--HERNOEMEN-->

            <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
            <div class="accordion-content" data-tab-content>
              <div class="small-12 border">
                <form action="" method="POST">
                  <ul class="accordion" data-accordion data-multi-expand="true" data-allow-all-closed="true" >
                    <li class="accordion-item" id="mod-main-acc" data-accordion-item>
                      <!-- Accordion tab title -->
                      <a href="#" class="accordion-title">Hoofd categorie</a>

                      <!-- ++HOOFD CATEGORIE -->

                      <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
                      <div class="accordion-content" data-tab-content>
                        <div class="small-12 border">
                          <fieldset>
                            <legend>Hoofd categorie</legend>
                            Kies een hoofd categorie:
                            <label>
                              <span id="subCatDDField">
                                <select name="renameMainCatDD" id="renameMainCatDD">
                                  <?php echo $mainDD; ?>
                                </select>
                                <p class="form-error"><?php echo $renameMainCatDDErr; ?></p>
                              </span>
                            </label>
                            <span id="rubriekFieldAddSub">
                              Naam:
                              <input id="addSubCatInput" value="<?php echo $renameMainCat;?>" name="renameMainCatInput" type="text" placeholder="Naam...">
                              <p class="form-error"><?php echo $renameMainCatErr; ?></p>
                            </span>
                            <input type="submit" name="renameMainCatBtn" value="Wijzigen" class="button round">
                          </fieldset>
                        </div>
                      </div>
                    </li>

                    <!--++SUB CATEGORIE++-->

                    <li class="accordion-item" id="mod-sub-acc" data-accordion-item>
                      <!-- Accordion tab title -->
                      <a href="#" class="accordion-title">Sub categorie</a>

                      <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
                      <div class="accordion-content" data-tab-content>
                        <div class="small-12 border">
                          <fieldset>
                            <legend>Sub categorie</legend>
                            Kies een categorie:
                            <label>
                              <span id="subCatDDField">
                                <select name="renameSubCatMainCatDD" id="renameSubCatDD">
                                  <?php echo $mainDD; ?>
                                </select>
                                <p class="form-error"><?php echo $renameSubCatMainCatDDErr; ?></p>
                              </span>
                            </label>
                            Kies een sub categorie
                            <span id="addRubriekSubCatDDField">
                              Kies een sub categorie
                              <label>
                                <span id="renameSubCatDDWrap">
                                  <select name="renameSubCatDD">
                                    <option selected disabled value=""></option>
                                  </select>
                                  <p class="form-error"><?php echo $renameSubCatDDErr; ?></p>
                                </span>
                              </label>
                            </span>
                            <span id="rubriekFieldAddSub">
                              Naam:
                              <input id="renameSubCatInput" value="<?php echo $renameSubCat;?>" name="renameSubCatInput" type="text" placeholder="Naam...">
                              <p class="form-error"><?php echo $renameSubCatErr; ?></p>
                            </span>
                            <input type="submit" name="renameSubCatBtn" value="Wijzigen" class="button round">
                          </fieldset>
                        </div>
                      </div>
                    </li>

                    <!--++RUBRIEK++-->

                    <li class="accordion-item" id="mod-rubriek-acc" data-accordion-item>
                      <!-- Accordion tab title -->
                      <a href="#" class="accordion-title">Rubriek</a>

                      <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
                      <div class="accordion-content" data-tab-content>
                        <div class="small-12 border">
                          <fieldset>
                            <legend>Sub categorie</legend>
                            Kies een categorie:
                            <label>
                              <span id="subCatDDField">
                                <select name="renameRubriekCatDD" id="renameRubriekMainCatDD" class="item-spacing dropdown">
                                  <?php echo $catDropDownData; ?>
                                </select>
                                <p class="form-error"><?php echo $renameRubriekDDErr; ?></p>
                              </span>
                            </label>
                            Kies een rubriek
                            <span id="renameRubriekField">
                              <label>
                                <span id="renameRubriekDDWrap">
                                  <select name="renameRubriekDD">
                                    <option selected disabled value=""></option>
                                  </select>
                                  <p class="form-error"><?php echo $renameRubriekRubriekDDErr; ?></p>
                                </span>
                              </label>
                            </span>
                            <span id="rubriekFieldAddSub">
                              Naam:
                              <input id="renameSubCatInput" value="<?php echo $renameRubriek;?>" name="renameRubriek" type="text" placeholder="Naam...">
                              <p class="form-error"><?php echo $renameRubriekErr; ?></p>
                            </span>
                            <input type="submit" name="renameRubriekBtn" value="Wijzigen" class="button round">
                          </fieldset>
                        </div>
                      </div>
                    </li>
                  </ul>
                </form>
              </div>
            </div>
          </li>
          <li class="accordion-item" id="sort-acc" data-accordion-item>
            <!-- Accordion tab title -->
            <a href="#" class="accordion-title">Sorteren op</a>

            <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
            <div class="accordion-content" data-tab-content>
              <div class="small-12 border">
                <form action="" method="POST">
                  <fieldset>
                    <legend>Sorteren op</legend>
                    <input type="radio" name="sort-method" value="alfa-az"> Alfabetisch A-Z <br>
                    <input type="radio" name="sort-method" value="alfa-za"> Alfabetisch Z-A <br>
                    <input type="submit" name="sort" value="Sorteren" class="button round">
                    <p class="form-error"><?php echo $sortErr;?></p>
                  </fieldset>
                </form>
              </div>
            </div>
          </li>
          <li class="accordion-item" data-accordion-item>
            <!-- Accordion tab title -->
            <a href="#" class="accordion-title">Uitfaseren</a>

            <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
            <div class="accordion-content" data-tab-content>
              <div class="small-12 border">
                <form action="" method="POST">
                  <ul class="accordion" data-accordion data-multi-expand="true" data-allow-all-closed="true" >
                    <li class="accordion-item" id="ps-main-acc" data-accordion-item>
                      <!-- Accordion tab title -->
                      <a href="#" class="accordion-title">Hoofd categorie</a>

                      <!-- ++HOOFD CATEGORIE -->

                      <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
                      <div class="accordion-content" data-tab-content>
                        <div class="small-12 border">
                          <fieldset>
                            <legend>Hoofd categorie</legend>
                            Kies een hoofd categorie:
                            <label>
                              <span id="subCatDDField">
                                <select name="phaseMainCatDD" id="phaseMainCatDD">
                                  <?php echo $mainDD; ?>
                                </select>
                                <p class="form-error"><?php echo $phaseMainCatDDErr; ?></p>
                              </span>
                            </label>

                            <input type="submit" name="phaseMainCatBtn" value="Wijzigen" class="button round">
                          </fieldset>
                        </div>
                      </div>
                    </li>

                    <!--++SUB CATEGORIE++-->

                    <li class="accordion-item" id="ps-sub-acc" data-accordion-item>
                      <!-- Accordion tab title -->
                      <a href="#" class="accordion-title">Sub categorie</a>

                      <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
                      <div class="accordion-content" data-tab-content>
                        <div class="small-12 border">
                          <fieldset>
                            <legend>Sub categorie</legend>
                            Kies een categorie:
                            <label>
                              <span id="subCatDDField">
                                <select name="phaseSubCatMainCatDD" id="phaseSubCatDD">
                                  <?php echo $mainDD; ?>
                                </select>
                                <p class="form-error"><?php echo $phaseSubCatMainCatDDErr; ?></p>
                              </span>
                            </label>
                            Kies een sub categorie
                            <span id="phaseRubriekSubCatDDField">
                              Kies een sub categorie
                              <label>
                                <span id="phaseSubCatDDWrap">
                                  <select name="phaseSubCatDD">
                                    <option selected disabled value=""></option>
                                  </select>
                                  <p class="form-error"><?php echo $phaseSubCatDDErr; ?></p>
                                </span>
                              </label>
                            </span>
                            <input type="submit" name="phaseSubCatBtn" value="Wijzigen" class="button round">
                          </fieldset>
                        </div>
                      </div>
                    </li>

                    <!--++RUBRIEK++-->

                    <li class="accordion-item" id="ps-rubriek-acc" data-accordion-item>
                      <!-- Accordion tab title -->
                      <a href="#" class="accordion-title">Rubriek</a>

                      <!-- Accordion tab content: it would start in the open state due to using the `is-active` state class. -->
                      <div class="accordion-content" data-tab-content>
                        <div class="small-12 border">
                          <fieldset>
                            <legend>Sub categorie</legend>
                            Kies een categorie:
                            <label>
                              <span id="subCatDDField">
                                <select name="phaseRubriekCatDD" id="phaseRubriekMainCatDD" class="item-spacing dropdown">
                                  <?php echo $catDropDownData; ?>
                                </select>
                                <p class="form-error"><?php echo $phaseRubriekDDErr; ?></p>
                              </span>
                            </label>
                            Kies een rubriek
                            <span id="phaseRubriekField">
                              <label>
                                <span id="phaseRubriekDDWrap">
                                  <select name="phaseRubriekDD">
                                    <option selected disabled value=""></option>
                                  </select>
                                  <p class="form-error"><?php echo $phaseRubriekRubriekDDErr; ?></p>
                                </span>
                              </label>
                            </span>
                            <input type="submit" name="phaseRubriekBtn" value="Wijzigen" class="button round">
                          </fieldset>
                        </div>
                      </div>
                    </li>
                  </ul>
                </form>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="row">



  </div>

  <?php
  //If an error occured with the entered data, do the following

  if($formErr) {
    echo $unhideAccordeon; //unfold the appropriate accordeon
    echo '<script>$(".form-error").css("display", "block");</script>'; //make the error text appear
  }
  ?>
  <?php require_once($_SERVER["DOCUMENT_ROOT"]."/include/footer.php"); ?>

</body>

</html>

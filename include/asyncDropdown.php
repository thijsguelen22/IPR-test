<?php
/*
Author:   Thijs-Jan Guelen
Project:  EenmaalAndermaal
Date:     26-04-2019 12:00
Description:

This file is loaded asynchronously by the categories dropdown.
*/
$ID = isset($_GET['ID']) ? $_GET['ID'] : '';
$Location = isset($_GET['Location']) ? $_GET['Location'] : '';

require_once('./globalFunctions.inc.php');
require_once('./db_connector.php');

if($Location == 'search') {
  $returnStr = '<select id="replaceableCatDD" class="item-spacing dropdown"><option value="" selected>Alle rubrieken</option>';
} else if($Location == 'BeheerAddSub') {
  $returnStr = '<select name="addRubriekSubCatDD" id="addSubCatDD"><option value="" selected disabled>Selecteer sub categorie</option>';
} else if($Location == 'BeheerAddRub') {
  $returnStr = '<select id="addSubCatDD"><option value="" selected disabled>Selecteer sub categorie</option>';
} else if($Location == "BeheerDelRub") {
  $returnStr = '<select id="deleteRubriekDD"><option value="" selected disabled>Selecteer..</option><option class="other" value="_WHOLE">Hele sub categorie</option><option class="other" value="_ALL">Alle rubrieken</option>';
} else if($Location == 'BeheerMod') {
   $returnStr = '<select id="modifySubCatDD"><option value="" selected disabled>Selecteer..</option>';
} else if($Location == 'BeheerModRub') {
    $returnStr = '<select id="modifyRubruekDD"><option value="" selected disabled>Selecteer..</option>';
} else if($Location == 'BeheerRenameSub') {
    $returnStr = '<select name="renameSubCatDD"><option value="" selected disabled>Selecteer..</option>';
} else if($Location == 'BeheerRenameRubriekSub') {
    $returnStr = '<select name="renameRubriekSubCatDD" id="renameRubriekSubCatDD"><option value="" selected disabled>Selecteer..</option>';
} else if($Location == 'BeheerRenameRubriekRub') {
    $returnStr = '<select name="renameRubriekDD"><option value="" selected disabled>Selecteer..</option>';
} else {
  $returnStr = '';
}
if($ID != NULL || $ID != '') {
  $subData = getSubCategories($dbh, $ID);

  foreach($subData['data'] as $subCat) {
    $returnStr = $returnStr.'<option value="'.$subCat['rubrieknummer'].'" name="rubriek">'.$subCat['rubrieknaam'].'</option>';
  }
  $returnStr = $returnStr.'</select></span>';
  echo $returnStr;
} else {
  echo '<select id="replaceableCatDD" class="item-spacing dropdown"><option value="" disabled selected>Rubriek</option></select>';
}

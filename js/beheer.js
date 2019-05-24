
//ADD

$( "#subCatDDField" ).on('change','select', function(){
  $( "#rubriekFieldAddSub" ).fadeIn(1000);
});

$( "#addSubCatDD" ).on("change", function(){
  var allSubs = '<select id="replaceableCatDD" class="item-spacing dropdown"><option value="" selected>Kies een subcategorie</option></select>';
  var loading = '<select id="addSubCatDD"><option selected disabled value="">Laden...</option></select>';

  var selectedVal = $( '#addMainCatDD' ).val();
  var cat = $( '#addMainCatDD' ).find(":selected").attr('class');
  if(cat == 'mainCat') {
    $("#addSubCatDDWrap").empty().append(loading);
    $.get("./include/asyncDropdown.php?Location=BeheerAddSub&ID="+selectedVal, function( newDD ) {
      //$( "#addSubCatDD" ).replaceWith(newDD);
      $("#addSubCatDDWrap").empty().append(newDD);
    }, 'html');
  } else {
    //$( "#addSubCatDD" ).replaceWith(allSubs);
    $("#addSubCatDDWrap").empty().append(allSubs);
  }
});

$( "#addRubriekMainCatDD" ).on("change", function(){
  var allSubs = '<select id="replaceableCatDD" class="item-spacing dropdown"><option value="" selected>Kies een subcategorie</option></select>';
  var loading = '<select id="addSubCatDD"><option selected disabled value="">Laden...</option></select>';

  var selectedVal = $( '#addRubriekMainCatDD' ).val();

  var cat = $( '#addMainCatDD' ).find(":selected").attr('class');
  //if(cat == 'mainCat') {
    $("#addRubriekSubCatDDWrap").empty().append(loading);
    $.get("./include/asyncDropdown.php?Location=BeheerAddSub&ID="+selectedVal, function( newDD ) {
      //$( "#addSubCatDD" ).replaceWith(newDD);
      $("#addRubriekSubCatDDWrap").empty().append(newDD);
    }, 'html');

});

$( "#renameSubCatDD" ).on("change", function(){
  var allSubs = '<select id="replaceableCatDD" class="item-spacing dropdown"><option value="" selected>Kies een subcategorie</option></select>';
  var loading = '<select id="renameSubCatDD"><option selected disabled value="">Laden...</option></select>';

  var selectedVal = $( '#renameSubCatDD' ).val();
  var cat = $( '#renameSubCatDD' ).find(":selected").attr('class');
  //if(cat == 'mainCat') {
    $("#renameSubCatDDWrap").empty().append(loading);
    $.get("./include/asyncDropdown.php?Location=BeheerRenameSub&ID="+selectedVal, function( newDD ) {
      //$( "#addSubCatDD" ).replaceWith(newDD);
      $("#renameSubCatDDWrap").empty().append(newDD);
    }, 'html');

});

$( "#renameRubriekMainCatDD" ).on("change", function(){
  var allSubs = '<select id="replaceableCatDD" class="item-spacing dropdown"><option value="" selected>Kies een subcategorie</option></select>';
  var loading = '<select id="renameSubCatDD"><option selected disabled value="">Laden...</option></select>';

  var selectedVal = $( '#renameRubriekMainCatDD' ).val();
  var cat = $( '#renameRubriekMainCatDD' ).find(":selected").attr('class');
  //if(cat == 'mainCat') {
    $("#renameRubriekDDWrap").empty().append(loading);
    $.get("./include/asyncDropdown.php?Location=BeheerRenameRubriekRub&ID="+selectedVal, function( newDD ) {
      //$( "#addSubCatDD" ).replaceWith(newDD);
      $("#renameRubriekDDWrap").empty().append(newDD);
    }, 'html');

});
//------------
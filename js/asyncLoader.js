$( window ).ready(function() {
  //console.log(window.location.protocol + "//" + window.location.host + "/");
  $.get(window.location.protocol + "//" + window.location.host + "/include/async_menu.php", function( menu ) {
    $( "#asyncMenu" ).replaceWith(menu);
  }, 'html')
  .done(function() {
    //$("#asyncMenu").css("display", "block");
  });
});

$(document).ready(function() {
  $("#valoreVoto").text($('input[type="range"]').first().val());
  $('input[type="range"]').first().change(function() {
    $("#valoreVoto").text($('input[type="range"]').first().val());
  });
});
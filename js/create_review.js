const descriptionMaxLength = 500;

$(document).ready(function() {
  $("#valoreVoto").text($('input[type="range"]').first().val());
  $('input[type="range"]').first().change(function() {
    $("#valoreVoto").text($('input[type="range"]').first().val());
  });
  $("p#remainingChars").text("("+descriptionMaxLength+" caratteri rimanenti.)");
});

function checkRemainingCharacters() {
  let remainingChar = descriptionMaxLength - $('[name="descrizione"]').val().length;
  $("p#remainingChars").text("("+remainingChar+" caratteri rimanenti.)");
}
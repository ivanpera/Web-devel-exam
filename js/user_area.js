$(document).ready(function() {
  $(".collapsableBtn").each(function() {
    $(this).click(function() {
      $(this).next().slideToggle();
      $(this).children("img.collapsableIcon")
             .first().toggleClass("open")
                     .css("transform", $(this).children("img.collapsableIcon").hasClass("open") ? "rotate(90deg)" : "rotate(0deg)");
    });
  })
});
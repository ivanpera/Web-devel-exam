function openSidebar() {
    $("#sidebar").css("display", "block"); 
    $("#sidebar").animate({left: '0'});

}

function closeSidebar() {
    $("#sidebar").animate({left: '-80%'}, function() {
        // The change in display property is inside a callback function so it is done after the completion of the translation.
        $("#sidebar").css("display", "none");
    });
}
const sidebarAnimDuration = 180;

function openSidebar() {
    $("#sidebar").css("display", "block");
    $("#sidebar").css("left", -$("#sidebar").width());
    $("#sidebar").animate({left: '0'}, sidebarAnimDuration);
    $("#overlay").css("display", "block");
    $("#overlay").animate({opacity: '0.5'}, sidebarAnimDuration);
}


function closeSidebar() {
    $("#overlay").animate({opacity: '0'}, sidebarAnimDuration);
    $("#sidebar").animate({left: -$("#sidebar").width()}, sidebarAnimDuration, function() {
        // The change in display property is inside a callback function so it is done after the completion of the translation.
        $("#sidebar").css("display", "none");
        $("#overlay").css("display", "none");
    });
}

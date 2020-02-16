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

function getNumOfNotification() {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            $("#notification-container > div").html(xhttp.responseText == "0" ? "" : xhttp.responseText);
            const isHidden = document.getElementById("notif_div").className.includes("hidden");
            if((xhttp.responseText === "0" && !isHidden) || (xhttp.responseText !== "0" && isHidden)) {
                $("#notification-container > div").toggleClass("hidden");
            }
        }
    };
    xhttp.open("GET", "php/ajax_response/notification_num_getter.php");
    xhttp.send();
}

let pullNotificationTimer;

$(document).ready(function() {
    let seconds = 60;
    pullNotificationTimer = setInterval(getNumOfNotification, seconds * 1000);
});

$(document).on("unload", function() {
    clearInterval(pullNotificationTimer);
});

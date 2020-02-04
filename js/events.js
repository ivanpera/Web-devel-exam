function showTopEvents() {
    $("#top_events").css("display", "block");
    $("#search_form").css("display", "none");
    $("#top_btn").addClass("active");
    $("#form_btn").removeClass("active");
}

function showSearchForm() {
    $("#top_events").css("display", "none");
    $("#search_form").css("display", "block");
    $("#form_btn").addClass("active");
    $("#top_btn").removeClass("active");
}

$(document).ready(function() {
    if ($("#top_events").css("display") == "block") {
        $("#top_btn").addClass("active");
    } else {
        $("#form_btn").addClass("active");
    }
});

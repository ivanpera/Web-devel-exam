$(document).ready(function() {
    $("#top_btn").prop("disabled", true);
    $("#top_btn").click(function () {
        $("#top_events").css("display", "block");
        $("#search_form").css("display", "none");

        $("#top_btn").prop("disabled", true);
        $("#form_btn").prop("disabled", false);

    });
    $("#form_btn").click(function () {
        $("#top_events").css("display", "none");
        $("#search_form").css("display", "block");
        
        $("#top_btn").prop("disabled", false);
        $("#form_btn").prop("disabled", true);
        
    });
});
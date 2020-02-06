function removeLastTicket() {
    $(".ticket_creator").last().remove();
}

function addNewTicket() {
    $(".ticket_creator").first().parent().append(' \
    <div class="ticket_creator"> \
    </div>');

    $.each($("div.ticket_creator:not(:first-of-type)"), function (i, item) {
        item.innerHTML = $("div.ticket_creator:first-of-type").html();
    });
}

function removeLastMod() {
    $(".moderator_adder").last().remove();
}

function addNewMod() {
    $(".moderator_adder").first().parent().append(' \
        <div class="moderator_adder"> \
            <input type="text" name="mod_mail[]" placeholder="E-mail moderatore"/> \
            <button class="rm_mod_btn" type="button" onclick=removeLastMod()> - </button>  <!-- Hidden for the first moderator --> \
            <button class="add_mod_btn" type="button" onclick=addNewMod()> + </button> <!-- Just a placeholder: adds another moderator to edit (todo with js) --> \
        </div> \
    ');
}

$(document).ready(function(){
    $("input[type=\"submit\"]").click(function() {
        checked = $("input[type=\"checkbox\"][name=\"categories[]\"]:checked").length;

      if(!checked) {
        alert("You must check at least one category.");
        return false;
      } else {
          $("#createForm").submit();
      }
    })
});
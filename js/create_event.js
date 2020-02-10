function removeLastTicket() {
    $(".ticket_creator").last().remove();
}

function addNewTicket() {
    $(".ticket_creator").first().parent().append(' \
    <div class="ticket_creator"> \
    </div>');

    $(".ticket_creator").last().html($(".ticket_creator").first().html());
    if ($(".ticket_creator:last-child > button.rm_ticket_btn").length == 0) {
        $(".ticket_creator:last-child > button.add_ticket_btn").before('<button class="rm_ticket_btn" type="button" onclick=removeLastTicket()> - </button>');
        $(".ticket_creator").last().html($(".ticket_creator").last().html().replace("readonly", "").replace("disabled", ""));
    }
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
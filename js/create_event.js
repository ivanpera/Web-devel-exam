/*$(document).ready(function() {
    $(".rm_ticket_btn").click(function() {
        $(".ticket_creator").last().remove();
    });

    $(".add_ticket_btn").click(function() {
        $(".ticket_creator").first().parent().append(' \
        <div class="ticket_creator"> \
            <select required> \
                <option value="" disabled selected hidden>Seleziona una categoria...</option> \
                <!-- Fill trough php--> \
                <?php foreach ($templateParams["tipoPosti"] as $tipoPosto): ?> \
                    <option value="<?php echo $tipoPosto["codTipologia"]; ?>"><?php echo $tipoPosto["nomeTipologia"];?></option> \
                <?php endforeach; ?> \
            </select> \
            <label>Costo per biglietto (in centesimi di Euro): <input type="number" min="0" step="1"/></label> \
            <label for="num_tickets"> Numero biglietti: <input type="number" min="1" name="num_tickets" id="num_tickets"/></label> \
            <button id="rm_ticket_btn" type="button"> - </button> <!-- hidden for the first ticket type --> \
            <button id="add_ticket_btn" type="button"> + </button> <!-- Just a placeholder: adds another ticket to edit (todo with js) --> \
        </div>');
    });
});
*/
function removeLastTicket() {
    $(".ticket_creator").last().remove();
}

function addNewTicket() {
    $(".ticket_creator").first().parent().append(' \
    <div class="ticket_creator"> \
        <select required> \
            <option value="" disabled selected hidden>Seleziona una categoria...</option> \
            <!-- Fill trough php--> \
            <?php foreach ($templateParams["tipoPosti"] as $tipoPosto): ?> \
                <option value="<?php echo $tipoPosto["codTipologia"]; ?>"><?php echo $tipoPosto["nomeTipologia"];?></option> \
            <?php endforeach; ?> \
        </select> \
        <label>Costo per biglietto (in centesimi di Euro): <input type="number" min="0" step="1"/></label> \
        <label for="num_tickets"> Numero biglietti: <input type="number" min="1" name="num_tickets" id="num_tickets"/></label> \
        <button id="rm_ticket_btn" type="button" onclick=removeLastTicket()> - </button> <!-- hidden for the first ticket type --> \
        <button id="add_ticket_btn" type="button" onclick=addNewTicket()> + </button> <!-- Just a placeholder: adds another ticket to edit (todo with js) --> \
    </div>');
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
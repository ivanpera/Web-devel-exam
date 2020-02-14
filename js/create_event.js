let currentTab = 0;

$(document).ready(function() {
    showTab(currentTab);
});

function showTab(n) {
    const tabs = document.getElementsByClassName("tab");
    tabs[n].style.display = "block";
    
    document.getElementById("prevBtn").style.display = n <= 0 ? "none" : "inline";
    document.getElementById("nextBtn").innerHTML = n >= (tabs.length - 1) ? "Fine" : "Successivo";
}

function validateForm() {
    let valid = true;
    const tabs = document.getElementsByClassName("tab");
    let currInputs = tabs[currentTab].querySelectorAll(".required");
    for (let i = 0; i < currInputs.length; i++) {
        if(currInputs[i].value === "") {
            if(!currInputs[i].className.includes(" invalid")) {
                currInputs[i].className += " invalid";
            }
            valid = false;
        } else {
            currInputs[i].className = currInputs[i].className.replace(" invalid", "");
        }
    }
    return valid;
}

function changeTab(step) {
    const tabs = document.getElementsByClassName("tab");
    if (step == 1 && !validateForm()) return false;
    tabs[currentTab].style.display = "none";
    currentTab += step;
    if(currentTab >= tabs.length) {
        $("#createForm").submit();
        return false;
    }
    showTab(currentTab);
}

function removeLastTicket() {
    $(".ticket_creator").last().remove();
}

function addNewTicket() {
    $(".ticket_creator").first().parent().append(' \
    <div class="ticket_creator"> \
    </div>');

    $("div.ticket_creator:last-of-type").html($("div.ticket_creator:last-of-type").prev().html());
}

function removeLastMod() {
    $(".moderator_adder").last().remove();
}

function addNewMod() {
    $(".moderator_adder").first().parent().append(' \
        <div class="moderator_adder"> \
        <label for="mod_mail">Mail del moderatore: </label><input id="mod_mail" type="text" name="mod_mail[]" placeholder="E-mail moderatore"/></label> \
        <label for="rm_ticket_btn" class="visuallyhidden">Rimuovi ultimo moderatore</label><button title="Rimuovi moderatore" class="rm_mod_btn" type="button" onclick=removeLastMod()> - </button> \
        <label for="rm_ticket_btn" class="visuallyhidden">Aggiungi un moderatore</label><button title="Aggiungi moderatore" class="add_mod_btn" type="button" onclick=addNewMod()> + </button> \
        </div> \
    ');
}

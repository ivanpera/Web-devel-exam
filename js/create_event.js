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
        if(currInputs[i].value == "") {
            currInputs[i].className += " invalid";
            valid = false;
        } else {
            currInputs[i].className -= " invalid";
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
        document.getElementById("createForm").submit();
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

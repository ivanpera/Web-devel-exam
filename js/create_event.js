let currentTab = 0;
let currentMods = 0;
let currentTickets = 1;

$(document).ready(function() {
    showTab(currentTab);
    currentMods = document.getElementsByClassName("moderator_adder").length;
    currentTickets = document.getElementsByClassName("ticket_creator").length;
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

function removeTicket(i) {
    const ticketCreators = document.getElementsByClassName("ticket_creator");

    document.querySelectorAll('#ticket_creator_' + i.toString())[0].remove();
    currentTickets--;

    for(let i = 0; i < currentTickets; i++) {
        let numChildren = ticketCreators[i].children.length; //At least 7 children are present (if more, then the delete button is present)
        ticketCreators[i].setAttribute("id", "ticket_creator_" + i.toString());
        ticketCreators[i].children[1].setAttribute("for", "ticket_cost_" + i.toString());
        ticketCreators[i].children[2].setAttribute("id", "ticket_cost_" + i.toString());
        ticketCreators[i].children[3].setAttribute("for", "num_tickets_" + i.toString());
        ticketCreators[i].children[4].setAttribute("id", "num_tickets_" + i.toString());

        if(numChildren > 7) {
            ticketCreators[i].children[5].setAttribute("for", "rm_ticket_" + i.toString());
            ticketCreators[i].children[6].setAttribute("id", "rm_ticket_" + i.toString());
            ticketCreators[i].children[6].setAttribute("onclick", "removeTicket(" + i.toString() + ")");
            ticketCreators[i].children[7].setAttribute("for", "add_ticket_" + i.toString());
            ticketCreators[i].children[8].setAttribute("id", "add_ticket_" + i.toString());
        } else {
            ticketCreators[i].children[5].setAttribute("for", "add_ticket_" + i.toString());
            ticketCreators[i].children[6].setAttribute("id", "add_ticket_" + i.toString());
        }
        ticketCreators[i].children[numChildren-1].style.display = i == currentTickets -1 ? "inline-block" : "none";
        ticketCreators[i].children[numChildren-2].style.display = i == currentTickets -1 ? "inline-block" : "none";
    }

    if(currentTickets <= 1 && ticketCreators[0].children.length > 7) {
        ticketCreators[0].children[5].style.display = "none";
        ticketCreators[0].children[6].style.display = "none";
    }
}

function addNewTicket() {
    if(getSumOfTickets() < Number($("#maxCapacity").attr("max-capacity"))) {
        const ticketCreators = document.getElementsByClassName("ticket_creator");
        addButtons = document.querySelectorAll('[id^="add_ticket"]');
        seatOptions = document.querySelectorAll('.ticket_creator:first-of-type option');
        for(let i = 0; i < currentTickets; i++) {
            addButtons[i].style.display = "none";
            if(ticketCreators[i].children.length > 7) {
                ticketCreators[i].children[5].style.display = "inline-block";
                ticketCreators[i].children[6].style.display = "inline-block";
            }
        }
        let itemString = '<div class="ticket_creator" id="ticket_creator_' + currentTickets + '">   \
        <label>Tipo biglietto:      \
        <select name="ticket_type[]" required class="required">';
        for(let i = 0; i < seatOptions.length; i++) {
            itemString += '<option value="' + seatOptions[i].value + '">' + seatOptions[i].innerHTML + '</option>';
        }
        itemString += '</select></label>   \
        <label for="ticket_cost_' + currentTickets + '">Costo unitario del biglietto: </label><input id="ticket_cost_' + currentTickets + '" name="ticket_cost[]" type="number" min="0" step="1" required class="required"/> \
        <label for="num_tickets_' + currentTickets + '"> Numero biglietti: </label><input type="number" min="1" name="num_tickets[]" id="num_tickets_' + currentTickets + '" required class="required" oninput="checkTickets('+currentTickets+')" onkeyup="checkTickets('+currentTickets+')"/>  \
        <label for="rm_ticket_' + currentTickets + '" class="visuallyhidden">Rimuovi tipologia di biglietto</label><button title="Rimuovi biglietto" id="rm_ticket_' + currentTickets + '" class="rm_ticket_btn" type="button" onclick=removeTicket(' + currentTickets + ')> - </button>    \
        <label for="add_ticket_' + currentTickets + '" class="visuallyhidden">Aggiungi una tipologia di biglietto</label><button title="Aggiungi biglietto" class=add_ticket_btn id="add_ticket_' + currentTickets + '" type="button" onclick=addNewTicket()> + </button>   \
        </div>';
        currentTickets++;
        $("#section_biglietti").append(itemString);
    } else {
        alert("La capacità massima è stata raggiunta. Diminuire il numero di biglietti per aggiugere un'altra categoria");
    }
}

function removeMod(i) {
    const modAdders = document.getElementsByClassName("moderator_adder");
    if(modAdders.length == 1) {
        $("#no_mod_parag").css("display", "block");
        $("#no_mod_label").css("display", "block");
        $(".add_mod_btn").css("display", "block");
    }

    document.querySelectorAll('#mod_adder' + i.toString())[0].remove();
    currentMods--;
    for(let i = 0; i < currentMods; i++) {
        //display last "+" button
        modAdders[i].children[5].style.display = i == currentMods -1 ? "inline-block" : "none";
        //update modAdder and children's (buttons and labels) attributes
        modAdders[i].setAttribute("id", "mod_adder" + i.toString());
        modAdders[i].children[0].setAttribute("for", "mod_mail" + i.toString());
        modAdders[i].children[1].setAttribute("id", "mod_mail" + i.toString());
        modAdders[i].children[2].setAttribute("for", "rm_mod_" + i.toString());
        modAdders[i].children[3].setAttribute("id", "rm_mod_" + i.toString());
        modAdders[i].children[3].setAttribute("onclick", "removeMod(" + i.toString() + ")");
        modAdders[i].children[4].setAttribute("for", "add_mod_" + i.toString());
        modAdders[i].children[5].setAttribute("id", "add_mod_" + i.toString());
    }
}

function addNewMod() {
    addButtons = document.querySelectorAll('[id^="add_mod"]');
    if(document.getElementsByClassName("moderator_adder").length <= 0) {
        $("#no_mod_parag").css("display", "none");
        $("#no_mod_label").css("display", "none");
        $(".add_mod_btn").css("display", "none");
    }
    for(let i = 0; i < currentMods; i++) {
        addButtons[i].style.display = "none";
    }

    $("#section_moderatori").append(' \
    <div class="moderator_adder" id="mod_adder' + currentMods + '"> \
    <label for="mod_mail_' + currentMods + '">Mail del moderatore: </label><input id="mod_mail' + currentMods + '" type="text" name="mod_mail[]" placeholder="E-mail moderatore"/></label> \
    <label for="rm_mod_' + currentMods + '" class="visuallyhidden">Rimuovi ultimo moderatore</label><button title="Rimuovi moderatore" id="rm_mod_' + currentMods + '" class="rm_mod_btn" type="button" onclick=removeMod(' + currentMods + ')> - </button> \
    <label for="add_mod_' + currentMods + '" class="visuallyhidden">Aggiungi un moderatore</label><button title="Aggiungi moderatore" id="add_mod_' + currentMods + '" class="add_mod_btn" type="button" onclick=addNewMod()> + </button> \
    </div> \
    ');
    currentMods++;
}

function getSumOfTickets() {
    let sum = 0;
    $('[id^="num_tickets"]').each(function(index, element) {
        sum += Number($(this).val());
    });
    return sum;
}

function setMaxCapacityOf() {
    let codLuogo = $("select#luogo").val();
    console.log(codLuogo);
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if(xhttp.readyState == 4 && xhttp.status == 200) {
            $("#maxCapacity").html("Capacità massima del luogo: "+xhttp.responseText);
            $("#maxCapacity").attr("max-capacity", xhttp.responseText);
            console.log($("#maxCapacity").attr("max-capacity"));
        }
    };
    xhttp.open("POST", "php/ajax_response/place_capacity.php");
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("codLuogo="+codLuogo);
}

//Called when place changed
function recheckTickets() {
    let maxCapacity = Number($("#maxCapacity").attr("max-capacity"));
    let oldSum = getSumOfTickets();
    if(oldSum > maxCapacity) {
        $('[id^="num_tickets_"]').each(function(index, element) {
            let num = Number($(this).val());
            $(this).val(floor(num/oldSum*maxCapacity));
        });
        alert("La somma dei biglietti era maggiore della capacità massima. Il numero di ciascuna tipologia di biglietto è stata rivalutata in proporzione alla vecchia somma.");
    }
}

//Called when ticket num changed
function checkTickets(modifiedIndex) {
    let maxCapacity = Number($("#maxCapacity").attr("max-capacity"));
    let sum = getSumOfTickets();
    if (sum > maxCapacity) {
        let overVal = Number($("#num_tickets_"+modifiedIndex).val());
        $("#num_tickets_"+modifiedIndex).val(overVal - (sum - maxCapacity));
        alert("La somma dei biglietti è maggiore della capacità massima. Il numero dell'ultimo biglietto modificato verrà impostato al massimo numero possibile mantenendo invariati gli altri biglietti.");
    }
}


$(document).ready(function () {
    $("select#luogo").bind("input keyup", function() {setMaxCapacityOf(); recheckTickets();});
    $('[id^="num_tickets_"]').each(function(index, element){
        $(this).prop("oninput", "checkTickets("+index+")");
        $(this).prop("onkeyup", "checkTickets("+index+")");
    });
});
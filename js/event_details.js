$(document).ready(function() {
  let observeButtons=$(".observe_btn");
  if(observeButtons.length != 0) {
    let oB = observeButtons[0];
    let funcB = oB.attributes["onclick"].value.split(/[\ \,\(\)]/);
    let codEvento = funcB[1];
    let email = funcB[3].replace(/\'/g, "");
    updateButtonText(codEvento, email);
  }
});

function toggleObserveStatus(codEvento, email) {
  let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if(this.readyState == 4 && this.status == 200) {
        updateButtonText(codEvento, email);
      }
    };
    xhttp.open("GET", "php/ajax_response/toggle_observe_status.php?codEvento=" + codEvento + "&emailUtente=" + email, true);
    xhttp.send();
}

function updateButtonText(codEvento, email) {
  let oB=$(".observe_btn")[0];
  let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if(this.readyState == 4 && this.status == 200) {
        oB.innerHTML = (xhttp.responseText == "1" ? "Evento osservato!" : "Osserva evento");
      }
    };
    xhttp.open("GET", "php/ajax_response/observe_event_status.php?codEvento=" + codEvento + "&emailUtente=" + email, true);
    xhttp.send();
}
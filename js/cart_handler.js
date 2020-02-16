function addToCart(codEvento, codTipologia, costo) {
  let numOfTickets = Number($('button[onclick="addToCart('+codEvento+','+codTipologia+','+ costo+')"]').first().prev().val());
  if (numOfTickets <= Number($('button[onclick="addToCart('+codEvento+','+codTipologia+','+ costo+')"]').first().prev().attr("max"))) {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
      if(this.readyState == 4 && this.status == 200) {
        $('button[onclick="addToCart('+codEvento+','+codTipologia+','+ costo+')"]').first().html((xhttp.response == "OK" ? "Aggiunto al carrello!" : "Errore!"));
        $('button[onclick="addToCart('+codEvento+','+codTipologia+','+ costo+')"]').first().prop("disabled", true);
      }
    };
    xhttp.open("POST", "php/ajax_response/cart_handler.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("codEvento="+codEvento+"&codTipologia="+codTipologia+"&costo="+costo+"&num="+numOfTickets);
  }
}

function removeFromCart(codEvento, codTipologia, costo, num) {
  let xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if(this.readyState == 4 && this.status == 200) {
      location.reload(true);
    }
  };
  xhttp.open("POST", "php/ajax_response/cart_handler.php");
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("codEvento="+codEvento+"&codTipologia="+codTipologia+"&costo="+costo+"&num=-"+num);
}

function emptyCart() {
  $(".cartRemover").each(function() {$(this).click();});
}
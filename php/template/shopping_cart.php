<div class="main-content">
  <?php if (!empty($templateParams["eventi"])) : ?>
    <div>
      <?php foreach ($templateParams["eventi"] as $evento): ?>
        <div class="evento list-container">
          <h1>Evento: <?php echo $evento["nomeEvento"]?></h1>
          <p>Data e ora inizio: <?php echo $evento["dataEOra"]?></p>
          <p><?php echo $evento["nomeLuogo"].", ".$evento["indirizzo"];?></p>
          <?php foreach ($_SESSION["sessUser"]["cart"][$evento["codEvento"]] as $tipoCosto => $numero): ?>
            <div class="postiEvento">
              <?php $arrTipoCosto = explode("/",$tipoCosto); ?>
              <ul>
                <li class="list-item">
                  <h2>Tipologia: <?php echo $dbh->getSeatType($arrTipoCosto[0])["nomeTipologia"]; ?><p></h2><span class="right-aligned"><?php echo ($numero." x ".intval($arrTipoCosto[1])/100)."€ = ".(intval($arrTipoCosto[1]) / 100 * intval($numero))."€";?></span></p><p class="cartRemover" onclick='removeFromCart(<?php echo $evento["codEvento"].",".$arrTipoCosto[0].",".$arrTipoCosto[1].",".$numero;?>)'>Rimuovi voce</p>
                </li>
              </ul>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach;?>
    </div>
    <p class="right-aligned">Totale: <?php echo ($total/100)."€";?></p>
    <button type="button" class="cartEmptier" onclick="emptyCart()">Svuota carrello</button><button class="pay" type="button"><a href = "php/checkout_process.php">Paga</a></button>
  <?php else:?>
  <p>Il tuo carrello è vuoto.</p>
  <?php endif;?>
</div>
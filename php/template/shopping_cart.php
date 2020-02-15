<?php if (!empty($templateParams["eventi"])) : ?>
  <button type="button" class="cartEmptier" onclick="emptyCart()">Svuota carrello</button>
  <?php foreach ($templateParams["eventi"] as $evento): ?>
    <div class="evento">
      <p><?php echo $evento["nomeEvento"]?>
      <p><?php echo $evento["dataEOra"]?></p>
      <p><?php echo $evento["nomeLuogo"]." @ ".$evento["indirizzo"];?></p>
      <?php foreach ($_SESSION["sessUser"]["cart"][$evento["codEvento"]] as $tipoCosto => $numero): ?>
        <div class="postiEvento">
          <?php $arrTipoCosto = explode("/",$tipoCosto); ?>
          <p>Tipologia: <?php echo $dbh->getSeatType($arrTipoCosto[0])["nomeTipologia"]; ?></p>
          <p><?php echo (intval($arrTipoCosto[1])/100)."€ * ".$numero." = ".(intval($arrTipoCosto[1]) / 100 * intval($numero))."€";?></p> 
          <button type="button" class="cartRemover" onclick='removeFromCart(<?php echo $evento["codEvento"].",".$arrTipoCosto[0].",".$arrTipoCosto[1].",".$numero;?>)'>Rimuovi voce</button>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endforeach;?>
  <p>Totale: <?php echo ($total/100)."€";?></p>
  <a href = "php/checkout_process.php">Paga</a>
<?php else:?>
<p>Il tuo carrello è vuoto.</p>
<?php endif;?>
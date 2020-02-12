<?php foreach ($templateParams["eventi"] as $evento): ?>
  <div class="evento">
    <p><?php echo $evento["nomeEvento"]?>
    <p><?php echo $evento["dataEOra"]?></p>
    <p><?php echo $evento["nomeLuogo"]." @ ".$evento["indirizzo"];?></p>
    <?php foreach ($_SESSION["sessUser"]["cart"][$evento["codEvento"]] as $tipoCosto => $numero): ?>
      <div class="postiEvento">
        <?php $indexes = explode("/",$tipoCosto); ?>
        <p>Tipologia: <?php echo $dbh->getSeatType($indexes[0])["nomeTipologia"]; ?></p>
        <p><?php echo (intval($indexes[1])/100)."€ * ".$numero." = ".(intval($indexes[1]) / 100 * intval($numero))."€";?></p>
      </div>
    <?php endforeach; ?>
  </div>
<?php endforeach;?>

<p>Totale: <?php echo $total."€";?></p>
<a href = "#">Paga</a>
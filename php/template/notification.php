<div class="main-content">
  <?php if (count($templateParams["notifiche"]) > 0) :?>
    <button type="button" onclick="readAll()">Segna tutti come letto</button>
    <?php foreach($templateParams["notifiche"] as $notifica):?>
      <article class="notification<?php echo $notifica["letta"] == false ? "unread" : "";?> list-item" >
        <a href="notification_details.php?codEvento=<?php echo $notifica["codEvento"]?>&codNotifica=<?php echo $notifica["codNotifica"]?>">
          <h3><?php echo $notifica["titolo"];?></h3>
          <p>Inviata alle <?php echo $notifica["dataEOraInvio"];?></p>
          <p>
            <?php 
              if ($notifica["letta"] == false) {
                echo "Da leggere.";
              } else {
                echo "Letta.";
              }
            ?>
          </p>
        </a>
      </article>
    <?php endforeach;?>
  <?php else: ?>
    <p>Non ci sono notifiche per te.</p>
  <?php endif;?>
</div>
<section>
  <h3><?php echo $templateParams["notifica"]["titolo"]?></h3>
  <p>Data e ora invio: <?php echo $templateParams["notifica"]["dataEOraInvio"];?></p>
  <p><?php echo $templateParams["notifica"]["descrizione"]?></p>
  <a href="event_details.php?codEvento=<?php echo $templateParams["notifica"]["codEvento"]?>">Mostra evento</a>
</section>
<div class="main-content">
  <section class="list-container">
    <h3>Recensioni per l'evento <?php echo $templateParams["nomeEvento"]?></h3>
    <p>Recensioni: <a href="event_reviews.php?codEvento=<?php echo $_GET["codEvento"]?>">(Mostra tutte le recensioni)</a></p>
      <?php foreach($templateParams["recensioni"] as $recensione ): ?>
        <article class="list-item">
          <h4><?php echo ($recensione["anonima"] == 0 ? $recensione["emailUtente"] : "Anonimo")?></h4>
          <p>Voto: <?php echo $recensione["voto"]?></p>
          <p><?php echo $recensione["testo"]?></p>
          <p>Scritta il: <?php echo $recensione["dataScrittura"]?></p>
        </article>
      <?php endforeach; ?>
  </section>
</div>
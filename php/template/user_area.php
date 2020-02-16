<div class="main-content">
  <h1> Area utente </h1>
  <!-- Dati personali -->
  <section id="personalSection">
    <h2>Informazioni personali</h2>
    <p>Indirizzo mail: <?php echo $_SESSION["sessUser"]["email"]?></p>
    <p>Nome e cognome: <?php echo $templateParams["userData"]["nome"]." ".$templateParams["userData"]["cognome"]?></p>
    <p>Genere: <?php echo $genere[$templateParams["userData"]["genere"]];?></p>
    <p>Nato il: <?php echo $templateParams["userData"]["dataNascita"]?></p>
    <p>Iscritto il: <?php echo $templateParams["userData"]["dataIscrizione"]?></p>
  </section>

  <!-- Eventi osservati -->
  <div id="observedSection">
    <button class="collapsableBtn" type="button"><img class="collapsableIcon" src="img/ArrowIcon.png" alt="toggleEventiOsservati"/>Eventi osservati</button>
      <div class="articles list-container">
        <?php
        if (count($templateParams["observedEvents"]) == 0) {
          echo "<p>Non stai osservando alcun evento.</p>";
        }
        ?>
        <?php foreach($templateParams["observedEvents"] as $osservato):?>
          <article class="list-item">
            <a href="event_details.php?codEvento=<?php echo $osservato["codEvento"];?>">
              <h4><?php echo $osservato["nomeEvento"];?></h4>
              <span><?php echo "Data: ".$osservato["dataEOra"];?></span><br/>
              <span><?php echo $osservato["nomeLuogo"].", ".$osservato["indirizzo"];?></span><br/>
              <span>Posti: <?php echo $osservato["postiOccupati"]."/".min($osservato["capienzaMassima"], $osservato["maxPostiDisponibili"])." (".$osservato["percPostiOccupati"]."%)"?></span><br/>
            </a>
          </article>
        <?php endforeach;?>
        <a href="#observedSection">Torna in cima</a>
      </div>
  </div>

  <!-- Eventi organizzati -->
  <div id="organizedSection">
  <button class="collapsableBtn" type="button"><img class="collapsableIcon" src="img/ArrowIcon.png" alt="toggleEventiOrganizzati"/>Eventi Organizzati</button>
      <div class="articles list-container">
      <?php
        if (count($templateParams["organizedEvents"]) == 0) {
          echo "<p>Non hai organizzato alcun evento.</p>";
        }
        ?>
        <?php foreach($templateParams["organizedEvents"] as $organizzato):?>
          <article class="list-item">
              <a href="event_details.php?codEvento=<?php echo $organizzato["codEvento"];?>">
              <h4><?php echo $organizzato["nomeEvento"];?></h4>
              <span><?php echo "Data: ".$organizzato["dataEOra"];?></span><br/>
              <span><?php echo $organizzato["nomeLuogo"].", ".$organizzato["indirizzo"];?></span><br/>
              <span>Posti: <?php echo $organizzato["postiOccupati"]."/".min($organizzato["capienzaMassima"], $organizzato["maxPostiDisponibili"])." (".$organizzato["percPostiOccupati"]."%)"?></span><br/>
            </a>
          </article>
        <?php endforeach;?>
        <a href="#organizedSection">Torna in cima</a>
      </div>
  </div>

  <!-- Eventi moderati -->
  <div id="moderatedSection">
  <button class="collapsableBtn" type="button"><img class="collapsableIcon" src="img/ArrowIcon.png" alt="toggleEventiModerati"/>Eventi moderati</button>
      <div class="articles list-container">
      <?php
        if (count($templateParams["moderatedEvents"]) == 0) {
          echo "<p>Non sei stato nominato moderatore per alcun evento.</p>";
        }
        ?>
        <?php foreach($templateParams["moderatedEvents"] as $moderato):?>
          <article>
            <a href="event_details.php?codEvento=<?php echo $moderato["codEvento"];?>">
              <h4><?php echo $moderato["nomeEvento"];?></h4>
              <span><?php echo "Data: ".$moderato["dataEOra"];?></span><br/>
              <span><?php echo $moderato["nomeLuogo"].", ".$moderato["indirizzo"];?></span><br/>
              <span>Posti: <?php echo $moderato["postiOccupati"]."/".min($moderato["capienzaMassima"], $moderato["maxPostiDisponibili"])." (".$moderato["percPostiOccupati"]."%)"?></span><br/>
            </a>
          </article>
        <?php endforeach;?>
        <a href="#moderatedSection">Torna in cima</a>
      </div>
  </div>

  <!-- Eventi presieduti -->
  <div id="bookedSection">
    <button class="collapsableBtn" type="button"><img class="collapsableIcon" src="img/ArrowIcon.png" alt="toggleEventiPrenotati"/>Eventi prenotati</button>
      <div class="articles">
      <?php
        if (count($templateParams["bookedEvents"]) == 0) {
          echo "<p>Non hai prenotato alcun evento.</p>";
        }
        ?>
        <?php foreach($templateParams["bookedEvents"] as $prenotato):?>
          <article class="list-container">
            <a href="event_details.php?codEvento=<?php echo $prenotato["codEvento"];?>">
              <h4><?php echo $prenotato["nomeEvento"];?></h4>
              <span><?php echo "Data: ".$prenotato["dataEOra"];?></span><br/>
              <span><?php echo $prenotato["nomeLuogo"].", ".$prenotato["indirizzo"];?></span><br/>
            </a>
            <section>
              <h3>Posti prenotati: <?php echo $prenotato["postiOccupati"]?></h3>
              <button class="collapsableBtn" type="button"><img class="collapsableIcon" src="img/ArrowIcon.png" alt="togglePostiPrenotati"/> Dettagli </button>
              <div class="articles">
              <?php foreach ($prenotato["bigliettiPrenotati"] as $biglietto): ?>
                <article class="list-item">
                  <p>Codice posto: <?php echo $biglietto["codEvento"]."-".$biglietto["codPosto"]?></p>
                  <p>Tipologia posto: <?php echo $biglietto["nomeTipologia"];?></p>
                  <p>Codice prenotazione: <?php echo $biglietto["codPrenotazione"];?></p>
                </article>
              <?php endforeach; ?>
              </div>
            </section>
          </article>
          
        <?php endforeach;?>
        <a href="#bookedSection">Torna in cima</a>
      </div>
  </div>

  <div id="reviewSection">
  <button class="collapsableBtn" type="button"><img class="collapsableIcon" src="img/ArrowIcon.png" alt="toggleRecensioniScritte"/>Le tue recensioni</button>
      <div class="articles list-container">
      <?php if (count($templateParams["recensioni"]) == 0) {
          echo "<p>Non hai scritto alcuna recensione.</p>";
        }
        ?>
        <?php foreach($templateParams["recensioni"] as $recensione):?>
          <article class="list-item">
              <h4><a href="event_details.php?codEvento=<?php echo $recensione["codEvento"];?>">Evento:  <?php echo $dbh->getEventName($recensione["codEvento"]);?></a></h4>
              <p>Voto: <?php echo $recensione["voto"]?></p>
              <p><?php echo $recensione["testo"]?></p>
              <?php if($recensione["anonima"]) {
                echo "<p>Pubblicata anonimamente</p>";
              } ?>
              <p>Data scrittura:<?php echo $recensione["dataScrittura"];?></p><br/>
          </article>
        <?php endforeach;?>
        <a href="#reviewSection">Torna in cima</a>
      </div>
  </div>
</div>
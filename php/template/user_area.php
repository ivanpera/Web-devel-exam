<!-- Dati personali -->
<section id="personalSection">
  <p><?php echo $_SESSION["sessUser"]["email"]?></p>
  <p><?php echo $templateParams["userData"]["nome"]." ".$templateParams["userData"]["cognome"]?></p>
  <p>Genere: <?php echo $genere[$templateParams["userData"]["genere"]];?></p>
  <p>Nato il: <?php echo $templateParams["userData"]["dataNascita"]?></p>
  <p>Iscritto il: <?php echo $templateParams["userData"]["dataIscrizione"]?></p>
</section>

<!-- Eventi osservati -->
<section id="observedSection">
  <button class="collapsableBtn"><img class="collapsableIcon" src="img/ArrowIcon.png"/>Eventi osservati</button>
    <div class="articles">
      <?php
      if (count($templateParams["observedEvents"]) == 0) {
        echo "<p>Non stai osservando alcun evento.</p>";
      }
      ?>
      <?php foreach($templateParams["observedEvents"] as $osservato):?>
        <a href="event_details.php?codEvento=<?php echo $osservato["codEvento"];?>">
        <article>
            <h4><?php echo $osservato["nomeEvento"];?></h4>
            <span><?php echo "Data: ".$osservato["dataEOra"];?></span><br/>
            <span><?php echo $osservato["nomeLuogo"]." @ ".$osservato["indirizzo"];?></span><br/>
            <span class="description_span"><?php echo $osservato["descrizione"];?></span>
            <span>Posti: <?php echo $osservato["postiOccupati"]."/".$osservato["capienzaMassima"]." (".$osservato["percPostiOccupati"]."%)"?></span><br/>
        </article>
        </a>
      <?endforeach;?>
      <a href="#observedSection">Return at the top</a>
    </div>
</section>

<!-- Eventi organizzati -->
<section id="organizedSection">
<button class="collapsableBtn"><img class="collapsableIcon" src="img/ArrowIcon.png"/>Eventi Organizzati</button>
    <div class="articles">
    <?php
      if (count($templateParams["organizedEvents"]) == 0) {
        echo "<p>Non hai organizzato alcun evento.</p>";
      }
      ?>
      <?php foreach($templateParams["organizedEvents"] as $organizzato):?>
        <a href="event_details.php?codEvento=<?php echo $organizzato["codEvento"];?>">
        <article>
            <h4><?php echo $organizzato["nomeEvento"];?></h4>
            <span><?php echo "Data: ".$organizzato["dataEOra"];?></span><br/>
            <span><?php echo $organizzato["nomeLuogo"]." @ ".$organizzato["indirizzo"];?></span><br/>
            <span class="description_span"><?php echo $organizzato["descrizione"];?></span>
            <span>Posti: <?php echo $organizzato["postiOccupati"]."/".$organizzato["capienzaMassima"]." (".$organizzato["percPostiOccupati"]."%)"?></span><br/>
        </article>
        </a>
      <?endforeach;?>
      <a href="#organizedSection">Return at the top</a>
    </div>
</section>

<!-- Eventi moderati -->
<section id="moderatedSection">
<button class="collapsableBtn"><img class="collapsableIcon" src="img/ArrowIcon.png"/>Eventi moderati</button>
    <div class="articles">
    <?php
      if (count($templateParams["moderatedEvents"]) == 0) {
        echo "<p>Non sei stato nominato moderatore per alcun evento.</p>";
      }
      ?>
      <?php foreach($templateParams["moderatedEvents"] as $moderato):?>
        <a href="event_details.php?codEvento=<?php echo $moderato["codEvento"];?>">
        <article>
            <h4><?php echo $moderato["nomeEvento"];?></h4>
            <span><?php echo "Data: ".$moderato["dataEOra"];?></span><br/>
            <span><?php echo $moderato["nomeLuogo"]." @ ".$moderato["indirizzo"];?></span><br/>
            <span class="description_span"><?php echo $moderato["descrizione"];?></span>
            <span>Posti: <?php echo $moderato["postiOccupati"]."/".$moderato["capienzaMassima"]." (".$moderato["percPostiOccupati"]."%)"?></span><br/>
        </article>
        </a>
      <?endforeach;?>
      <a href="#moderatedSection">Return at the top</a>
    </div>
</section>

<!-- Eventi presieduti -->
<section id="bookedSection">
  <button class="collapsableBtn"><img class="collapsableIcon" src="img/ArrowIcon.png"/>Eventi prenotati</button>
    <div class="articles">
    <?php
      if (count($templateParams["bookedEvents"]) == 0) {
        echo "<p>Non hai prenotato alcun evento.</p>";
      }
      ?>
      <?php foreach($templateParams["bookedEvents"] as $prenotato):?>
        <a href="event_details.php?codEvento=<?php echo $prenotato["codEvento"];?>">
        <article>
            <h4><?php echo $prenotato["nomeEvento"];?></h4>
            <span><?php echo "Data: ".$prenotato["dataEOra"];?></span><br/>
            <span><?php echo $prenotato["nomeLuogo"]." @ ".$prenotato["indirizzo"];?></span><br/>
            <span class="description_span"><?php echo $prenotato["descrizione"];?></span>
            <span>Posti: <?php echo $prenotato["postiOccupati"]."/".$prenotato["capienzaMassima"]." (".$prenotato["percPostiOccupati"]."%)"?></span><br/>
        </article>
        </a>
      <?endforeach;?>
      <a href="#bookedSection">Return at the top</a>
    </div>
</section>

<!-- Recensioni
<section></section>-->
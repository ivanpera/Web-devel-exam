<div class="main-content">
    <section id="result_events" class="tabcontent">
        <h1> Risulati di ricerca </h1>
        <?php 
        if(count($templateParams["events"]) <= 0): ?>
            <p>Nessun evento trovato con questi parametri.</p>
            <a class="redirect-link" href="events.php">Cerca con parametri diversi.</a>
        <?php endif;?>
        <?php
            foreach ($templateParams["events"] as $event):
        ?>
        <a href="event_details.php?codEvento=<?php echo $event["codEvento"];?>">
            <article>
                <!--<img src="<?php /*if (file_exists("img/".$event["emailOrganizzatore"]."/".$event["nomeImmagine"]) && is_file("img/".$event["emailOrganizzatore"]."/".$event["nomeImmagine"])) {
                    echo "img/".$event["emailOrganizzatore"]."/".$event["nomeImmagine"];
                    } else {
                        echo "img/image-not-available.jpg";
                    }*/?>" alt="" class="eventImgPreview"/>-->
                <h2><?php echo $event["nomeEvento"];?></h2>
                <p><?php echo $event["dataEOra"];?></p>
                <p><?php echo $event["nomeLuogo"].", ".$event["indirizzo"];?></p>
                <p class="description_p"><?php echo '<span class="description_span">Descrizione</span>'.$event["descrizione"];?></p>
                <?php if($event["dataEOra"] > date("Y-m-d H:i:s")):?>
                <p>Posti disponibili: <?php echo min($event["capienzaMassima"], $event["maxPostiDisponibili"]) - $event["postiOccupati"]?></p>
                <?php endif; ?>
            </article>
        </a>
        <?php endforeach;?>
    </section>
</div>
<div class="main-content">
    <section id="result_events" class="tabcontent">
        <h1> Risulati di ricerca </h1>
        <?php 
        if(count($templateParams["events"]) <= 0): ?>
            <p>No event found with this search terms.</p>
            <a href="events.php">Search with different terms</a>
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
                <span><?php echo $event["dataEOra"];?></span>
                <span><?php echo $event["nomeLuogo"].", ".$event["indirizzo"];?></span>
                <span class="description_span"><?php echo "<h3>Descrizione</h3>\n".$event["descrizione"];?></span>
                <?php if($event["dataEOra"] > date("Y-m-d H:i:s")):?>
                <span>Posti <?php echo ($event["dataEOra"] > date("Y-m-d H:i:s") ? "disponibili" : "rimasti" );?>: <?php echo min($event["capienzaMassima"], $event["maxPostiDisponibili"]) - $event["postiOccupati"]?></span>
                <?php endif; ?>
            </article>
        </a>
        <?php endforeach;?>
    </section>
</div>
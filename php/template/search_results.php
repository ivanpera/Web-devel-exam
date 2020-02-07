<section id="result_events" class="tabcontent">
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
            <!-- <img src="<?php /*if (file_exists("img/".$event["emailOrganizzatore"].$event["nomeImmagine"])) {
                echo "img/".$event["emailOrganizzatore"].$event["nomeImmagine"];
                } else {
                    echo "img/image-not-available.jpg";
                }?>" alt=""/> */?>-->
            <h4><?php echo $event["nomeEvento"];?></h4>
            <span><?php echo "Data: ".$event["dataEOra"];?></span><br/>
            <span><?php echo $event["nomeLuogo"]." @ ".$event["indirizzo"];?></span><br/>
            <span class="description_span"><?php echo $event["descrizione"];?></span>
            <span>Posti: <?php echo $event["postiOccupati"]."/".$event["capienzaMassima"]." (".$event["percPostiOccupati"]."%)"?></span><br/>
        </article>
    </a>
    <?php endforeach;?>
</section>
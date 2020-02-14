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
                <h2><?php echo $event["nomeEvento"];?></h2>
                <span><?php echo $event["dataEOra"];?></span>
                <span><?php echo $event["nomeLuogo"].", ".$event["indirizzo"];?></span>
                <span class="description_span"><?php echo "<h3>Descrizione</h3>\n".$event["descrizione"];?></span>
                <span>Posti <?php echo ($event["dataEOra"] > date("Y-m-d H:i:s") ? "disponibili" : "rimasti" );?>: <?php echo min($event["capienzaMassima"], $event["maxPostiDisponibili"]) - $event["postiOccupati"]?></span>
            </article>
        </a>
        <?php endforeach;?>
    </section>
</div>
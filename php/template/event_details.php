<section>
    <img src="<?php echo "img/".(file_exists($templateParams["evento"]["emailOrganizzatore"].$templateParams["evento"]["nomeImmagine"]) ? $templateParams["evento"]["emailOrganizzatore"].$templateParams["evento"]["nomeImmagine"] : "image-not-available.jpg")?>" alt="event_image"/>
    <h3><?php echo $templateParams["evento"]["nomeEvento"]?> </h3>
    <p><?php echo $templateParams["evento"]["dataEOra"]?>
    <p>  <?php echo $templateParams["evento"]["descrizione"] ?> </p>

    <p> Dove: <?php echo $templateParams["evento"]["nomeLuogo"]." @ ".$templateParams["evento"]["indirizzo"]?> </p>

    <label for="btn_show_on_map">
        <button type="button" id="btn_show_on_map">
            Mostralo sulla mappa
        </button>
    </label>

    <p>
        Biglietti: <?php echo $templateParams["evento"]["postiOccupati"]." occupati su ".min($templateParams["evento"]["capienzaMassima"],$templateParams["evento"]["maxPostiDisponibili"])?>
    </p>
    <p> Organizzatore: <?php echo $templateParams["evento"]["emailOrganizzatore"] ?> </p>
    
    <?php
        if (!empty($templateParams["moderatori"])) {
            echo "<p> Moderatori: </p>";
            foreach ($templateParams["moderatori"] as $moderatore){
                echo "<p>".$moderatore["emailModeratore"]."</p>";
            }
        }
    ?>
    <!-- Stampa le recensioni -->
    <!--php check, if the the user is not logged in, display the "log in first to purchase", else display the "proceed to check out" -> disable it if no tickets are selected -->


    <!-- php check, if the user is the organizer, they can modify the event -->
</section>
<div class="main-content">
    <section>
        <h1><?php echo $templateParams["evento"]["nomeEvento"]?></h1>
        <img src="<?php echo "img/".(file_exists("img/".$templateParams["evento"]["emailOrganizzatore"]."/".$templateParams["evento"]["nomeImmagine"]) && is_file("img/".$templateParams["evento"]["emailOrganizzatore"]."/".$templateParams["evento"]["nomeImmagine"]) ? $templateParams["evento"]["emailOrganizzatore"]."/".$templateParams["evento"]["nomeImmagine"] : "image-not-available.jpg")?>" alt="event_image"/>
        <p><?php echo $templateParams["evento"]["dataEOra"]?>
        <p>  <?php echo $templateParams["evento"]["descrizione"] ?> </p>

        <p> Dove: <?php echo $templateParams["evento"]["nomeLuogo"].", ".$templateParams["evento"]["indirizzo"]?> </p>

        <label for="btn_show_on_map">
            <button type="button" id="btn_show_on_map">
                Mostralo sulla mappa
            </button>
        </label>

    <div id="tickets">
        Biglietti disponibili: <?php echo min($templateParams["evento"]["capienzaMassima"],$templateParams["evento"]["maxPostiDisponibili"]) - $templateParams["evento"]["postiOccupati"]?>
        <?php if(!isset($_SESSION["sessUser"])) {
            $_SESSION["previousPage"] = "event_details.php?codEvento=".$_GET["codEvento"]."#tickets";
            echo '<p>Per aggiungere biglietti al carrello devi essere autenticato. <a href="login.php">Autenticati</a></p>';
        }?>
        <?php foreach ($templateParams["tickets"] as $biglietto):?>
        <div>
            <p>Tipo: <?php echo $biglietto["nomeTipologia"];?></p>
            <p>Prezzo: <?php printf("%.2f", $biglietto["costo"]/100)?></p>
            <p>Disponibili: <?php echo $biglietto["numTotPosti"] - $biglietto["postiPrenotati"];?></p>
            <?php
            if (isset($_SESSION["sessUser"])) {
                echo '<label>Numero biglietti:<input type="number" value="1" min="0" max="'.($biglietto["numTotPosti"] - $biglietto["postiPrenotati"]).'"/><button onclick="addToCart('.$_GET["codEvento"].','.$biglietto["codTipologia"].','.$biglietto["costo"].')">Aggiungi al carrello</button></label>';
            }
            ?>
        </div>
        <?php endforeach;?>
    </div>
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
    <?php if (isset($_SESSION["sessUser"]) && $templateParams["evento"]["emailOrganizzatore"] == $_SESSION["sessUser"]["email"]) {
        echo '<a href="modify_event.php?codEvento='.$templateParams["evento"]["codEvento"].'">Modifica evento</a>';
    }
    if (isset($_SESSION["sessUser"]) && $templateParams["evento"]["emailOrganizzatore"] != $_SESSION["sessUser"]["email"]) {
        echo '<button class="observe_btn" onclick="toggleObserveStatus('.$templateParams["evento"]["codEvento"].', \''.$_SESSION["sessUser"]["email"].'\')"></button>';
    }
    ?>
</section>

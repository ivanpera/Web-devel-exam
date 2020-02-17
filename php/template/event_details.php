<div class="main-content">
    <?php if(isset($_GET["uploadError"])):?>
        <p>Errore: <?php echo $errorMessages[$_GET["uploadError"]];?></p>
    <?php endif;?>
    <section>
        <h1><?php echo $templateParams["evento"]["nomeEvento"]?></h1>
        <?php if (isset($_SESSION["sessUser"]) && $templateParams["evento"]["emailOrganizzatore"] == $_SESSION["sessUser"]["email"]) {
            echo '<a href="modify_event.php?codEvento='.$templateParams["evento"]["codEvento"].'">Modifica evento</a>';
        }?>
        <img src="<?php echo "img/".(file_exists("img/".$templateParams["evento"]["emailOrganizzatore"]."/".$templateParams["evento"]["nomeImmagine"]) && is_file("img/".$templateParams["evento"]["emailOrganizzatore"]."/".$templateParams["evento"]["nomeImmagine"]) ? $templateParams["evento"]["emailOrganizzatore"]."/".$templateParams["evento"]["nomeImmagine"] : "image-not-available.jpg")?>" alt="event_image"/>
        <p><?php echo $templateParams["evento"]["dataEOra"]?>
        <p>  <?php echo $templateParams["evento"]["descrizione"] ?> </p>

        <p> Dove: <?php echo $templateParams["evento"]["nomeLuogo"].", ".$templateParams["evento"]["indirizzo"]?> </p>

        <!--<label for="btn_show_on_map">
            <button type="button" id="btn_show_on_map">
                Mostralo sulla mappa
            </button>
        </label>-->
    <?php if($templateParams["evento"]["dataEOra"] > date("Y-m-d H:i:s")):?>
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
                echo '<label>Numero biglietti:<input type="number" value="'.min(1, $biglietto["numTotPosti"] - $biglietto["postiPrenotati"]).'" min="0" max="'.($biglietto["numTotPosti"] - $biglietto["postiPrenotati"]).'"/></label>'.($biglietto["numTotPosti"] - $biglietto["postiPrenotati"] > 0 ? '<button onclick="addToCart('.$_GET["codEvento"].','.$biglietto["codTipologia"].','.$biglietto["costo"].')">Aggiungi al carrello</button>' : '');
            }
            ?>
        </div>
        <?php endforeach;?>
    </div>
    <?php endif;?>
    <p> Organizzatore: <?php echo $templateParams["evento"]["emailOrganizzatore"] ?> </p>
    
    <?php
        if (!empty($templateParams["moderatori"])) {
            echo "<p> Moderatori: </p>";
            foreach ($templateParams["moderatori"] as $moderatore){
                echo "<p>".$moderatore["emailModeratore"]."</p>";
            }
        }
    ?>
    <?php
    if (isset($_SESSION["sessUser"]) && $templateParams["evento"]["emailOrganizzatore"] != $_SESSION["sessUser"]["email"]) {
        echo '<button class="observe_btn" onclick="toggleObserveStatus('.$templateParams["evento"]["codEvento"].', \''.$_SESSION["sessUser"]["email"].'\')"></button>';
    }
    ?>
    <div class="reviews">
        <?php if(!isset($_SESSION["sessUser"]) && $templateParams["evento"]["dataEOra"] < date("Y-m-d H:i:s")):?>
            <a href="login.php">Autenticati per recensire questo evento!</a>
        <?php endif;?>
        <?php if(!$templateParams["utenteHaRecensito"] && $templateParams["evento"]["dataEOra"] < date("Y-m-d H:i:s") && $templateParams["userCanReview"]):?>
            <a href="create_review.php?codEvento=<?php echo $_GET["codEvento"]?>">Recensisci questo evento</a>
        <?php endif;?>
        <?php if(!empty($templateParams["recensioni"])):?>
            <p>Recensioni più recenti: <a href="event_reviews.php?codEvento=<?php echo $_GET["codEvento"]?>">(Mostra tutte le recensioni)</a></p>
            <p>Voto medio: <?php printf("%.1f",$templateParams["votoMedioRecensioni"])?></p>
            <?php foreach($templateParams["recensioni"] as $recensione ): ?>
                <article class="list-item">
                    <h5>Recensione di: <?php echo ($recensione["anonima"] == 0 ? $recensione["emailUtente"] : "Anonimo")?></h5>
                    <p>Voto: <?php echo $recensione["voto"]?></p>
                    <p><?php echo $recensione["testo"]?></p>
                    <p>Scritta il: <?php echo $recensione["dataScrittura"]?></p>
                </article>
              <?php endforeach; ?>
        <?php else:?>
            <p>Non sono ancora state scritte recensioni per questo evento.</p>
        <?php endif;?>
    </div>
    </section>
</div>
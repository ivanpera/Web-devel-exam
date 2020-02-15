<!-- I'm following the template at https://www.w3schools.com/howto/howto_js_tabs.asp -->
<div class="tab">
    <button type="button" id="top_btn" class="tablinks" onclick=showTopEvents()>Top</button><button type="button" id="form_btn" class="tablinks" onclick=showSearchForm()>Cerca</button>
</div>

<div class="main-content">
    <section id="top_events" class="tabcontent">
        <h1> Eventi più popolari </h1>
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
                <span>Posti disponibili: <?php echo min($event["capienzaMassima"], $event["maxPostiDisponibili"]) - $event["postiOccupati"]?></span>
            </article>
        </a>
        <?php endforeach;?>
    </section>

    <div id="search_form" class="tabcontent">
        <form action="search_results.php" method="get">
            <label for="event_name">Nome evento:<input type="text" id="event_name" name="event_name"/></label>
            <label for="place">Luogo:
                <select name="luogo" id="place">
                    <option value="" disabled selected hidden>Seleziona un luogo...</option>
                    <?php foreach ($templateParams["luoghi"] as $luogo): ?>
                        <option value=<?php echo $luogo["codLuogo"];?>><?php echo $luogo["nome"]?></option>
                    <?php endforeach;?>
                </select>
            </label>
            <label for="start_date">Dal:<input type="date" id="start_date" name="fromDate"/></label> <!--TODO: Add min and max attributes defined using php -->
            <label for="end_date">Al:<input type="date" id="end_date" name="toDate"></label> <!--TODO: Add min and max attributes defined using php -->
            <label for="categories">Categorie:
                <ul id="categories">
                    <?php foreach ($templateParams["categories"] as $cat): ?>
                        <li><label for="<?php echo $cat["codCategoria"] ?>"><input type="checkbox" name="categories[]" id="<?php echo $cat["codCategoria"]?>" value="<?php echo $cat["codCategoria"]?>"/><?php echo $cat["nomeCategoria"]?></label></li>
                    <?php endforeach; ?>
            </ul>
            <input type="submit" name="btn_search" value="Cerca"/>
        </form>
    </div>
</div>
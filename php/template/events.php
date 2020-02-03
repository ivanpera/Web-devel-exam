<!-- I'm following the template at https://www.w3schools.com/howto/howto_js_tabs.asp -->
<div class="tab">
    <button class="tablinks" onclick="showTopEvents()">Top</button><button class="tablinks" onclick="showSearchForm()">Cerca</button>
</div>

<div id="top_events" class="tabcontent">
    <section>
    <?php
        foreach ($templateParams["events"] as $event):
    ?>
    <article>
            <img src="<?php if (file_exists("img/".$event["emailOrganizzatore"].$event["nomeImmagine"])) {
                echo "img/".$event["emailOrganizzatore"].$event["nomeImmagine"];
                } else {
                    echo "img/image-not-available.jpg";
                }?>" alt=""/>
            <h4><?php echo $event["nomeEvento"];?></h4>
            <p><?php echo $event["dataEOra"];?></p>
            <span>Luogo</span>
            <span>Descrizione</span>
            <span>Posti rimanenti: <?php echo $event["postiOccupati"]."/".$event["capienzaMassima"]." (".$event["percPostiOccupati"]."%)"?></span>
            <span>Categorie: <?php echo $event["categorie"];?></span>
            <span>Organizzatore: <?php echo $event["emailOrganizzatore"]; ?></span>
    </article>
    <?php endforeach;?>
    </section>
</div>

<div id="search_form" class="tabcontent">
    <form action="/search_results.php" method="get"> <!-- should we have a search_results page or should we reuse this one, hiding the form and showing the result after an ajax query? -->
        <label for="event_name">
            Nome evento: <br/>
            <input type="text" id="event_name" name="event_name"><br/>
        </label>
        <label for="city_name">
            Città: <br/>
            <input type="text" id="city_name" name="city_name"><br/>
        </label>
        <label for="date"> <!-- NOTE: The date input type is not supported in all browsers. Please be sure to test, and consider using a polyfill. We just have to choose which one -->
            Data inizio evento: <br/>
            <input type="date" id="date" name="date"><br/> <!--TODO: Add min and max attributes defined using php -->
        </label>
        Categorie: <br/>
        <ul>
            <!--to be filled with checkboxes -->
        </ul>
        <input type="submit" name="btn_search" value="Cerca"/>
    </form>
</div>
<!-- I'm following the template at https://www.w3schools.com/howto/howto_js_tabs.asp -->
<div class="tab">
    <button id="top_btn" class="tablinks" onclick=showTopEvents()>Top</button><button id="form_btn" class="tablinks" onclick=showSearchForm()>Cerca</button>
</div>

<section id="top_events" class="tabcontent">
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

<div id="search_form" class="tabcontent">
    <form action="/search_results.php" method="get"> <!-- should we have a search_results page or should we reuse this one, hiding the form and showing the result after an ajax query? -->
        <label for="event_name">
            Nome evento: <br/>
            <input type="text" id="event_name" name="event_name"><br/>
        </label>
        <label for="city_name">
            Luogo: <br/>
            <select name="luogo">
                <option value="" disabled selected hidden>Seleziona un luogo...</option>
                <?php foreach ($templateParams["luoghi"] as $luogo): ?>
                    <option value=<?php echo $luogo["codLuogo"];?>><?php echo $luogo["nome"]?></option>
                <?php endforeach;?>
            </select>
        </label>
        <label for="date"> <!-- NOTE: The date input type is not supported in all browsers. Please be sure to test, and consider using a polyfill. We just have to choose which one -->
            Dal: <br/>
            <input type="date" id="date" name="fromDate"><br/> <!--TODO: Add min and max attributes defined using php -->
            Al: <br/> 
            <input type="date" id="date" name="toDate"><br/> <!--TODO: Add min and max attributes defined using php -->
        </label>
        Categorie: <br/>
        <!-- Volendo si può mettere una serie di radio button per far scegliere all'utente se vuole usare le categorie devono esserci tutte o ne basta almeno una (AND o OR) -->
        <ul>
        <?php foreach ($templateParams["categories"] as $cat): ?>
            <li><label><input type="checkbox" name="categories[]" value="<?php echo $cat["codCategoria"]?>"/><?php echo $cat["nomeCategoria"]?></label></li>
        <?php endforeach; ?>
        </ul>
        <input type="submit" name="btn_search" value="Cerca"/>
    </form>
</div>
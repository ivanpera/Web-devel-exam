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
            <label for="start_date">Dal:<input type="date" id="start_date" name="fromDate"/></label> 
            <label for="end_date">Al:<input type="date" id="end_date" name="toDate"></label> 
            <div id="categorie">Categorie:
                    <?php foreach ($templateParams["categories"] as $cat): ?>
                        <p><label for="<?php echo $cat["codCategoria"] ?>"><input type="checkbox" name="categories[]" id="<?php echo $cat["codCategoria"]?>" value="<?php echo $cat["codCategoria"]?>"/><?php echo $cat["nomeCategoria"]?></label></p>
                    <?php endforeach; ?>
            </div>
            <input type="submit" name="btn_search" value="Cerca"/>
        </form>
    </div>
</div>
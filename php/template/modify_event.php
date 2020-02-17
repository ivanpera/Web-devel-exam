<div class="main-content">
    <form id="editForm" action="php/modify_event_process.php" method="post" enctype="multipart/form-data">
        <h3>Modifica un evento: </h3>
        <section class="tab"> 
            <h4>Informazioni di Base:</h4>
            <label for="codEvento" hidden>Codice evento</label>
            <input id="codEvento" name="codEvento" hidden value="<?php echo $_GET["codEvento"]?>"/>
            <label for="nomeEvento">Nome evento: *<input id="nomeEvento" name="nomeEvento" type="text" value="<?php echo $templateParams["evento"]["nomeEvento"]?>" placeholder="Nome dell'evento..." required class="required"/></label>
            <label for="NSFC"><input name="NSFC" id="NSFC" type="checkbox" value="1" <?php if ($templateParams["evento"]["NSFC"] == 1) { echo "checked";}?>/>Not Safe For Children</label>
            <label for="luogo">Luogo: *<select name="luogo" id="luogo" required class="required">
                <option value="" disabled hidden>Seleziona un luogo...</option>
                <?php foreach ($templateParams["luoghi"] as $luogo): ?>
                    <option value=<?php echo $luogo["codLuogo"];?> <?php if ($templateParams["evento"]["codLuogo"] == $luogo["codLuogo"]) {echo "selected";}?>><?php echo $luogo["nome"]?></option>
                <?php endforeach;?>
            </select></label>
            <label for="data">Data di inizio: *<input name="data" id="data" type="date" required class="required" value="<?php echo (new DateTime($templateParams["evento"]["dataEOra"]))->format("Y-m-d")?>"/></label>
            <label for="ora">Ora di inizio: *<input id="ora" name="ora" type="time" required class="required" value="<?php echo (new DateTime($templateParams["evento"]["dataEOra"]))->format("H:i")?>"/></label>
            <label for="description">Descrizione evento: <textarea id="description" name="description" form="editForm" placeholder="Descrizione dell'evento..."> <?php echo $templateParams["evento"]["descrizione"];?> </textarea></label>
        </section>

        <section class="tab">
            <h4>Informazioni aggiuntive:</h4>
            <label for="currentImageName" hidden>Immagine attuale:
            <input id="currentImageName" type="text" value="<?php echo $templateParams["evento"]["nomeImmagine"]?>" name="currentImageName" hidden/></label>
            <p>Immagine attuale: <?php echo !empty($templateParams["evento"]["nomeImmagine"]) ? '<br/><img src="'.'img/'.$_SESSION["sessUser"]["email"].'/'.$templateParams["evento"]["nomeImmagine"].'" class="imgMiniature" alt=""/>'.$templateParams["evento"]["nomeImmagine"] : "Nessuna immagine caricata precedentemente" ?></p>
            <label for="image_picker"> Scegli un'immagine: <br/><input type="file" id="image_picker" name="imageName"/></label>
            <div id="catg_list">Aggiungi delle categorie:
            <?php foreach ($templateParams["categories"] as $category): ?>
                <p><input id="cat_<?php echo $category["codCategoria"] ?>" type="checkbox" name="categories[]" value="<?php echo $category["codCategoria"]?>" <?php if(strpos($templateParams["evento"]["categorie"], $category["nomeCategoria"]) !== false) {echo "checked";} ?>/><label for="cat_<?php echo $category["codCategoria"] ?>"><?php echo $category["nomeCategoria"];?></label></p>
            <?php endforeach; ?>
            </div>
        </section>

        <section id="section_biglietti" class="tab">
            <h4>Biglietti:</h4>
            <p id="maxCapacity" data-max-capacity="<?php echo $templateParams["evento"]["capienzaMassima"]?>">Capacità massima del luogo: <?php echo $templateParams["evento"]["capienzaMassima"]?></p>
            <?php $numTickets = 0 ?>
            <?php foreach($templateParams["biglietti"] as $biglietto): ?>
                <div class="ticket_creator" id=<?php echo "ticket_creator_".$numTickets?>>
                    <label for="ticket_type_<?php echo $numTickets?>">Tipo biglietto: <?php echo $numTickets == 0 ? "*" : "" ;?></label>
                    <select id="ticket_type_<?php echo $numTickets?>" name="ticket_type[]" required class="required">
                    <option value="" disabled hidden>Seleziona una categoria...</option>
                        <?php foreach ($templateParams["tipoPosti"] as $tipoPosto): ?>
                            <option value="<?php echo $tipoPosto["codTipologia"]; ?>" <?php if($biglietto["codTipologia"] == $tipoPosto["codTipologia"]) { echo "selected";} elseif ($biglietto["postiPrenotati"] > 0) {echo "disabled";}?>><?php echo $tipoPosto["nomeTipologia"];?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="<?php echo "ticket_cost_".$numTickets ?>">Costo unitario del biglietto: <?php echo $numTickets == 0 ? "*" : "" ;?></label><input id="<?php echo "ticket_cost_".$numTickets ?>" name="ticket_cost[]" type="number" min="0" step="1" required class="required" value="<?php printf("%.2f", $biglietto["costo"]/100); ?>" <?php echo ($biglietto["postiPrenotati"] > 0 ? "readonly" : "")?>/>
                    <label for="<?php echo "num_tickets_".$numTickets ?>"> Numero biglietti: <?php echo $numTickets == 0 ? "*" : "" ;?></label><input type="number" min="<?php echo $biglietto["postiPrenotati"];?>" name="num_tickets[]" id="<?php echo "num_tickets_".$numTickets?>" required class="required" value="<?php echo $biglietto["numTotPosti"]?>"/>
                    <?php
                        if($biglietto["postiPrenotati"] == 0) {
                            $message = '<label for="rm_ticket_'.$numTickets.'" class="visuallyhidden">Rimuovi tipologia di biglietto</label>
                                        <button title="Rimuovi biglietto" id="rm_ticket_'.$numTickets.'" class="rm_ticket_btn" type="button" onclick=removeTicket('.$numTickets.')> - </button>';
                            echo $message;
                        }
                    ?>
                    <label for=<?php echo "add_ticket_".$numTickets ?> class="visuallyhidden <?php echo ($numTickets < count($templateParams["biglietti"]) - 1 ? "hidden" : "")?>">Aggiungi una tipologia di biglietto</label><button title="Aggiungi biglietto" id=<?php echo "add_ticket_".$numTickets ?> class="add_ticket_btn <?php echo ($numTickets < count($templateParams["biglietti"]) - 1 ? "hidden" : "")?>" type="button" onclick=addNewTicket()> + </button>
                </div>
                <?php $numTickets = $numTickets + 1; ?>
            <?php endforeach; ?>
        </section>

        <section id="section_moderatori" class="tab">
            <h4>Moderatori:</h4>
            <?php $numMods = 0 ?>
            <?php foreach($templateParams["moderatori"] as $mod):?>
                <div class="moderator_adder" id=<?php echo '"mod_adder_'.$numMods.'"'?>>
                        <label for=<?php echo 'mod_'.$numMods ?>>Mail del moderatore: *</label><input id=<?php echo '"mod_'.$numMods.'"'?> type="text" name="mod_mail[]" value="<?php echo $mod["emailModeratore"];?>"/>
                        <label for=<?php echo '"rm_mod_'.$numMods.'"'?> class="visuallyhidden">Rimuovi moderatore</label><button id=<?php echo '"rm_mod_'.$numMods.'"'?> title="Rimuovi moderatore" class="rm_mod_btn" type="button" onclick="removeMod(<?php echo $numMods ?>)"> - </button><label for=<?php echo '"add_mod_'.$numMods.'"'?> class="visuallyhidden <?php echo ($numMods < count($templateParams["moderatori"]) - 1 ? "hidden" : "")?>">Aggiungi un moderatore</label><button title="Aggiungi moderatore" id=<?php echo'"add_mod_'.$numMods.'"'?> class="add_mod_btn <?php echo ($numMods < count($templateParams["moderatori"]) - 1 ? "hidden" : "")?>" type="button" onclick=addNewMod()> + </button>
                        <?php $numMods = $numMods + 1; ?>
                    </div>
            <?php endforeach;?>
            <?php
                if(count($templateParams["moderatori"]) <= 0) {
                    echo '<p id="no_mod_parag">Nessun moderatore presente al momento: aggiungine uno</p>';
                    echo '<label id="no_mod_label" for="add_mod_btn" class="visuallyhidden">Aggiungi un moderatore</label><button title="Aggiungi moderatore" id="add_mod_btn" class="add_mod_btn" type="button" onclick=addNewMod()> + </button>';
                }
            ?>
        </section>

        <div class="prevNext_div">
            <label for="prevBtn" class="visuallyhidden">Vai alla prossima sezione della form </label><button type="button" id="prevBtn" onclick="changeTab(-1)">Precedente</button>
            <label for="nextBtn" class="visuallyhidden">Vai alla precedente sezione della form </label><button type="button" id="nextBtn" onclick="changeTab(1)">Successivo</button>
        </div>
        <p>* = campo obbligatorio</p>
    </form>
</div>
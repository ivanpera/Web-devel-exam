<!-- Template taken from https://www.w3schools.com/howto/howto_js_form_steps.asp -->
<div class="main-content">
    <form id="createForm" action="php/create_event_process.php" method="post" enctype="multipart/form-data">
        <h3>Crea un evento: </h3>

        <section class="tab"> 
            <h4>Informazioni di Base:</h4>
            <label for="nomeEvento">Nome evento: *<input id="nomeEvento" name="nomeEvento" type="text" placeholder="Nome dell'evento..." required class="required"/></label>
            <label for="luogo">Luogo: *<select name="luogo" id="luogo" required class="required">
                <option value="" disabled selected hidden>Seleziona un luogo...</option>
                <?php foreach ($templateParams["luoghi"] as $luogo): ?>
                    <option value=<?php echo $luogo["codLuogo"];?>><?php echo $luogo["nome"]?></option>
                <?php endforeach;?>
            </select></label>
            
            <label for="data">Data di inizio: *<input name="data" id="data" type="date" required class="required"/></label>
            <label for="ora">Ora di inizio: *<input id="ora" name="ora" type="time" required class="required"/></label>
            <label for="description">Descrizione evento: <textarea id="description" name="description" form="createForm" placeholder="Descrizione dell'evento..."></textarea></label>
        </section>

        <section class="tab"> 
            <h4>Informazioni aggiuntive:</h4>
            <label for="image_picker"> Scegli un'immagine: <br/><input type="file" id="image_picker" name="imageName"/></label>
            <div id="catg_list">Aggiungi delle categorie:
            <?php foreach ($templateParams["categories"] as $category): ?>
                <p><input id="cat_<?php echo $category["codCategoria"]?>" type="checkbox" name="categories[]" value="<?php echo $category["codCategoria"]?>"/><label for="cat_<?php echo $category["codCategoria"]?>"><?php echo $category["nomeCategoria"];?></label></p>
            <?php endforeach; ?></div>
        </section>

        <section id="section_biglietti" class="tab">
            <h4>Biglietti:</h4>
            <p id="maxCapacity" data-max-capacity=""></p>
            <div class="ticket_creator" id="ticket_creator_0">
                <label for="ticket_type_0">Tipo biglietto: *</label>
                <select id="ticket_type_0" name="ticket_type[]" required class="required">
                    <option value="" disabled selected hidden>Seleziona una categoria...</option>
                    <?php foreach ($templateParams["tipoPosti"] as $tipoPosto): ?>
                        <option value="<?php echo $tipoPosto["codTipologia"]; ?>"><?php echo $tipoPosto["nomeTipologia"];?></option>
                    <?php endforeach; ?>
                </select>
                <label for="ticket_cost_0">Costo unitario del biglietto: *</label><input id="ticket_cost_0" name="ticket_cost[]" type="number" min="0" step="1" required class="required"/>
                <label for="num_tickets_0"> Numero biglietti: *</label><input type="number" min="1" name="num_tickets[]"  value="1" id="num_tickets_0" required class="required"/>
                <label for="rm_ticket_0" style="display: none" class="visuallyhidden">Rimuovi tipologia di biglietto</label><button title="Rimuovi biglietto" id="rm_ticket_0" class="rm_ticket_btn" style="display: none" type="button" onclick=removeTicket(0)> - </button><label for="add_ticket_0" class="visuallyhidden">Aggiungi una tipologia di biglietto</label><button title="Aggiungi biglietto" id="add_ticket_0" class="add_ticket_btn" type="button" onclick=addNewTicket()> + </button>
            </div>
        </section>

        <section class="tab"> 
            <h4>Moderatori:</h4>
            <div id="section_moderatori">
                <p id="no_mod_parag">Nessun moderatore presente al momento: aggiungine uno</p>
                <label id="no_mod_label" for="add_mod_btn" class="visuallyhidden">Aggiungi un moderatore</label><button id="add_mod_btn" title="Aggiungi moderatore" class="add_mod_btn" type="button" onclick=addNewMod()> + </button>
            </div>
        </section>

        <div class="prevNext_div">
            <label for="prevBtn" class="visuallyhidden">Vai alla prossima sezione della form </label><button type="button" id="prevBtn" onclick="changeTab(-1)">Precedente</button>
            <label for="nextBtn" class="visuallyhidden">Vai alla precedente sezione della form </label><button type="button" id="nextBtn" onclick="changeTab(1)">Successivo</button>
        </div>
        <p>* = campo obbligatorio</p>
    </form>
</div>
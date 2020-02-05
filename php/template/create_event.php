<!-- Template taken from https://www.w3schools.com/howto/howto_js_form_steps.asp -->
<form id="createForm" action="TODO">
    <h3>Crea un evento: </h3>

    <div class="tab"> Informazioni di Base:
        <p><input type="text" placeholder="Nome dell'evento..." oninput="this.className = ''"/></p>
        <p><input type="text" placeholder="Luogo..." oninput="this.className=''"/></p>
        <p><input type="date" placeholder="Data..." oninput="this.className=''"/><input type="time" placeholder="Ora..." oninput="this.className=''" /></p>
        <p><textarea name="description" form="createForm" placeholder="Descrizione dell'evento..." oninput="this.className = ''"></textarea></p>
    </div>

    <section class="tab"> Informazioni aggiuntive:
        <p><label for="image_picker"> Scegli un'immagine: <br/><input type="file" id="image_picker"/></label></p> 
        <p>Aggiungi delle categorie: </p>
        <?php foreach ($templateParams["categories"] as $category): ?>
            <label><input type="checkbox" name="<?php echo "category_".$category["codCategoria"];?>" value="<?php echo $category["nomeCategoria"]?>"/><?php echo $category["nomeCategoria"];?></label>
        <?php endforeach; ?>
    </section>

    <section class="tab"> Biglietti:
        <div class="ticket_creator">
            <select required>
                <option value="" disabled selected hidden>Seleziona una categoria...</option>
                <!-- Fill trough php-->
                <?php foreach ($templateParams["tipoPosti"] as $tipoPosto): ?>
                    <option value="<?php echo $tipoPosto["codTipologia"]; ?>"><?php echo $tipoPosto["nomeTipologia"];?></option>
                <?php endforeach; ?>
            </select>
            <label>Costo per biglietto (in centesimi di Euro): <input type="number" min="0" step="1"/></label>
            <label for="num_tickets"> Numero biglietti: <input type="number" min="1" name="num_tickets" id="num_tickets"/></label>
            <button class="rm_ticket_btn" type="button" onclick=removeLastTicket()> - </button> <!-- classden for the first ticket type -->
            <button class="add_ticket_btn" type="button" onclick=addNewTicket()> + </button> <!-- Juclass a placeholder: adds another ticket to edit (todo with js) -->
        </div>
    </section>

    <section class="tab"> Moderatori:
        <div class="moderator_adder">
            <input type="text" name="mod_mail[]" placeholder="E-mail moderatore"/>
            <button class="rm_mod_btn" type="button" onclick=removeLastMod()> - </button>  <!-- Hidden for the first moderator -->
            <button class="add_mod_btn" type="button" onclick=addNewMod()> + </button> <!-- Just a placeholder: adds another moderator to edit (todo with js) -->
        </div>
    </section>

    <div style="overflow:auto;">
        <div style="float:right;">
            <button type="button" id="prevBtn" onclick="TODO">Precedente</button>
            <button type="button" id="nextBtn" onclick="TODO">Successivo</button>
        </div>
    </div>
</form>
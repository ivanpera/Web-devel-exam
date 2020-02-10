<!-- Template taken from https://www.w3schools.com/howto/howto_js_form_steps.asp -->
<?php
    if(isset($_GET["uploadError"]) && $_GET["uploadError"] < count($errorMessages)) {
        echo "<p>".$errorMessages[$_GET["uploadError"]]."</p>";
    }
?>

<form id="createForm" action="php/create_event_process.php" method="post" enctype="multipart/form-data">
    <h3>Crea un evento: </h3>

    <div class="tab"> Informazioni di Base:
        <p><input name="nomeEvento" type="text" placeholder="Nome dell'evento..." oninput="this.className = ''" required/></p>
        <p><label><input name="NSFC" type="checkbox" value="1"/>Not Safe For Children</label></p>
        <p><select name="luogo" required>
            <option value="" disabled selected hidden>Seleziona un luogo...</option>
            <?php foreach ($templateParams["luoghi"] as $luogo): ?>
                <option value=<?php echo $luogo["codLuogo"];?>><?php echo $luogo["nome"]?></option>
            <?php endforeach;?>
        </select></p>
        <p><input name="data" type="date" placeholder="Data..." oninput="this.className=''" required/><input name="ora" type="time" placeholder="Ora..." oninput="this.className=''" required/></p>
        <p><textarea name="description" form="createForm" placeholder="Descrizione dell'evento..." oninput="this.className = ''"></textarea></p>
    </div>

    <section class="tab"> Informazioni aggiuntive:
        <p><label for="image_picker"> Scegli un'immagine: <br/><input type="file" id="image_picker" name="imageName"/></label></p> 
        <p>Aggiungi delle categorie: </p>
        <?php foreach ($templateParams["categories"] as $category): ?>
            <label><input type="checkbox" name="categories[]" value="<?php echo $category["codCategoria"]?>"/><?php echo $category["nomeCategoria"];?></label>
        <?php endforeach; ?>
    </section>

    <section class="tab"> Biglietti:
        <div class="ticket_creator">
            <select name="ticket_type[]" required>
                <option value="" disabled selected hidden>Seleziona una categoria...</option>
                <?php foreach ($templateParams["tipoPosti"] as $tipoPosto): ?>
                    <option value="<?php echo $tipoPosto["codTipologia"]; ?>"><?php echo $tipoPosto["nomeTipologia"];?></option>
                <?php endforeach; ?>
            </select>
            <label>Costo per biglietto: <input name="ticket_cost[]" type="number" min="0.01" step="0.01" required/></label>
            <label for="num_tickets"> Numero biglietti: <input type="number" min="1" name="num_tickets[]" class="num_tickets" required/></label>
            <button class="rm_ticket_btn" type="button" onclick=removeLastTicket()> - </button> <!-- classden for the first ticket type -->
            <button class="add_ticket_btn" type="button" onclick=addNewTicket()> + </button>
        </div>
    </section>

    <section class="tab"> Moderatori (un indirizzo email di un utente non registrato non verrà considerato):
        <div class="moderator_adder">
            <input type="text" name="mod_mail[]" placeholder="E-mail moderatore"/>
            <button class="rm_mod_btn" type="button" onclick=removeLastMod()> - </button>  <!-- Hidden for the first moderator -->
            <button class="add_mod_btn" type="button" onclick=addNewMod()> + </button>
        </div>
    </section>

    <input type="submit"/>

    <div style="overflow:auto;">
        <div style="float:right;">
            <button type="button" id="prevBtn" onclick="TODO">Precedente</button>
            <button type="button" id="nextBtn" onclick="TODO">Successivo</button>
        </div>
    </div>
</form>
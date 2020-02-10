<!-- Template taken from https://www.w3schools.com/howto/howto_js_form_steps.asp -->
<?php
    if(isset($_GET["uploadError"]) && $_GET["uploadError"] < count($errorMessages)) {
        echo "<p>".$errorMessages[$_GET["uploadError"]]."</p>";
    }
?>

<form id="createForm" action="php/modify_event_process.php" method="post" enctype="multipart/form-data">
    <h3>Modifica un evento: </h3>
    <input type="number" name="codEvento" value="<?php echo $templateParams["evento"]["codEvento"];?>" hidden>

    <div class="tab"> Informazioni di Base:
        <p><input name="nomeEvento" type="text" value="<?php echo $templateParams["evento"]["nomeEvento"]?>" oninput="this.className = ''" required/></p>
        <p><label><input name="NSFC" type="checkbox" value="1" <?php if ($templateParams["evento"]["NSFC"] == 1) { echo "checked";}?>/>Not Safe For Children</label></p>
        <p><select name="luogo" required>
            <?php foreach ($templateParams["luoghi"] as $luogo): ?>
                <option value=<?php echo $luogo["codLuogo"];?> <?php if ($templateParams["evento"]["codLuogo"] == $luogo["codLuogo"]) {echo "selected";}?>><?php echo $luogo["nome"]?></option>
            <?php endforeach;?>
        </select></p>
        <p><input name="data" type="date" value="<?php echo (new DateTime($templateParams["evento"]["dataEOra"]))->format("Y-m-d")?>" oninput="this.className=''" required/><input name="ora" type="time" value="<?php echo (new DateTime($templateParams["evento"]["dataEOra"]))->format("H:i")?>" oninput="this.className=''" required/></p>
        <p><textarea name="description" form="createForm" oninput="this.className = ''"><?php echo $templateParams["evento"]["descrizione"];?></textarea></p>
    </div>

    <section class="tab"> Informazioni aggiuntive:
        <input name="currentImageName" type="text" value="<?php echo $templateParams["evento"]["nomeImmagine"]?>" hidden/>
        <?php if(!empty($templateParams["evento"]["nomeImmagine"])) {
            echo '<img class="preview" src="img/'.$templateParams["evento"]["emailOrganizzatore"].'/'.$templateParams["evento"]["nomeImmagine"].'"/>';
        }?>
        <p><label for="image_picker"> Scegli un'immagine: <br/><input type="file" id="image_picker" name="imageName"/></label></p>
        <p>Aggiungi delle categorie: </p>
        <?php foreach ($templateParams["categories"] as $category): ?>
            <label><input type="checkbox" name="categories[]" value="<?php echo $category["codCategoria"]?>" <?php if(strpos($templateParams["evento"]["categorie"], $category["nomeCategoria"]) !== false) {echo "checked";} ?>/><?php echo $category["nomeCategoria"];?></label>
        <?php endforeach; ?>
    </section>

    <section class="tab"> Biglietti:
        <?php foreach($templateParams["biglietti"] as $biglietto): ?>
            <div class="ticket_creator">
                <select name="ticket_type[]" required>
                    <?php foreach ($templateParams["tipoPosti"] as $tipoPosto): ?>
                        <option value="<?php echo $tipoPosto["codTipologia"]; ?>" <?php if($biglietto["codTipologia"] == $tipoPosto["codTipologia"]) { echo "selected";} elseif ($biglietto["postiPrenotati"] > 0) {echo "disabled";}?> ><?php echo $tipoPosto["nomeTipologia"];?></option>
                    <?php endforeach; ?>
                </select>
                <label>Costo per biglietto: <input name="ticket_cost[]" type="number" min="0.01" step="0.01" required value="<?php printf("%.2f",$biglietto["costo"]/100)?>" <?php if($biglietto["postiPrenotati"] > 0){echo "readonly";}?>/></label>
                <label for="num_tickets"> Numero biglietti: <input type="number" min="<?php echo $biglietto["postiPrenotati"];?>" name="num_tickets[]" id="num_tickets" required value="<?php echo $biglietto["numTotPosti"]?>"/></label>
                <? if($biglietto["postiPrenotati"] == 0) {echo  '<button class="rm_ticket_btn" type="button" onclick=removeLastTicket()> - </button>'; }  ?><!-- classden for the first ticket type -->
                <button class="add_ticket_btn" type="button" onclick=addNewTicket()> + </button>
                <br/> <!-- TO BE IMPROVED -->
            </div>
        <?php endforeach; ?>
    </section>

    <section class="tab"> Moderatori (un indirizzo email di un utente non registrato non verrà considerato):
        <div class="moderator_adder">
            <?php foreach($templateParams["moderatori"] as $mod):?>
                <input type="text" name="mod_mail[]" value="<?php echo $mod;?>"/>
                <button class="rm_mod_btn" type="button" onclick=removeLastMod()> - </button>  <!-- Hidden for the first moderator -->
                <button class="add_mod_btn" type="button" onclick=addNewMod()> + </button>
            <?php endforeach;?>
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
<div class="main-content">
    <form id="createForm" action="php/modify_event_process.php" method="post" enctype="multipart/form-data">
        <h3>Modifica un evento: </h3>
        <section class="tab"> 
            <h4>Informazioni di Base:</h4>
            <input name="codEvento" hidden value="<?php echo $_GET["codEvento"]?>"/>
            <label for="nomeEvento">Nome evento: <input id="nomeEvento" name="nomeEvento" type="text" value="<?php echo $templateParams["evento"]["nomeEvento"]?>" placeholder="Nome dell'evento..." required class="required"/></label>
            <label for="NSFC"><input name="NSFC" id="NSFC" type="checkbox" value="1" <?php if ($templateParams["evento"]["NSFC"] == 1) { echo "checked";}?>/>Not Safe For Children</label>
            <label for="luogo">Luogo: <select name="luogo" id="luogo" required class="required">
                <?php foreach ($templateParams["luoghi"] as $luogo): ?>
                    <option value=<?php echo $luogo["codLuogo"];?> <?php if ($templateParams["evento"]["codLuogo"] == $luogo["codLuogo"]) {echo "selected";}?>><?php echo $luogo["nome"]?></option>
                <?php endforeach;?>
            </select></label>
            <label for="data">Data di inizio: <input name="data" id="data" type="date" required class="required" value="<?php echo (new DateTime($templateParams["evento"]["dataEOra"]))->format("Y-m-d")?>"/></label>
            <label for="ora">Ora di inizio: <input name="ora" type="time" required class="required" value="<?php echo (new DateTime($templateParams["evento"]["dataEOra"]))->format("H:i")?>"/></label>
            <label for="description">Descrizione evento: <textarea name="description" form="createForm" placeholder="Descrizione dell'evento..."> <?php echo $templateParams["evento"]["descrizione"];?> </textarea></label>
        </section>

        <section class="tab">
            <h4>Informazioni aggiuntive:</h4>
            <label for="image_picker"> Scegli un'immagine: <br/><input name="" type="file" id="image_picker" name="filename"/></label>
            <label for="catg_list">Aggiungi delle categorie:<ul id="catg_list">
            <?php foreach ($templateParams["categories"] as $category): ?>
                <li><input type="checkbox" name="categories[]" value="<?php echo $category["codCategoria"]?>" <?php if(strpos($templateParams["evento"]["categorie"], $category["nomeCategoria"]) !== false) {echo "checked";} ?>/><?php echo $category["nomeCategoria"];?></li>
            <?php endforeach; ?></ul></label>
        </section>

        <section class="tab">
            <h4>Biglietti:</h4>
            <?php foreach($templateParams["biglietti"] as $biglietto): ?>
                <div class="ticket_creator">
                    <label for="ticket_type">Tipo biglietto:
                    <select name="ticket_type[]" required class="required">
                        <?php foreach ($templateParams["tipoPosti"] as $tipoPosto): ?>
                            <option value="<?php echo $tipoPosto["codTipologia"]; ?>" <?php if($biglietto["codTipologia"] == $tipoPosto["codTipologia"]) { echo "selected";} elseif ($biglietto["postiPrenotati"] > 0) {echo "disabled";}?>><?php echo $tipoPosto["nomeTipologia"];?></option>
                        <?php endforeach; ?>
                    </select></label>
                    <label for="ticket_cost">Costo unitario del biglietto : <input id="ticket_cost" name="ticket_cost[]" type="number" min="0" step="1" required class="required" value="<?php printf("%.2f", $biglietto["costo"]/100); ?>" <?php echo ($biglietto["postiPrenotati"] > 0 ? "readonly" : "")?>/></label>
                    <label for="num_tickets"> Numero biglietti: <input type="number" min="<?php echo $biglietto["postiPrenotati"];?>" name="num_tickets[]" id="num_tickets" required value="<?php echo $biglietto["numTotPosti"]?>"/></label>
                    <? if($biglietto["postiPrenotati"] == 0) {echo  '<label for="rm_ticket_btn" class="visuallyhidden">Rimuovi ultima tipologia di biglietto</label><button title="Rimuovi biglietto" id="rm_ticket_btn" class="rm_ticket_btn" type="button" onclick=removeLastTicket()> - </button>'; }  ?>
                    <label for="add_ticket_btn" class="visuallyhidden">Aggiungi una tipologia di biglietto</label><button title="Aggiungi biglietto" class="add_ticket_btn" type="button" onclick=addNewTicket()> + </button>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="tab">
            <h4>Moderatori:</h4>
            <div class="moderator_adder">

                <?php foreach($templateParams["moderatori"] as $mod):?>
                    <label for="mod_mail">Mail del moderatore: </label><input id="mod_mail" type="text" name="mod_mail[]" value="<?php echo $mod;?>"/>
                    <label for="rm_ticket_btn" class="visuallyhidden">Rimuovi ultimo moderatore</label><button title="Rimuovi moderatore" class="rm_mod_btn" type="button" onclick=removeLastMod()> - </button><label for="rm_ticket_btn" class="visuallyhidden">Aggiungi un moderatore</label><button title="Aggiungi moderatore" class="add_mod_btn" type="button" onclick=addNewMod()> + </button>
                <?php endforeach;?>
            </div>
        </section>

        <div style="overflow:auto;">
            <div style="float:right;">
                <label for="prevBtn" class="visuallyhidden">Vai alla prossima sezione della form </label><button type="button" id="prevBtn" onclick="changeTab(-1)">Precedente</button>
                <label for="nextBtn" class="visuallyhidden">Vai alla precedente sezione della form </label><button type="button" id="nextBtn" onclick="changeTab(1)">Successivo</button>
            </div>
        </div>
    </form>
</div>
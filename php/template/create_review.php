<div class="main-content">
  <h3>Scrivi una recensione per l'evento '<?php echo $templateParams["nomeEvento"]?>'</h3>
  <form id="reviewForm" action="php/create_review_process.php" method="post">
    <label class="visuallyhidden" for="codEvento" hidden>codEvento<input id="codEvento" name="codEvento" hidden value="<?php echo $_GET["codEvento"]?>"/></label>
    <label class="visuallyhidden" for="emailUtente" hidden>emailUtente<input id="emailUtente" name="emailUtente" hidden value="<?php echo $_SESSION["sessUser"]["email"]?>"/></label>
    <label>Voto: <span id="valoreVoto"></span><input type="range" name="voto" min="1" max="5" step="1" value="3" /></label>
    <label for="descrizione">Testo:</label>
    <textarea id="descrizione" name="descrizione" maxlength="500" oninput="checkRemainingCharacters()" onkeyup="checkRemainingCharacters()"></textarea>
    <p id="remainingChars"></p>
    <label>Anonima: <input type="checkbox" name="anonymous"/></label>
    <label class="visuallyhidden" for="pubblica" hidden>Pubblica recensione</label>
    <input id="pubblica" type="submit" value="Pubblica recensione"/>
  </form>
</div>
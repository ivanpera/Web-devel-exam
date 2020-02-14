<div class="mainContent">
  <h3>Scrivi una recensione per l'evento '<?php echo $templateParams["nomeEvento"]?>'</h3>
  <form id="reviewForm" action="php/create_review_process.php" method="post">
    <input name="codEvento" hidden value="<?php echo $_GET["codEvento"]?>"/>
    <input name="emailUtente" hidden value="<?php echo $_SESSION["sessUser"]["email"]?>"/>
    <label>Voto: <span id="valoreVoto"></span><input type="range" name="voto" min="1" max="5" step="1" value="3" /></label>
    <label>
      <textarea name="descrizione"></textarea>
    </label>
    <label>Anonima: <input type="checkbox" name="anonymous"/></label>
    <input type="submit"/>
  </form>
</div>
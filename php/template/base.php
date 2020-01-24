<!DOCTYPE html>
<html lang="it">
  <head>
    <title>Titolo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!--
    <script
			  src="https://code.jquery.com/jquery-3.4.1.min.js"
			  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
			  crossorigin="anonymous"></script>
    -->
  </head>
  <body>
    <header>  <!-- Perhaps should we use a nav?-->
    <!-- NOTE: currently, in the mockup, the sidebar button and login icon
         are not present in the login page, should we make a special case?-->
      <!--
        If the user has logged in display the button sidebar/aside section for the desktop version
        <button id="btn_open_sidebar" type="button">
        <img src="" alt="apri_sidebar"/>
      </button> -->
      <!--Else, display login icon -->
      <a href="login.php">
        <img src="" alt="login"/>
      </a>
      <a href="index.php">
        <img src="" alt="home"/>
      </a>
    </header>

    <main>
      <?php 
        if(isset($templateParams["name"])) {
          require(TEMPLATE_DIR.$templateParams["name"]); 
        }
      ?>
    </main>

    <!-- <aside id="sidebar">
          These elements are identical to the ones used in the header section, might be better to generate them through js?
        <button id="btn_close_sidebar" type="button">
          <img src="" alt="icon_sidebar"/>
        </button>
        <a href="index.php">
          <img src="" alt="home"/>
        </a>
        <ul>
          To be filled through js depending on the type of user
              Possible list items:
                -profilo
                -calendario
                -statistiche
                -messaggi (promemoria/avvisi)
                -gestione eventi (moderazione)
                -???
        </ul>
        <a href="login.php">
          <img src="" alt="login"/>
        </a>
    </aside> -->

    <footer>
      Contatti
    </footer>
  </body>
</html>
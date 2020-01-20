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
      <label for="btn_sidebar"/>
      <button id="btn_sidebar" type="button">
        <img src="" alt="icona sidebar"/>
      </button>
      <a href="home.php">
        <img src="" alt="logo"/>
      </a>
      <a href="login.php">
        <img src="" alt="icona login"/>
      </a>
    </header>

    <main>
      <?php 
        if(isset($templateParams["name"])) { 
          require($templateParams["name"]); 
        }
      ?>
    </main>

    <aside id="sidebar">
      <section>
        <!-- These elements are identical to the ones used in the header section, might be better to generate them through js? -->
        <label for="btn_sidebar"/>
        <button id="btn_sidebar" type="button">
          <img src="" alt="icona sidebar"/>
        </button>
        <a href="home.php">
          <img src="" alt="logo"/>
        </a>
        <ul>
          <!-- To be filled through js depending on the type of user 
              Possible list items:
                -profilo
                -calendario
                -statistiche
                -messaggi (promemoria/avvisi)
                -???
          -->
        </ul>
        <a href="login.php">
          <img src="" alt="icona login"/>
        </a>
      </section>
    </aside>

    <footer>
      Contatti
    </footer>
  </body>
</html>
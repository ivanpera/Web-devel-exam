<!DOCTYPE html>
<html lang="it">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <?php
      if(isset($templateParams["css"])):
          foreach($templateParams["css"] as $stylesheet):
      ?>
          <link rel="stylesheet" type="text/css" href=<?php echo CSS_DIR.$stylesheet?> />
      <?php
          endforeach;
      endif;
    ?>
    <?php if(isset($templateParams["title"])) {
        echo("<title>".$templateParams["title"]."</title>");
      } else {
        echo("<title> Bad Title </title>");
      }
    ?>

    <?php
      if(isset($templateParams["js"])):
          foreach($templateParams["js"] as $script):
      ?>
            <script src="<?php echo $script; ?>"></script>
      <?php
          endforeach;
      endif;
    ?>
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
        <?php 
        if (!isset($_SESSION["sessUser"]["email"])) { 
          echo '<a href="login.php">
          <img src="img/login_icon.png" alt="login" class="login"/>
          </a>'; } else {
            echo '<button id="openside_btn" type="button" onclick="openSidebar()"><img src="img/openSidebarIcon.png" alt="Open sidebar" /> </button>';
        } ?><a href="index.php">
          <img src="img/logo.png" alt="home" class="logo"/>
        </a>
    </header>

    <main>
      <?php 
        if(isset($templateParams["name"])) {
          require(TEMPLATE_DIR.$templateParams["name"]); 
        }
      ?>
    </main>

<!-- Maybe add an overlay which blocks input on the page below the 
     aside and which appears and disappears with the aside        -->
    <aside id=sidebar>
        <button id="closeside_btn" onclick=closeSidebar() type="button">
          <img src="img/closeSidebarIcon.png" alt="Close sidebar"/>
        </button>
        <ul>
          <li>Profilo</li>
          <li>Calendario</li>
          <li>Statistiche</li>
          <li>Messaggi</li>
          <li>Moderazione</li>
          <li><a href="php/logout_process.php">Logout</a></li>
        </ul>
    </aside>
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
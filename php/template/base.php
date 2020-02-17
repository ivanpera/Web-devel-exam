<!DOCTYPE html>
<html lang="it">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
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
  </head>
  <body>
    <div id="bg-image"></div>
    <header>
      <?php
        if (!isset($_SESSION["sessUser"]["email"])) { 
          echo '<a href="login.php">
          <img src="img/login_icon.png" alt="login" class="login"/>
          </a>'; 
        } else {
          $notificationString = (isset($notificationNumber) && $notificationNumber > 0) ? $notificationNumber :  "";
          $hidden = isset($notificationNumber) && $notificationNumber > 0 ? "" : 'class="hidden"';
          echo '<button id="openside_btn" type="button" class="notification-container" onclick="openSidebar()"><img src="img/openSidebarIcon.png" alt="Open sidebar"/><span id="notif_span" '.$hidden.'>'.$notificationString.'</span></button>';
        } 
      ?><a href="index.php"><img src="img/logo.png" alt="home" class="logo"/></a>
    </header>

    <main>
      <?php 
        if(isset($templateParams["name"])) {
          require(TEMPLATE_DIR.$templateParams["name"]); 
        }
      ?>
    </main>

    <aside id=sidebar>
        <header>
          <button id="closeside_btn" onclick=closeSidebar() type="button">
            <img src="img/openSidebarIcon.png" alt="Close sidebar"/>
          </button><a href="index.php">
            <img src="img/logo.png" alt="home" class="logo"/>
          </a>
      </header>
        <ul>
          <li><a href="user_area.php">Profilo</a></li>
          <li><a href="shopping_cart.php">Carrello</a></li>
          <!-- <li>Statistiche</li> -->
          <li><a href="notification.php">Notifiche<?php if(isset($notificationNumber) && $notificationNumber > 0) {echo " (".$notificationNumber.")";}?></a></li>
          <!-- <li>Moderazione</li> -->
          <li><a href="php/logout_process.php">Logout</a></li>
        </ul>
    </aside>
    <div id="overlay" onclick=closeSidebar()></div>
  </body>
</html>
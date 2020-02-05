<div class="headline_text"> <!-- If we are not interested in doing something sophisticated, it might be better to remove the div -->
    <h1>
        Dacci i vaini, vendiamo biglietti
    </h1>
</div>
<section> <!-- Is section here useful? -->
    <div class="text_hover_container">
        <a href="events.php">
            <img src="img/home_find.jpg" alt="image_find_event"/>
            <div class="text_hover"> Cerca eventi </div>
        </a>
    </div>
    <div class="text_hover_container">
        <a href="<?php if(isset($_SESSION["sessUser"]["email"])) { echo 'create_event.php'; }
                        else {
                            $_SESSION["previousPage"] = "create_event.php";
                            echo 'login.php'; }?>">
            <img src="img/home_create.jpg" alt="image_create_event"/>
            <div class="text_hover"> Crea eventi </div>
        </a>
    </div>
</section>

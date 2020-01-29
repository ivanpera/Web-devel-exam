<?php
    if (isset($_GET["loginFailed"]) ) {
        echo "<p>Email or password not correct.</p>";
    }
?>
<form action="php/login_process.php" method="POST">
    <label for="email">Email: <br/><input type="email" id="email" name="email"></input></label><br/>
    <label for="password">Password: <br/><input type="password" id="password" name="password" required></input></label><br/>
    <input type="submit" id="btn_login" name="btn_login" value="Login"/>
</form><br/>

<section>
    Non ancora registrato?<br/>
    <a href="signup.php">
        <button type="button">
            Registrati
        </button>
    </a>
</section>
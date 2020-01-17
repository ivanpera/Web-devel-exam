<?php
    if (isset($_GET["loginFailed"]) ) {
        echo "<p>Email or password not correct.</p>";
    }
?>
<form action="login_process.php" method="POST">
<label>Email: <input type="text" name="email"></input></label><br/>
<label>Password: <input type="password" name="password"></input></label>
<input type="submit"></input>
</form>
<a href="signup">Registrati</a>
<div class="main-content">
    <?php
        if (isset($_GET["loginFailed"]) ) {
            echo "<p>Email or password not correct.</p>";
        }
    ?>
    <form action="php/login_process.php" method="POST">
        <label for="email">Email: <br/><input type="email" id="email" name="email"/></label><br/>
        <label for="password">Password: <br/><input type="password" id="password" name="password" required/></label><br/>
        <input type="submit" id="btn_login" name="btn_login" value="Login"/>
    </form>

    <div class="form-appendage">
        <p>Non sei ancora registrato?</p>
        <a href="signup.php" class="a-button">
                Registrati
        </a>
    </div>
</div>
<div class="main-content">
    <?php
        if(isset($_GET["registrationFailed"])) {
            echo '<p class="error">'.$errorMessages[$_GET["registrationFailed"]].'</p>';
        }

    ?>
    <form action="php/signup_process.php" method="POST">
        <label for="email">Email *: <br/><input type="email" id="email" name="email" required/></label><br/>
        <label for="password">Password *: <br/><input type="password" id="password" name="password" required/></label><br/>
        <label for="password_confirmation">Reinserisci la password *: <br/><input type="password" id="password_confirmation" name="password_confirmation" required/></label><br/>
        <label for="name">Nome *:<br/><input type="text" id="name" name="name" required/></label><br/>
        <label for="surname">Cognome *: <br/><input type="text" id="surname" name="surname" required/></label><br/>
        <label for="birthdate">Data di nascita *: <br/><input type="date" id="birthdate" name="birthdate" required/></label><br/>
        <label for="gender">Genere *: <select id="gender" name="gender" required>
                    <option value="" disabled selected hidden>Seleziona un'opzione...</option>
                    <option value="M">Maschio</option>
                    <option value="F">Femmina</option>
                    <option value="O">Altro</option>
                </select></label><br/>
        <input type="submit" id="btn_register" name="btn_register" value="Invia"/>
    </form>
    <p>* = campo obbligatorio</p>
</div>
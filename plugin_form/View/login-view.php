<?php

class Login_View {
    public function display_login_form() {
        ob_start();
        ?>
        <form method="post" action="?action=login">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" name="login_submit" value="Se connecter">
        </form>

        <?php

        if (isset($_SESSION["message"])) { ?>
        <div class="error-message"><?php echo $_SESSION["message"];

        }?></div>
<?php
        return ob_get_clean();
    }
}
?>
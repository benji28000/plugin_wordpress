<?php
class Reservation_View {
    public function display_reservation_form() {
        ob_start();
        ?>
        <form method="post" action="?action=reservation">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="mdp" placeholder="Mot de passe" required>
            <input type="date" name="date" required>
            <input type="time" name="heure" required>
            <input type="submit" name="submit" value="Réserver">
        </form>
        <?php
        return ob_get_clean();
    }
}
?>
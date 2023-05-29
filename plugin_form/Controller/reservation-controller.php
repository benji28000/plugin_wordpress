<?php
require_once(__DIR__.'/../Model/reservation-model.php');
class Reservation_Controller {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function process_reservation() {

        if (isset($_POST['submit'])) {
            $nom = sanitize_text_field($_POST['nom']);
            $email = sanitize_email($_POST['email']);
            $mdp = sanitize_text_field($_POST['mdp']);
            $date = sanitize_text_field($_POST['date']);
            $heure = sanitize_text_field($_POST['heure']);

            // Effectuer des validations supplémentaires si nécessaire

            // Enregistrer la réservation dans la base de données
            $this->model->save_reservation($nom, $email, $mdp, $date, $heure);

            // Rediriger ou afficher un message de succès
            wp_safe_redirect(home_url().'/merci/');

            exit();
        }
    }
}
?>
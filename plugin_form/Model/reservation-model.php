<?php
class Reservation_Model {
    private $wpdb;
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = 'reservation';
    }

    public function save_reservation($nom, $email, $mdp, $date, $heure) {
        $password_hash=password_hash($mdp, PASSWORD_BCRYPT);
        $sql = "INSERT INTO $this->table_name (nom, email, mdp, date, heure) VALUES (%s, %s, %s, %s, %s)";

        $prepared_sql = $this->wpdb->prepare($sql, $nom, $email, $password_hash, $date, $heure);

        $this->wpdb->query($prepared_sql);
    }
}
?>









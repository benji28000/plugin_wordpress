<?php



class login_Model{


    private $wpdb;
    private $table_name;


    public function __construct(){

        global $wpdb;
        $this->wpdb= $wpdb;
        $this->table_name="utilisateur";
    }

    public function login_verif($username, $password) {
        $sql = "SELECT * FROM utilisateur WHERE username = %s AND password = %s";
        $prepared_sql = $this->wpdb->prepare($sql, $username, $password);

        $result = $this->wpdb->get_row($prepared_sql, ARRAY_A); // Récupérer les résultats de la requête

        return $result;
    }
}
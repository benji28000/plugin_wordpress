<?php
require_once (__DIR__.'/../Model/login-model.php');

class Login_Controller {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function process_login() {
        unset($_SESSION["message"]);
        if (isset($_POST['login_submit'])) {
            $username = sanitize_text_field($_POST['username']);
            $password = sanitize_text_field($_POST['password']);

            // Effectuer des validations supplémentaires si nécessaire

            // Vérifier les informations d'identification
            $verif=$this->model->login_verif($username, $password);

            if($verif!=null){
                return["user" => $verif["username"],
                    "password" => $verif["password"]];

            }
            else{
                $message='<div class="error-message">Le nom d\'utilisateur ou le mot de passe sont incorrects</div>';
                $_SESSION["message"]=$message;
            }




        }
    }
}
?>
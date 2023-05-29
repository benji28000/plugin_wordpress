<?php

/**
 * Plugin Name: form_MVC
 * Description: Un système de formulaire codé en MVC
 * Version: 1.0.0
 * Author: Perez Benjamin
 * License: Benjamin
 */

function get_current_page_name() {
    $current_page = basename($_SERVER['REQUEST_URI']);
    return $current_page;
}

function my_plugin_init() {
    session_start();

    require_once(__DIR__.'/Controller/reservation-controller.php');
    require_once(__DIR__.'/View/reservation-view.php');
    require_once(__DIR__.'/View/login-view.php');
    require_once (__DIR__.'/Controller/login-controller.php');


    $page=get_current_page_name();
    $action = isset($_GET["action"]) ? $_GET["action"] : '';


    $model = new Reservation_Model();
    $controller = new Reservation_Controller($model);
    $view = new Reservation_View();

    // Ajoutez des hooks WordPress pour afficher le formulaire et traiter la soumission
    add_shortcode('reservation_form', array($view, 'display_reservation_form'));
    add_action('reservation', array($controller, 'process_reservation'));
    if($action == 'reservation'){
        do_action('reservation');
    }

    $model_login= new login_Model();
    $controller_login= new Login_Controller($model_login);
    $view_login= new Login_View();

    add_shortcode('login_form', array($view_login,'display_login_form'));
    add_action('login',array($controller_login,'process_login'));

    if($action=="login"){
        do_action('login');
        $login_result = $controller_login->process_login();

        if ($login_result && $login_result["user"]) {
            $user = $login_result["user"];
            session_start();
            $_SESSION["username"] = $user;
            wp_safe_redirect(home_url().'/merci/');
            exit();

        }


    }
    if ($page=="deconnexion"){
        session_destroy();
        wp_safe_redirect(home_url());
        exit();
    }

}

function display_username_shortcode() {

    if(isset($_SESSION["username"])){
        $username = $_SESSION["username"];


        return '<p>La connexion a bien été effectuée.</p> <p> utilisateur :  ' . $username . '</p>';
    }
    else {

        return '<p>Veuillez vous connecter pour afficher le nom d\'utilisateur.</p>';
    }

}
function register_username_shortcode() {
    add_shortcode('display_username', 'display_username_shortcode');
}

function css_form_reservation() {
    wp_enqueue_style( 'custom-form-style', plugin_dir_url( __FILE__ ) . 'styles/form_reservation.css', array(), '1.0', 'all' );
}


// Filtrer les éléments du menu
function custom_menu_items($items, $args) {
    // Vérifier si c'est le bon menu
    if ($args->theme_location == 'primary') {
        // Vérifier si l'utilisateur est connecté
        if (isset($_SESSION["username"])) {
            // Obtenir le nom d'utilisateur
            $username = $_SESSION["username"];

            $items .= "user : ".$username;
        }
    }

    return $items;
}


add_filter('wp_nav_menu_items', 'custom_menu_items', 10, 2);
add_action( 'wp_enqueue_scripts', 'css_form_reservation' );

add_action('init', 'register_username_shortcode');
add_action('plugins_loaded', 'my_plugin_init');
?>
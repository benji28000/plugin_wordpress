<?php

/*
Plugin Name: Testimonials Slider
Description: Affichez des témoignages clients sous forme de slider.
Version: 1.0
Author: Votre Nom
*/

// Créez une fonction pour enregistrer les témoignages dans une base de données
function testimonial_slider_save_testimonial($testimonial_data)
{
    global $wpdb; // Utilisation de la classe WordPress pour interagir avec la base de données

    $table_name = $wpdb->prefix . 'testimonials'; // Nom de la table dans la base de données

    // Insérez les données du témoignage dans la table
    $wpdb->insert(
        $table_name,
        array(
            'name' => $testimonial_data['name'],
            'email' => $testimonial_data['email'],
            'message' => $testimonial_data['message'],
            'date' => current_time('mysql')
        )
    );
}

// Créez une fonction pour afficher le slider sur le front-end à l'aide d'un shortcode
function testimonial_slider_display_slider($atts)
{
    ob_start(); // Démarrer la mise en tampon de sortie

    // Récupérez les témoignages de la base de données
    global $wpdb;
    $table_name = $wpdb->prefix . 'testimonials'; // Nom de la table dans la base de données

    $page = isset($_GET['avis_page']) ? intval($_GET['avis_page']) : 1; // Récupérer le numéro de la page

    // Paramètres de pagination
    $per_page = 5; // Nombre de témoignages par page
    $offset = ($page - 1) * $per_page; // Offset pour la requête SQL

    // Récupérer les témoignages avec la pagination
    $testimonials = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name ORDER BY date DESC LIMIT %d, %d",
            $offset,
            $per_page
        )
    );

    // Affichez le slider
    if (!empty($testimonials)) {
        echo '<div class="testimonial-slider">';
        foreach ($testimonials as $testimonial) {
            echo '<div class="testimonial">';
            echo '<h3>' . esc_html($testimonial->name) . '</h3>';
            echo '<p>' . esc_html($testimonial->message) . '</p>';
            echo '</div>';
        }
        echo '</div>';

        // Affichage de la pagination
        $total_testimonials = $wpdb->get_var("SELECT COUNT(*) FROM $table_name"); // Nombre total de témoignages
        $total_pages = ceil($total_testimonials / $per_page); // Nombre total de pages

        if ($total_pages > 1) {
            echo '<div class="pagination" style="display: flex;">';
            for ($i = 1; $i <= $total_pages; $i++) {
                $active_class = ($page == $i) ? ' active' : ''; // Ajoute la classe "active" à la page actuelle
                $page_url = add_query_arg('avis_page', $i); // Construit l'URL de la page
                echo '<a href="' . esc_url($page_url) . '" class="page-link' . $active_class . '" style="margin-right:10px; text-decoration: none;" >' . $i . '</a>';
            }
            echo '</div>';
        }
    } else {
        echo 'Aucun témoignage trouvé.';
    }

    return ob_get_clean(); // Retourne le contenu du tampon de sortie
}

// Créez une fonction pour ajouter une page d'administration dans le tableau de bord
function testimonial_slider_admin_page()
{
    add_menu_page(
        'Testimonials',
        'Testimonials',
        'manage_options',
        'testimonial-slider',
        'testimonial_slider_admin_page_callback'
    );
}

// Callback pour afficher la page d'administration
function testimonial_slider_admin_page_callback() {
    // Code pour afficher la page d'administration (à implémenter)
    echo '<div class="wrap">';
    echo '<h1>Page d\'administration des témoignages</h1>';

    // Vérifiez si des données ont été soumises via le formulaire
    if (isset($_POST['testimonial_submit'])) {
        // Récupérez les valeurs soumises
        $name = sanitize_text_field($_POST['testimonial_name']);
        $email = sanitize_email($_POST['testimonial_email']);
        $message = sanitize_textarea_field($_POST['testimonial_message']);

        // Validez les données (effectuez les vérifications nécessaires selon vos besoins)

        // Enregistrez le témoignage dans la base de données
        testimonial_slider_save_testimonial(array(
            'name' => $name,
            'email' => $email,
            'message' => $message
        ));

        echo '<div class="notice notice-success"><p>Témoignage enregistré avec succès.</p></div>';
    }

    // Affichez le formulaire d'ajout de témoignage
    echo '<form method="post">';
    echo '<label for="testimonial_name">Nom :</label><br>';
    echo '<input type="text" name="testimonial_name" id="testimonial_name" required><br><br>';
    echo '<label for="testimonial_email">Email :</label><br>';
    echo '<input type="email" name="testimonial_email" id="testimonial_email" required><br><br>';
    echo '<label for="testimonial_message">Message :</label><br>';
    echo '<textarea name="testimonial_message" id="testimonial_message" required></textarea><br><br>';
    echo '<input type="submit" name="testimonial_submit" value="Ajouter Témoignage" class="button button-primary">';
    echo '</form>';

    echo '</div>';
}

// Enregistrez les fonctions du plugin
add_shortcode('testimonial-slider', 'testimonial_slider_display_slider');
add_action('admin_menu', 'testimonial_slider_admin_page');
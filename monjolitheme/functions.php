<?php 
  function thememonsite_setup() {
    // Ajout du support pour les images mises en avant
    add_theme_support( 'post-thumbnails' );
    // Ajout du support pour le titre du site
    add_theme_support( 'title-tag' );
    // Ajout du support pour rendre le code valide en HTML 5
    add_theme_support( 
      'html5', 
      array( 
        'comment-list', 
        'comment-form', 
        'search-form', 
        'gallery', 
        'caption',
        'style',
        'script'
      )
    );
    // Ajout du support pour les menus
    register_nav_menus( 
      array(
        'main' => 'Menu Principal',
        'footer' => 'Menu footer'
      )
    );
  }
  add_action( 'after_setup_theme', 'thememonsite_setup' );

  function thememonsite_script() {
    // Ajout du fichier style.css
    wp_enqueue_style( 'style', get_stylesheet_uri() );
    // Ajout du fichier main.js
    wp_enqueue_script( 'main', get_template_directory_uri() . '/js/script.js', array(), '1.0.0', true );
  }
  add_action( 'wp_enqueue_scripts', 'thememonsite_script' );



// Enregistrer le type de post personnalisé "équipe" avec champs personnalisés.
function register_custom_post_types() { 
    register_post_type('equipe', array(
        'label' => 'Équipes',
        'labels' => array(
            'name' => 'Les équipes',
            'singular_name' => 'équipe',
            'add_new_item' => 'Ajouter une équipe',
            'edit_item' => 'Éditer une équipe',
            'new_item' => 'Nouvelle équipe',
            'view_item' => 'Voir la équipe',
            'search_items' => 'Rechercher parmi les équipes',
            'not_found' => 'Pas d\'équipe trouvée',
            'not_found_in_trash' => 'Pas d\'équipe dans la corbeille'
        ),
        'public' => true,
        'capability_type' => 'post',
        'supports' => array('title', 'editor', 'thumbnail'),
        'has_archive' => true,
    ));
}
add_action('init', 'register_custom_post_types');

    // Enregistrer le type de post "match"
    register_post_type('match', array(
        'label' => 'matchs',
        'labels' => array(
            'name' => 'Les matchs',
            'singular_name' => 'match',
            'add_new_item' => 'Ajouter un match',
            'edit_item' => 'Éditer un match',
            'new_item' => 'Nouveau match',
            'view_item' => 'Voir le match',
            'search_items' => 'Rechercher parmi les matchs',
            'not_found' => 'Pas de match trouvé',
            'not_found_in_trash' => 'Pas de match dans la corbeille'
        ),
        'public' => true,
        'capability_type' => 'post',
        'supports' => array('title', 'editor', 'thumbnail'),
        'has_archive' => true,
    ));

add_action('init', 'register_custom_post_types');


  register_taxonomy(
    'type',
    'aliment',
    array(
      'label' => 'Types',
      'labels' => array(
      'name' => 'Types',
      'singular_name' => 'Type',
      'all_items' => 'Tous les types',
      'edit_item' => 'Éditer le type',
      'view_item' => 'Voir le type',
      'update_item' => 'Mettre à jour le type',
      'add_new_item' => 'Ajouter un type',
      'new_item_name' => 'Nouveau type',
      'search_items' => 'Rechercher parmi les types',
      'popular_items' => 'Types les plus utilisés'
    ),
    'hierarchical' => true
    )
  );

  
function custom_registration_form() { 
    ob_start();
    if (is_user_logged_in()) {
        echo "<p>Vous êtes déjà inscrit et connecté.</p>";
        echo '<form action="' . wp_logout_url() . '" method="post"><p><input type="submit" value="Déconnexion"></p></form>';
    } else {
        ?>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" enctype="multipart/form-data">
            <p><label for="user_login">Nom d'utilisateur</label><input type="text" name="user_login" id="user_login" required></p>
            <p><label for="user_email">Email</label><input type="email" name="user_email" id="user_email" required></p>
            <p><label for="user_phone">Numéro de téléphone</label><input type="text" name="user_phone" id="user_phone" required></p>
            <p><label for="user_password">Mot de passe</label><input type="password" name="user_password" id="user_password" required></p>
            <p><label for="user_pseudo">Pseudo (pour les joueurs)</label><input type="text" name="user_pseudo" id="user_pseudo" required></p>

            <!-- Champ de sélection d'équipe -->
            <p><label for="user_team">Équipe</label>
            <select name="user_team" id="user_team">
                <?php
                $teams = get_posts(array('post_type' => 'equipe', 'numberposts' => -1));
                foreach ($teams as $team) {
                    echo '<option value="' . esc_attr($team->ID) . '">' . esc_html($team->post_title) . '</option>';
                }
                ?>
            </select></p>

            <p><input type="submit" value="Inscription"><input type="hidden" name="action" value="custom_registration"></p>
        </form>
        <?php
    }
    return ob_get_clean();
}
add_shortcode('custom_registration_form', 'custom_registration_form');

// Fonction de traitement de l'inscription
function handle_custom_registration() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['user_login']) && !empty($_POST['user_email']) && !empty($_POST['user_password'])) {
        $user_login = sanitize_user($_POST['user_login']);
        $user_email = sanitize_email($_POST['user_email']);
        $user_password = $_POST['user_password'];
        $user_phone = sanitize_text_field($_POST['user_phone']);
        $user_pseudo = sanitize_text_field($_POST['user_pseudo']);
        $user_team = sanitize_text_field($_POST['user_team']); // Récupérer l'équipe sélectionnée

        // Vérifier si le nom d'utilisateur ou l'email existe déjà
        if (!username_exists($user_login) && !email_exists($user_email)) {
            // Créer l'utilisateur
            $user_id = wp_create_user($user_login, $user_password, $user_email);
            if (!is_wp_error($user_id)) {
                // Mettre à jour les métadonnées utilisateur
                update_user_meta($user_id, 'phone_number', $user_phone);
                update_user_meta($user_id, 'pseudo', $user_pseudo);
                update_user_meta($user_id, 'team', $user_team); // Enregistrer l'équipe sélectionnée
                
                // Redirection après une inscription réussie
                wp_redirect(home_url('/connexion?inscription=success'));
                exit;
            } else {
                wp_redirect(home_url('/inscription?inscription=error'));
                exit;
            }
        } else {
            wp_redirect(home_url('/inscription?inscription=exists'));
            exit;
        }
    }
}
add_action('admin_post_nopriv_custom_registration', 'handle_custom_registration');
add_action('admin_post_custom_registration', 'handle_custom_registration');


function custom_login_form() {
    ob_start();
    if (is_user_logged_in()) {
        // Afficher un message de bienvenue et un bouton de déconnexion
        $current_user = wp_get_current_user();
        ?>
        <p>Bonjour, <?php echo esc_html($current_user->display_name); ?>. Vous êtes déjà connecté.</p>
        <form action="<?php echo wp_logout_url(); ?>" method="post">
            <p><input type="submit" value="Déconnexion"></p>
        </form>
        <?php
    } else {
        // Afficher le formulaire de connexion si l'utilisateur n'est pas connecté
        ?>
        <form action="<?php echo wp_login_url(); ?>" method="post">
            <p><label for="username">Nom d'utilisateur</label><input type="text" name="log" id="username" required></p>
            <p><label for="password">Mot de passe</label><input type="password" name="pwd" id="password" required></p>
            <p><input type="submit" value="Connexion"><input type="hidden" name="redirect_to" value="<?php echo home_url(); ?>"></p>
        </form>
        <?php
    }
    return ob_get_clean();
}
add_shortcode('custom_login_form', 'custom_login_form');

function custom_login_redirect($redirect_to, $request, $user) {
    if (is_wp_error($user)) {
        return home_url('/connexion?erreur=1');
    } else {
        return home_url();
    }
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);

if (isset($_GET['inscription'])) {
    if ($_GET['inscription'] == 'success') {
        echo "<p>Inscription réussie. Vous pouvez maintenant vous connecter.</p>";
    } elseif ($_GET['inscription'] == 'exists') {
        echo "<p>Le nom d'utilisateur ou l'email existe déjà.</p>";
    } elseif ($_GET['inscription'] == 'error') {
        echo "<p>Erreur lors de l'inscription. Veuillez réessayer.</p>";
    }
}

function display_user_info() {
    // Vérifier si l'utilisateur est connecté
    if (is_user_logged_in()) {
        // Récupérer l'utilisateur actuel
        $current_user = wp_get_current_user();

        // Récupérer les informations utilisateur
        $username = $current_user->user_login; // Nom d'utilisateur
        $email = $current_user->user_email; // Email
        $first_name = $current_user->user_firstname; // Prénom
        $last_name = $current_user->user_lastname; // Nom
        $phone = get_user_meta($current_user->ID, 'phone_number', true); // Téléphone
        $pseudo = get_user_meta($current_user->ID, 'pseudo', true); // Pseudo
        $profile_picture_url = get_avatar_url($current_user->ID); // Photo de profil
        
        // Récupérer l'ID de l'équipe associée à l'utilisateur
        $user_team_id = get_user_meta($current_user->ID, 'team', true);

        // Récupérer le nom de l'équipe associée
        $team_name = '';
        if ($user_team_id) {
            $team_post = get_post($user_team_id);
            if ($team_post) {
                $team_name = $team_post->post_title;
            }
        }

        // Récupérer les joueurs de l'équipe
        $args_joueurs = array(
            'meta_key'   => 'team',
            'meta_value' => $user_team_id,
            'number'     => -1,
        );
        $joueurs = get_users($args_joueurs);

        // Retourner les informations sous forme de HTML
        ob_start();
        ?>
        <div class="user-info">
            <div><strong>Nom d'utilisateur :</strong> <?php echo esc_html($username); ?></div>
            <div><strong>Pseudo :</strong> <?php echo esc_html($pseudo); ?></div>
            <div><strong>Email :</strong> <?php echo esc_html($email); ?></div>
            <div><strong>Téléphone :</strong> <?php echo esc_html($phone); ?></div>
            <div><strong>Équipe :</strong> <?php echo esc_html($team_name ? $team_name : 'Aucune équipe associée'); ?></div>
            
            <?php if (!empty($user_team_id)): ?>
                <div><strong>Joueurs de l'équipe :</strong></div>
                <ul>
                    <?php
                    if (!empty($joueurs)) {
                        foreach ($joueurs as $joueur) {
                            echo '<li>' . esc_html($joueur->display_name) . ' (Pseudo: ' . esc_html(get_user_meta($joueur->ID, 'pseudo', true)) . ')</li>';
                        }
                    } else {
                        echo '<li>Aucun joueur dans l\'équipe.</li>';
                    }
                    ?>
                </ul>
            <?php endif; ?>

            <?php if ($profile_picture_url): ?>
                <div><strong>Photo de profil :</strong><br>
                    <img src="<?php echo esc_url($profile_picture_url); ?>" alt="Photo de profil" />
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    } else {
        return '<p>Vous devez être connecté pour voir vos informations.</p>';
    }
}
add_shortcode('user_info', 'display_user_info');



function custom_profile_edit_form() {
    if (!is_user_logged_in()) {
        return '<p>Vous devez être connecté pour modifier votre profil.</p>';
    }

    $current_user = wp_get_current_user();
    if ( !$current_user->ID ) {
        return '<p>Utilisateur non trouvé.</p>';  // Si l'utilisateur n'est pas correctement récupéré
    }

    $user_phone = get_user_meta($current_user->ID, 'phone_number', true);
    $user_pseudo = get_user_meta($current_user->ID, 'pseudo', true);
    $user_team = get_user_meta($current_user->ID, 'team', true);

    ob_start();
    ?>
    <form action="" method="post">
        <h3>Modifier vos informations de profil</h3>
        <p>
            <label for="user_pseudo">Pseudo (pour les joueurs)</label>
            <input type="text" name="user_pseudo" id="user_pseudo" value="<?php echo esc_attr($user_pseudo); ?>" required>
        </p>
        <p>
            <label for="user_email">Email</label>
            <input type="email" name="user_email" id="user_email" value="<?php echo esc_attr($current_user->user_email); ?>" required>
        </p>
        <p>
            <label for="phone_number">Numéro de téléphone</label>
            <input type="tel" name="phone_number" id="phone_number" value="<?php echo esc_attr($user_phone); ?>" required>
        </p>
        <p>
            <label for="user_team">Équipe</label>
            <select name="user_team" id="user_team">
                <?php
                $teams = get_posts(array('post_type' => 'equipe', 'numberposts' => -1));
                foreach ($teams as $team) {
                    $selected = $team->ID == $user_team ? 'selected' : '';
                    echo '<option value="' . esc_attr($team->ID) . '" ' . $selected . '>' . esc_html($team->post_title) . '</option>';
                }
                ?>
            </select>
        </p>
        <p>
            <input type="submit" name="submit_profile_update" value="Mettre à jour">
        </p>
    </form>
    <?php

    // Vérifier si le formulaire a été soumis
    if (isset($_POST['submit_profile_update'])) {
        // Sécuriser les données reçues
        $user_email = sanitize_email($_POST['user_email']);
        $phone_number = sanitize_text_field($_POST['phone_number']);
        $user_pseudo = sanitize_text_field($_POST['user_pseudo']);
        $user_team = sanitize_text_field($_POST['user_team']);

        // Récupérer l'ID de l'utilisateur actuel
        $user_id = $current_user->ID;

        // Vérifier si $user_id est défini
        if (!$user_id) {
            echo '<p>Une erreur est survenue. Veuillez réessayer.</p>';
            return;
        }

        // Mise à jour des informations de l'utilisateur
        wp_update_user([
            'ID' => $user_id,
            'user_email' => $user_email
        ]);

        // Mise à jour des métadonnées utilisateur
        update_user_meta($user_id, 'phone_number', $phone_number);
        update_user_meta($user_id, 'pseudo', $user_pseudo);
        update_user_meta($user_id, 'team', $user_team);

        // Si un mot de passe est spécifié, on le met à jour
        if (!empty($user_password)) {
            wp_set_password($user_password, $user_id);
        }

        // Message de confirmation et redirection
        echo '<p>Vos informations ont été mises à jour avec succès !</p>';

        // Rediriger vers la page du profil après la mise à jour
        wp_redirect(home_url('/profil')); 
        exit;
    }

    return ob_get_clean();
}
add_shortcode('custom_profile_edit_form', 'custom_profile_edit_form');




// Fonction pour afficher le formulaire de création d'équipe
function equipe_creation_form() {
    ob_start();
    ?>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" enctype="multipart/form-data">
        <p>
            <label for="equipe_name">Nom de l'équipe</label>
            <input type="text" name="equipe_name" id="equipe_name" required>
        </p>
        <p>
            <label for="equipe_description">Description de l'équipe</label>
            <textarea name="equipe_description" id="equipe_description" required></textarea>
        </p>
        <p>
            <label for="equipe_image">Image de l'équipe</label>
            <input type="file" name="equipe_image" id="equipe_image" accept="image/*" required>
        </p>
        <p>
            <label for="equipe_phone">Numéro de téléphone</label>
            <input type="tel" name="equipe_phone" id="equipe_phone" required>
        </p>
        <p>
            <label for="equipe_email">Email de l'équipe</label>
            <input type="email" name="equipe_email" id="equipe_email" required>
        </p>
        <input type="hidden" name="action" value="create_equipe">
        <p>
            <input type="submit" value="Créer l'équipe">
        </p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('equipe_creation_form', 'equipe_creation_form');

// Gestion de la soumission du formulaire
function handle_equipe_creation() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['equipe_name']) && !empty($_POST['equipe_description'])) {
        $equipe_name = sanitize_text_field($_POST['equipe_name']);
        $equipe_description = sanitize_textarea_field($_POST['equipe_description']);
        $equipe_phone = sanitize_text_field($_POST['equipe_phone']);
        $equipe_email = sanitize_email($_POST['equipe_email']);

        // Créer un nouveau post de type "équipe"
        $new_equipe = array(
            'post_title'   => $equipe_name,
            'post_content' => $equipe_description,
            'post_type'    => 'equipe',
            'post_status'  => 'publish'
        );
        $equipe_id = wp_insert_post($new_equipe);

        if (!is_wp_error($equipe_id)) {
            // Ajouter le numéro de téléphone et l'email en tant que métadonnées
            update_post_meta($equipe_id, 'equipe_phone', $equipe_phone);
            update_post_meta($equipe_id, 'equipe_email', $equipe_email);

            // Gestion de l'image téléchargée
            if (!empty($_FILES['equipe_image']['name'])) {
                $uploaded_file = $_FILES['equipe_image'];
                $upload_overrides = array('test_form' => false);
                $movefile = wp_handle_upload($uploaded_file, $upload_overrides);

                if ($movefile && !isset($movefile['error'])) {
                    // Ajouter l'image à la médiathèque
                    $attachment = array(
                        'guid'           => $movefile['url'],
                        'post_mime_type' => $movefile['type'],
                        'post_title'     => sanitize_file_name($uploaded_file['name']),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    );

                    $attachment_id = wp_insert_attachment($attachment, $movefile['file'], $equipe_id);

                    // Générez les métadonnées de l'image
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    $attach_data = wp_generate_attachment_metadata($attachment_id, $movefile['file']);
                    wp_update_attachment_metadata($attachment_id, $attach_data);

                    // Définir l'image de l'équipe
                    set_post_thumbnail($equipe_id, $attachment_id);
                }
            }

            wp_redirect(home_url('/equipe?creation=success'));
            exit;
        } else {
            wp_redirect(home_url('/equipe?creation=error'));
            exit;
        }
    }
}
add_action('admin_post_nopriv_create_equipe', 'handle_equipe_creation');
add_action('admin_post_create_equipe', 'handle_equipe_creation');

// Fonction pour afficher toutes les équipes avec leurs informations
function afficher_toutes_les_equipes() {
    // Arguments pour la requête de récupération des posts "équipe"
    $args = array(
        'post_type'      => 'equipe',
        'posts_per_page' => -1, // Récupérer toutes les équipes
        'post_status'    => 'publish',
    );

    // Requête pour récupérer les équipes
    $equipes = new WP_Query($args);

    // Vérifier s'il y a des équipes
    if ($equipes->have_posts()) {
        ob_start(); // Démarrer la mise en tampon pour capturer la sortie HTML
        echo '<div class="liste-equipes">';

        // Boucle pour chaque équipe
        while ($equipes->have_posts()) {
            $equipes->the_post(); // Configurer les données pour le post courant
            
            // Récupérer les informations d'équipe
            $equipe_nom = get_the_title();
            $equipe_image = get_the_post_thumbnail_url(get_the_ID(), 'medium'); // URL de l'image de l'équipe
            $equipe_description = get_the_content();
            $equipe_phone = get_post_meta(get_the_ID(), 'equipe_phone', true);
            $equipe_email = get_post_meta(get_the_ID(), 'equipe_email', true);

            // Afficher les informations de l'équipe
            echo '<div class="equipe">';
            if ($equipe_image) {
                echo '<img src="' . esc_url($equipe_image) . '" alt="' . esc_attr($equipe_nom) . '">';
            }
            echo '<div class="equipe-content">';
            echo '<h2>' . esc_html($equipe_nom) . '</h2>';

            // Récupérer les joueurs associés à cette équipe (récupérer les utilisateurs avec le champ 'team')
            $args_joueurs = array(
                'meta_key'   => 'team', // Le champ qui associe l'utilisateur à une équipe
                'meta_value' => get_the_ID(), // ID de l'équipe actuelle
                'number'     => -1, // Pas de limite au nombre de joueurs
            );
            $joueurs = get_users($args_joueurs);

            if (!empty($joueurs)) {
                $noms_joueurs = array_map(function($joueur) {
                    return $joueur->display_name;
                }, $joueurs);
                echo '<p>' . esc_html(implode(', ', $noms_joueurs)) . '</p>';
            } else {
                echo '<p>Aucun joueur associé.</p>';
            }

            echo '</div>'; // Fin de l'équipe-content
            echo '</div>'; // Fin de l'équipe
        }

        echo '</div>'; // Fin de la liste des équipes
        wp_reset_postdata(); // Réinitialiser les données de la requête

        return ob_get_clean(); // Retourner le contenu mis en tampon
    } else {
        return '<p>Aucune équipe trouvée.</p>';
    }
}
add_shortcode('afficher_equipes', 'afficher_toutes_les_equipes');





// Fonction pour afficher le formulaire de création de match
function match_creation_form() {
    // Vérifier si l'utilisateur est connecté et s'il a le rôle d'administrateur
    if (!current_user_can('administrator')) {
        return '<p>Vous n\'avez pas la permission d\'accéder à cette page.</p>';
    }

    // Récupérer toutes les équipes pour le sélecteur
    $equipes = get_posts(array('post_type' => 'equipe', 'numberposts' => -1));
    $message = '';

    // Afficher un message si le match a été créé
    if (isset($_POST['submit_match_creation'])) {
        $message = handle_match_creation();
    }

    ob_start();
    if ($message) {
        echo '<p>' . esc_html($message) . '</p>';
    }
    ?>
    <form action="" method="post">
        <p>
            <label for="match_date">Date du match:</label>
            <input type="date" id="match_date" name="match_date" required>
        </p>
        <p>
            <label for="match_heure">Heure du match:</label>
            <input type="time" id="match_heure" name="match_heure" required>
        </p>
        <p>
            <label for="match_equipe_1">Équipe 1:</label>
            <select id="match_equipe_1" name="match_equipe_1" required>
                <?php foreach ($equipes as $equipe) : ?>
                    <option value="<?php echo esc_attr($equipe->ID); ?>">
                        <?php echo esc_html($equipe->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="match_equipe_2">Équipe 2:</label>
            <select id="match_equipe_2" name="match_equipe_2" required>
                <?php foreach ($equipes as $equipe) : ?>
                    <option value="<?php echo esc_attr($equipe->ID); ?>">
                        <?php echo esc_html($equipe->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="match_score_equipe_1">Score Équipe 1:</label>
            <input type="number" id="match_score_equipe_1" name="match_score_equipe_1" required>
        </p>
        <p>
            <label for="match_score_equipe_2">Score Équipe 2:</label>
            <input type="number" id="match_score_equipe_2" name="match_score_equipe_2" required>
        </p>
        <input type="hidden" name="submit_match_creation" value="1">
        <p>
            <input type="submit" value="Créer le match">
        </p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('match_creation_form', 'match_creation_form');

// Gestion de la soumission du formulaire de création de match
function handle_match_creation() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['match_date']) && !empty($_POST['match_heure'])) {
        $date = sanitize_text_field($_POST['match_date']);
        $heure = sanitize_text_field($_POST['match_heure']);
        $equipe_1 = sanitize_text_field($_POST['match_equipe_1']);
        $equipe_2 = sanitize_text_field($_POST['match_equipe_2']);
        $score_equipe_1 = intval($_POST['match_score_equipe_1']);
        $score_equipe_2 = intval($_POST['match_score_equipe_2']);

        // Créer un nouveau post de type "match"
        $new_match = array(
            'post_title'   => 'Match: ' . get_the_title($equipe_1) . ' vs ' . get_the_title($equipe_2),
            'post_type'    => 'match',
            'post_status'  => 'publish',
        );
        $match_id = wp_insert_post($new_match);

        if (!is_wp_error($match_id)) {
            // Ajouter les champs personnalisés
            update_post_meta($match_id, 'match_date', $date);
            update_post_meta($match_id, 'match_heure', $heure);
            update_post_meta($match_id, 'match_equipe_1', $equipe_1);
            update_post_meta($match_id, 'match_equipe_2', $equipe_2);
            update_post_meta($match_id, 'match_score_equipe_1', $score_equipe_1);
            update_post_meta($match_id, 'match_score_equipe_2', $score_equipe_2);

            return 'Le match a été créé avec succès !';
        } else {
            return 'Erreur lors de la création du match. Veuillez réessayer.';
        }
    }
    return '';
}


// Fonction pour afficher tous les matchs avec les photos des équipes
// Fonction pour afficher tous les matchs avec les fiches de match
function afficher_tous_les_matchs() {
    $args = array(
        'post_type'      => 'match',
        'posts_per_page' => -1, // Récupérer tous les matchs
        'post_status'    => 'publish',
    );

    $matchs = new WP_Query($args);

    if ($matchs->have_posts()) {
        ob_start();
        echo '<div class="liste-matchs">';

        while ($matchs->have_posts()) {
            $matchs->the_post();
            $date = get_post_meta(get_the_ID(), 'match_date', true);
            $heure = get_post_meta(get_the_ID(), 'match_heure', true);
            $equipe_1_id = get_post_meta(get_the_ID(), 'match_equipe_1', true);
            $equipe_2_id = get_post_meta(get_the_ID(), 'match_equipe_2', true);
            $score_equipe_1 = get_post_meta(get_the_ID(), 'match_score_equipe_1', true);
            $score_equipe_2 = get_post_meta(get_the_ID(), 'match_score_equipe_2', true);

            $equipe_1_nom = get_the_title($equipe_1_id);
            $equipe_2_nom = get_the_title($equipe_2_id);
            $equipe_1_image = get_the_post_thumbnail_url($equipe_1_id, 'thumbnail');
            $equipe_2_image = get_the_post_thumbnail_url($equipe_2_id, 'thumbnail');

            echo '<div class="match">';
            echo '<h2>' . get_the_title() . '</h2>';
            echo '<p>Date: ' . esc_html($date) . '</p>';
            echo '<p>Heure: ' . esc_html($heure) . '</p>';

            // Afficher les images des équipes
            echo '<div class="equipes">';
            if ($equipe_1_image) {
                echo '<div class="equipe">';
                echo '<img src="' . esc_url($equipe_1_image) . '" alt="' . esc_attr($equipe_1_nom) . '">';
                echo '<p>' . esc_html($equipe_1_nom) . '</p>';
                echo '</div>';
            }
            if ($equipe_2_image) {
                echo '<div class="equipe">';
                echo '<img src="' . esc_url($equipe_2_image) . '" alt="' . esc_attr($equipe_2_nom) . '">';
                echo '<p>' . esc_html($equipe_2_nom) . '</p>';
                echo '</div>';
            }
            echo '</div>'; // Fin du div des équipes

            echo '<p class="match-score">Score: ' . esc_html($score_equipe_1) . ' - ' . esc_html($score_equipe_2) . '</p>';
            echo '</div>'; // Fin du div du match
        }

        echo '</div>'; // Fin du div de la liste des matchs
        wp_reset_postdata();
        return ob_get_clean();
    } else {
        return '<p>Aucun match trouvé.</p>';
    }
}
add_shortcode('afficher_matchs', 'afficher_tous_les_matchs');






 /* register_taxonomy(
    'type',
    'aliment',
    array(
      'label' => 'Types',
      'labels' => array(
      'name' => 'Types',
      'singular_name' => 'Type',
      'all_items' => 'Tous les types',
      'edit_item' => 'Éditer le type',
      'view_item' => 'Voir le type',
      'update_item' => 'Mettre à jour le type',
      'add_new_item' => 'Ajouter un type',
      'new_item_name' => 'Nouveau type',
      'search_items' => 'Rechercher parmi les types',
      'popular_items' => 'Types les plus utilisés'
    ),
    'hierarchical' => true
    )
  );
  register_taxonomy(
    'couleur',
    'aliment',
    array(
      'label' => 'Couleurs',
      'labels' => array(
      'name' => 'Couleurs',
      'singular_name' => 'Couleur',
      'all_items' => 'Toutes les couleurs',
      'edit_item' => 'Éditer la couleur',
      'view_item' => 'Voir la couleur',
      'update_item' => 'Mettre à jour la couleur',
      'add_new_item' => 'Ajouter une couleur',
      'new_item_name' => 'Nouvelle couleur',
      'search_items' => 'Rechercher parmi les couleurs',
      'popular_items' => 'Couleurs les plus utilisées'
    ),
    'hierarchical' => false
    )
  );
  register_taxonomy_for_object_type( 'type', 'aliment' );
  register_taxonomy_for_object_type( 'couleur', 'aliment' ); */


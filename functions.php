<?php
/**
 * IKAPSI UNDIP functions and definitions
 */

if ( ! function_exists( 'ikapsi_undip_setup' ) ) {
    function ikapsi_undip_setup() {
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        register_nav_menus( array(
            'primary' => __( 'Primary Menu', 'ikapsi-undip' ),
        ) );
    }
}
add_action( 'after_setup_theme', 'ikapsi_undip_setup' );

function ikapsi_undip_scripts() {
    wp_enqueue_style( 'ikapsi-undip-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version') );
    
    // Memastikan skrip balasan (reply) komentar aktif agar form bisa berpindah
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'ikapsi_undip_scripts' );

// 1. Daftarkan Custom Post Type 'alumni_project'
function register_alumni_project_cpt() {
    $labels = array(
        'name'               => 'Alumni Projects',
        'singular_name'      => 'Alumni Project',
        'menu_name'          => 'Alumni Projects',
        'add_new'            => 'Tambah Project Baru',
        'add_new_item'       => 'Tambah Project Alumni',
        'edit_item'          => 'Edit Project',
        'new_item'           => 'Project Baru',
        'view_item'          => 'Lihat Project',
        'search_items'       => 'Cari Project',
        'not_found'          => 'Project tidak ditemukan',
        'not_found_in_trash' => 'Project tidak ditemukan di tempat sampah',
    );
    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'project-alumni'), 
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-portfolio',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
    );
    register_post_type('alumni_project', $args);
}
add_action('init', 'register_alumni_project_cpt');

// 2. Daftarkan Custom Taxonomy 'project_category'
function register_project_taxonomy() {
    $labels = array(
        'name'              => 'Kategori Project',
        'singular_name'     => 'Kategori Project',
        'search_items'      => 'Cari Kategori',
        'all_items'         => 'Semua Kategori',
        'edit_item'         => 'Edit Kategori',
        'update_item'       => 'Update Kategori',
        'add_new_item'      => 'Tambah Kategori Baru',
        'new_item_name'     => 'Nama Kategori Baru',
        'menu_name'         => 'Kategori Project',
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'project-category'),
    );
    register_taxonomy('project_category', array('alumni_project'), $args);
}
add_action('init', 'register_project_taxonomy');

// 3. Fungsi AJAX untuk Filter dan Pencarian Project
function filter_projects_ajax() {
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'all';
    $search   = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

    $args = array(
        'post_type'      => 'alumni_project',
        'posts_per_page' => -1, 
        'post_status'    => 'publish'
    );

    if ($category !== 'all') {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'project_category',
                'field'    => 'slug',
                'terms'    => $category
            )
        );
    }

    if (!empty($search)) {
        $args['s'] = $search;
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            
            $lokasi       = get_field('lokasi');
            $nama_alumni  = get_field('nama_alumni');
            $angkatan     = get_field('angkatan');
            $foto_alumni  = get_field('foto_alumni');
            $link_project = get_field('link_project');
            
            $terms = get_the_terms(get_the_ID(), 'project_category');
            $badge = $terms && !is_wp_error($terms) ? $terms[0]->name : 'Uncategorized';
            
            $foto_src = $foto_alumni ? esc_url($foto_alumni) : esc_url(get_template_directory_uri() . '/images/default-avatar.png');
            $link_url = $link_project ? esc_url($link_project) : get_the_permalink();
            ?>
            
            <div class="project-card">
                <div class="card-img-wrapper">
                    <div class="card-badge"><?php echo esc_html($badge); ?></div>
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium', array('style' => 'width: 100%; height: 100%; object-fit: cover;')); ?>
                    <?php else: ?>
                        <div style="width: 100%; height: 100%; background: #eaeaea; display: flex; align-items: center; justify-content: center; color: #999;">No Image</div>
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <div class="card-header">
                        <h3 class="card-title"><?php the_title(); ?></h3>
                        <span class="card-location"><?php echo esc_html($lokasi); ?></span>
                    </div>
                    <p class="card-desc"><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p>
                    <div class="card-footer">
                        <div class="alumni-info">
                            <img src="<?php echo $foto_src; ?>" class="alumni-avatar" alt="Avatar">
                            <span class="alumni-name"><?php echo esc_html($nama_alumni . ' (Psi \'' . $angkatan . ')'); ?></span>
                        </div>
                        <a href="<?php echo $link_url; ?>" class="card-link" target="_blank">Kenali Lebih Jauh →</a>
                    </div>
                </div>
            </div>

            <?php
        endwhile;
        wp_reset_postdata();
    else :
        echo '<div style="width: 100%; text-align: center; padding: 50px 0; color: #999; grid-column: 1 / -1;">Project tidak ditemukan.</div>';
    endif;

    die();
}
add_action('wp_ajax_filter_projects', 'filter_projects_ajax');
add_action('wp_ajax_nopriv_filter_projects', 'filter_projects_ajax');

// 4. Daftarkan Custom Post Type 'agenda_event'
function register_agenda_event_cpt() {
    $labels = array(
        'name'               => 'Agenda Events',
        'singular_name'      => 'Agenda Event',
        'menu_name'          => 'Agenda Events',
        'add_new'            => 'Tambah Event Baru',
        'add_new_item'       => 'Tambah Event',
        'edit_item'          => 'Edit Event',
        'new_item'           => 'Event Baru',
        'view_item'          => 'Lihat Event',
        'search_items'       => 'Cari Event',
        'not_found'          => 'Event tidak ditemukan',
        'not_found_in_trash' => 'Event tidak ditemukan di tempat sampah',
    );
    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'agenda-event'),
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 6, 
        'menu_icon'           => 'dashicons-calendar-alt', 
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
    );
    register_post_type('agenda_event', $args);
}
add_action('init', 'register_agenda_event_cpt');

// 5. Fungsi AJAX untuk Filter dan Pencarian Artikel/Berita
function filter_artikel_ajax() {
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'all';
    $search   = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

    $args = array(
        'post_type'      => 'post', 
        'posts_per_page' => -1,    
        'post_status'    => 'publish'
    );

    if ($category !== 'all') {
        $args['category_name'] = $category; 
    }

    if (!empty($search)) {
        $args['s'] = $search;
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            ?>
            <div class="artikel-card">
                <div class="card-img-wrapper">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium', array('style' => 'width: 100%; height: 100%; object-fit: cover;')); ?>
                    <?php else: ?>
                        <div style="width: 100%; height: 100%; background: #eaeaea; display: flex; align-items: center; justify-content: center; color: #999;">No Image</div>
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <span class="card-date"><?php echo get_the_date('d F Y'); ?></span>
                    <h3 class="card-title"><?php the_title(); ?></h3>
                    <p class="card-desc"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                    <div class="card-footer">
                        <a href="<?php the_permalink(); ?>" class="card-link">Baca Selengkapnya →</a>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
        wp_reset_postdata();
    else :
        echo '<div style="width: 100%; text-align: center; padding: 50px 0; color: #999; grid-column: 1 / -1;">Artikel tidak ditemukan.</div>';
    endif;

    die();
}
add_action('wp_ajax_filter_artikel', 'filter_artikel_ajax');
add_action('wp_ajax_nopriv_filter_artikel', 'filter_artikel_ajax');

/**
 * Logika Keamanan Halaman Login Custom & Redirect
 */

// 1. Alihkan wp-login.php ke halaman login buatan kita (Dengan Pengecualian Admin)
add_action('init', 'redirect_login_page');
function redirect_login_page() {
    $login_page  = home_url( '/login/' );
    $page_viewed = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    if ( $page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET' ) {
        
        if ( isset($_GET['redirect_to']) && strpos($_GET['redirect_to'], 'wp-admin') !== false ) {
            return; 
        }

        if ( isset($_GET['admin_bypass']) && $_GET['admin_bypass'] == 'true' ) {
            return; 
        }

        if ( isset($_GET['action']) && in_array($_GET['action'], array('logout', 'lostpassword', 'rp', 'resetpass')) ) {
            return;
        }

        wp_redirect($login_page);
        exit;
    }
}

// 2. Handle jika login gagal agar tetap di halaman custom (bukan dilempar ke wp-login.php)
add_action( 'wp_login_failed', 'login_failed_redirect' );
function login_failed_redirect( $username ) {
    $referrer = wp_get_referer();
    if ( $referrer && strpos( $referrer, 'wp-login.php' ) !== false && strpos( $referrer, 'redirect_to' ) !== false ) {
        return;
    }

    $login_page  = home_url( '/login/' );
    wp_redirect( $login_page . '?login=failed' );
    exit;
}

// 3. Handle jika form kosong saat disubmit
add_action( 'authenticate', 'verify_username_password', 1, 3);
function verify_username_password( $user, $username, $password ) {
    $login_page  = home_url( '/login/' );
    
    $referrer = wp_get_referer();
    if ( $referrer && strpos( $referrer, 'wp-login.php' ) !== false ) {
        return $user;
    }

    if ( $username == "" || $password == "" ) {
        wp_redirect( $login_page . '?login=failed' );
        exit;
    }
    
    return $user;
}

// 4. Arahkan ke Beranda setelah login berhasil (Semua User termasuk Admin)
add_filter( 'login_redirect', 'ikapsi_after_login_redirect', 999, 3 );
function ikapsi_after_login_redirect( $redirect_to, $request, $user ) {
    if ( ! is_a( $user, 'WP_User' ) ) {
        return $redirect_to;
    }

    if ( ! empty( $request ) && strpos( $request, 'go_to_member' ) !== false ) {
        return $request;
    }

    return home_url( '/' );
}


// ==============================================================================
// IKAPSI SSO TRIGGER KE LARAVEL & SHORTCODE BUTTON
// ==============================================================================

// A. Logika Redirect SSO
add_action('init', 'ikapsi_sso_to_laravel');
function ikapsi_sso_to_laravel() {
    if (isset($_GET['go_to_member']) && is_user_logged_in()) {
        
        $current_user = wp_get_current_user();
        $token = bin2hex(random_bytes(32));
        
        update_user_meta($current_user->ID, 'sso_laravel_token', $token);
        update_user_meta($current_user->ID, 'sso_laravel_expiry', time() + 60);
        
        $laravel_url = defined('LARAVEL_SSO_URL') ? LARAVEL_SSO_URL : home_url('/sso-login');
        
        $redirect_url = add_query_arg([
            'uid'   => $current_user->ID,
            'email' => urlencode($current_user->user_email),
            'name'  => urlencode($current_user->display_name),
            'token' => $token
        ], $laravel_url);
        
        wp_redirect($redirect_url);
        exit;
    }
}

// B. Shortcode [tombol_member]
add_shortcode('tombol_member', 'ikapsi_sso_button_shortcode');
function ikapsi_sso_button_shortcode($atts) {
    $link = home_url('/?go_to_member=1');
    $label = is_user_logged_in() ? 'Masuk Member Area' : 'Login Alumni';
    
    if (!is_user_logged_in()) {
        $link = wp_login_url(home_url('/?go_to_member=1'));
    }

    return '<a href="' . esc_url($link) . '" class="btn-member-area" style="background-color: #D74690; color: #ffffff !important; padding: 12px 25px; border-radius: 8px; font-weight: bold; text-decoration: none; display: inline-block; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">' . esc_html($label) . '</a>';
}

// C. Logika Single Log Out (SLO) dari Laravel ke WordPress
add_action('init', 'ikapsi_sso_logout_handler');
function ikapsi_sso_logout_handler() {
    if (isset($_GET['sso_action']) && $_GET['sso_action'] === 'logout') {
        
        // Nonaktifkan Hook WP->Laravel agar tidak terjadi "Infinite Loop"
        remove_action('wp_logout', 'ikapsi_sso_logout_to_laravel');
        
        wp_logout();
        
        wp_redirect(home_url('/'));
        exit;
    }
}

// D. Logika Single Log Out (SLO) dari WordPress ke Laravel
add_action('wp_logout', 'ikapsi_sso_logout_to_laravel');
function ikapsi_sso_logout_to_laravel() {
    $laravel_slo_url = defined('LARAVEL_SLO_URL') ? LARAVEL_SLO_URL : home_url('/sso-logout');
    wp_redirect($laravel_slo_url);
    exit;
}

/**
 * =========================================================================
 * REGISTRASI CUSTOM ROLE IKAPSI
 * =========================================================================
 */
function ikapsi_register_custom_roles() {
    add_role(
        'moderator',
        'Moderator',
        array(
            'read'                   => true,
            'edit_posts'             => true,
            'edit_others_posts'      => true,
            'edit_published_posts'   => true,
            'publish_posts'          => true,
            'upload_files'           => true,
        )
    );

    add_role(
        'member',
        'Member',
        array(
            'read'         => true,
            'edit_posts'   => true, 
            'upload_files' => true, 
        )
    );
}
add_action( 'init', 'ikapsi_register_custom_roles' );

// =========================================================================
// TAHAP 1: REGISTRASI CPT 'ALUMNI CORNER' & META BOX
// =========================================================================
function register_alumni_corner_cpt() {
    $labels = array(
        'name'               => 'Alumni Corner',
        'singular_name'      => 'Post Alumni',
        'menu_name'          => 'Alumni Corner',
        'add_new'            => 'Tambah Post Baru',
        'add_new_item'       => 'Tambah Post Alumni',
        'edit_item'          => 'Edit Post',
        'new_item'           => 'Post Baru',
        'view_item'          => 'Lihat Post',
        'search_items'       => 'Cari Post',
        'not_found'          => 'Post tidak ditemukan',
        'not_found_in_trash' => 'Post tidak ditemukan di tempat sampah',
    );
    
    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'alumni-corner'),
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 7, 
        'menu_icon'           => 'dashicons-groups', 
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'), 
    );
    
    register_post_type('alumni_corner', $args);
}
add_action('init', 'register_alumni_corner_cpt');

function add_alumni_corner_meta_boxes() {
    add_meta_box(
        'alumni_corner_foto_meta',       
        'Foto Pendukung (Opsional)',     
        'render_alumni_corner_foto_meta',
        'alumni_corner',                 
        'normal',                        
        'default'
    );
}
add_action('add_meta_boxes', 'add_alumni_corner_meta_boxes');

function render_alumni_corner_foto_meta($post) {
    wp_nonce_field('save_alumni_corner_foto', 'alumni_corner_foto_nonce');

    $foto_1 = get_post_meta($post->ID, 'foto_pendukung_1', true);
    $foto_2 = get_post_meta($post->ID, 'foto_pendukung_2', true);
    $foto_3 = get_post_meta($post->ID, 'foto_pendukung_3', true);

    echo '<style>
        .foto-pendukung-wrapper { margin-bottom: 20px; }
        .foto-pendukung-wrapper label { display: block; font-weight: bold; margin-bottom: 5px; }
        .foto-pendukung-wrapper input[type="text"] { width: 100%; max-width: 100%; padding: 5px; }
        .foto-preview { margin-top: 10px; max-width: 250px; height: auto; border: 1px solid #ddd; padding: 3px; display: block; background: #fff; }
    </style>';

    echo '<div class="foto-pendukung-wrapper">';
    echo '<label for="foto_pendukung_1">URL Foto Pendukung 1:</label>';
    echo '<input type="text" id="foto_pendukung_1" name="foto_pendukung_1" value="' . esc_attr($foto_1) . '" placeholder="Contoh: https://ikapsiundip.or.id/wp-content/uploads/2026/... .jpg">';
    if (!empty($foto_1)) {
        echo '<img src="' . esc_url($foto_1) . '" class="foto-preview" alt="Preview Foto 1">';
    }
    echo '</div>';

    echo '<div class="foto-pendukung-wrapper">';
    echo '<label for="foto_pendukung_2">URL Foto Pendukung 2:</label>';
    echo '<input type="text" id="foto_pendukung_2" name="foto_pendukung_2" value="' . esc_attr($foto_2) . '" placeholder="Contoh: https://ikapsiundip.or.id/wp-content/uploads/2026/... .jpg">';
    if (!empty($foto_2)) {
        echo '<img src="' . esc_url($foto_2) . '" class="foto-preview" alt="Preview Foto 2">';
    }
    echo '</div>';

    echo '<div class="foto-pendukung-wrapper">';
    echo '<label for="foto_pendukung_3">URL Foto Pendukung 3:</label>';
    echo '<input type="text" id="foto_pendukung_3" name="foto_pendukung_3" value="' . esc_attr($foto_3) . '" placeholder="Contoh: https://ikapsiundip.or.id/wp-content/uploads/2026/... .jpg">';
    if (!empty($foto_3)) {
        echo '<img src="' . esc_url($foto_3) . '" class="foto-preview" alt="Preview Foto 3">';
    }
    echo '</div>';
    
    echo '<p class="description"><strong>Catatan Sistem:</strong> Slot ini adalah wadah penampung gambar yang diunggah oleh alumni dari halaman depan (frontend). Admin masih dapat mengubah atau menghapus tautan URL secara manual melalui form ini jika diperlukan.</p>';
}

function save_alumni_corner_foto_meta($post_id) {
    if (!isset($_POST['alumni_corner_foto_nonce']) || !wp_verify_nonce($_POST['alumni_corner_foto_nonce'], 'save_alumni_corner_foto')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['foto_pendukung_1'])) {
        update_post_meta($post_id, 'foto_pendukung_1', esc_url_raw($_POST['foto_pendukung_1']));
    }
    if (isset($_POST['foto_pendukung_2'])) {
        update_post_meta($post_id, 'foto_pendukung_2', esc_url_raw($_POST['foto_pendukung_2']));
    }
    if (isset($_POST['foto_pendukung_3'])) {
        update_post_meta($post_id, 'foto_pendukung_3', esc_url_raw($_POST['foto_pendukung_3']));
    }
}
add_action('save_post_alumni_corner', 'save_alumni_corner_foto_meta');

// =========================================================================
// TAHAP 2: FRONTEND FORM SUBMISSION ALUMNI CORNER
// =========================================================================
require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');

function alumni_corner_submission_shortcode() {
    if (!is_user_logged_in()) {
        return '<div style="padding:20px; background:#fdd; border:1px solid #f99; border-radius:5px; color:#a00;">Anda harus <a href="' . wp_login_url() . '" style="font-weight:bold; color:#d00;">login</a> terlebih dahulu untuk dapat membuat postingan di Alumni Corner.</div>';
    }

    $message = '';

    if (isset($_POST['submit_alumni_corner'])) {
        if (!isset($_POST['alumni_corner_nonce']) || !wp_verify_nonce($_POST['alumni_corner_nonce'], 'submit_alumni_corner_action')) {
            return '<div style="color:red; margin-bottom:15px; padding:10px; border:1px solid red;">Error Sistem: Validasi keamanan sesi gagal. Silakan muat ulang halaman dan coba lagi.</div>';
        }

        $judul = sanitize_text_field($_POST['judul_post']);
        $konten = wp_kses_post($_POST['konten_post']); 

        if (empty($judul) || empty($konten)) {
            $message = '<div style="color:red; margin-bottom:15px; padding:10px; border:1px solid red;">Error: Judul dan isi tulisan wajib diisi.</div>';
        } else {
            if (empty($_FILES['foto_utama']['name'])) {
                $message = '<div style="color:red; margin-bottom:15px; padding:10px; border:1px solid red;">Error: Foto utama wajib diunggah.</div>';
            } else {
                $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
                $max_size = 2 * 1024 * 1024; 
                $upload_error = false;
                $files_to_check = ['foto_utama', 'foto_pendukung_1', 'foto_pendukung_2', 'foto_pendukung_3'];

                foreach ($files_to_check as $file_input) {
                    if (!empty($_FILES[$file_input]['name'])) {
                        if (!in_array($_FILES[$file_input]['type'], $allowed_types)) {
                            $message = '<div style="color:red; margin-bottom:15px; padding:10px; border:1px solid red;">Error: Format file <strong>' . esc_html($_FILES[$file_input]['name']) . '</strong> tidak diizinkan. Hanya menerima JPG, PNG, dan WEBP.</div>';
                            $upload_error = true;
                            break;
                        }
                        if ($_FILES[$file_input]['size'] > $max_size) {
                            $message = '<div style="color:red; margin-bottom:15px; padding:10px; border:1px solid red;">Error: Ukuran file <strong>' . esc_html($_FILES[$file_input]['name']) . '</strong> melebihi batas 2MB.</div>';
                            $upload_error = true;
                            break;
                        }
                    }
                }

                if (!$upload_error) {
                    $new_post = array(
                        'post_title'    => $judul,
                        'post_content'  => $konten,
                        'post_status'   => 'pending', 
                        'post_type'     => 'alumni_corner',
                        'post_author'   => get_current_user_id(),
                        'comment_status'=> 'open' 
                    );

                    $post_id = wp_insert_post($new_post);

                    if ($post_id && !is_wp_error($post_id)) {
                        $foto_utama_id = media_handle_upload('foto_utama', $post_id);
                        if (!is_wp_error($foto_utama_id)) {
                            set_post_thumbnail($post_id, $foto_utama_id);
                        }

                        $meta_keys = [
                            'foto_pendukung_1' => 'foto_pendukung_1',
                            'foto_pendukung_2' => 'foto_pendukung_2',
                            'foto_pendukung_3' => 'foto_pendukung_3',
                        ];

                        foreach ($meta_keys as $input_name => $meta_key) {
                            if (!empty($_FILES[$input_name]['name'])) {
                                $attachment_id = media_handle_upload($input_name, $post_id);
                                if (!is_wp_error($attachment_id)) {
                                    $image_url = wp_get_attachment_url($attachment_id);
                                    update_post_meta($post_id, $meta_key, esc_url_raw($image_url));
                                }
                            }
                        }

                        $message = '<div style="color:green; margin-bottom:15px; padding:15px; border:1px solid green; background:#efeed;"><strong>Sukses!</strong> Tulisan Anda berhasil dikirim dan sedang dalam antrean moderasi (Pending Review). Tulisan akan tayang setelah disetujui oleh Admin.</div>';
                    } else {
                        $message = '<div style="color:red; margin-bottom:15px; padding:10px; border:1px solid red;">Error: Gagal menyimpan data ke database server.</div>';
                    }
                }
            }
        }
    }

    ob_start();
    echo $message;
    ?>
    <form action="" method="post" enctype="multipart/form-data" style="background:#f9f9f9; padding:25px; border-radius:8px; border:1px solid #ddd;">
        <?php wp_nonce_field('submit_alumni_corner_action', 'alumni_corner_nonce'); ?>
        
        <h3 style="margin-top:0; margin-bottom:20px;">Buat Postingan Baru</h3>
        
        <div style="margin-bottom:20px;">
            <label for="judul_post" style="display:block; font-weight:bold; margin-bottom:5px;">Judul Tulisan <span style="color:red;">*</span></label>
            <input type="text" name="judul_post" id="judul_post" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;" placeholder="Tulis judul di sini...">
        </div>

        <div style="margin-bottom:20px;">
            <label for="konten_post" style="display:block; font-weight:bold; margin-bottom:5px;">Isi Tulisan <span style="color:red;">*</span></label>
            <textarea name="konten_post" id="konten_post" rows="8" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;" placeholder="Tuliskan pengalaman, opini, atau informasi di sini..."></textarea>
        </div>

        <div style="margin-bottom:20px; padding:15px; border:1px dashed #aaa; background:#fff; border-radius:4px;">
            <p style="margin-top:0; color:#555; font-size:14px; border-bottom:1px solid #eee; padding-bottom:10px; margin-bottom:15px;"><strong>Ketentuan Gambar:</strong> Hanya format JPG, PNG, WEBP. Maksimal 2MB per file.</p>
            
            <div style="margin-bottom:15px;">
                <label for="foto_utama" style="display:block; font-weight:bold; margin-bottom:5px;">Foto Utama / Thumbnail (Wajib) <span style="color:red;">*</span></label>
                <input type="file" name="foto_utama" id="foto_utama" accept=".jpg,.jpeg,.png,.webp" required style="width:100%;">
            </div>
            
            <hr style="border-top:1px dashed #eee; margin:15px 0;">
            
            <div style="margin-bottom:10px;">
                <label for="foto_pendukung_1" style="display:block; font-weight:bold; margin-bottom:5px; color:#555;">Foto Pendukung 1 (Opsional)</label>
                <input type="file" name="foto_pendukung_1" id="foto_pendukung_1" accept=".jpg,.jpeg,.png,.webp" style="width:100%;">
            </div>
            <div style="margin-bottom:10px;">
                <label for="foto_pendukung_2" style="display:block; font-weight:bold; margin-bottom:5px; color:#555;">Foto Pendukung 2 (Opsional)</label>
                <input type="file" name="foto_pendukung_2" id="foto_pendukung_2" accept=".jpg,.jpeg,.png,.webp" style="width:100%;">
            </div>
            <div style="margin-bottom:10px;">
                <label for="foto_pendukung_3" style="display:block; font-weight:bold; margin-bottom:5px; color:#555;">Foto Pendukung 3 (Opsional)</label>
                <input type="file" name="foto_pendukung_3" id="foto_pendukung_3" accept=".jpg,.jpeg,.png,.webp" style="width:100%;">
            </div>
        </div>

        <button type="submit" name="submit_alumni_corner" style="padding:12px 25px; background:#0073aa; color:#fff; font-weight:bold; border:none; border-radius:4px; cursor:pointer; width:100%;">Kirim Postingan</button>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('form_alumni_corner', 'alumni_corner_submission_shortcode');

// =========================================================================
// TAHAP 3: AJAX HANDLER UNTUK SEARCH, FILTER, & PAGINASI
// =========================================================================
add_action('wp_ajax_filter_alumni_corner', 'ajax_filter_alumni_corner_handler');
add_action('wp_ajax_nopriv_filter_alumni_corner', 'ajax_filter_alumni_corner_handler');

function ajax_filter_alumni_corner_handler() {
    $search       = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $sort         = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'new';
    $current_page = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $args = array(
        'post_type'      => 'alumni_corner',
        'post_status'    => 'publish',
        'posts_per_page' => 9,         
        'paged'          => $current_page, 
        's'              => $search,
        'orderby'        => 'date',
        'order'          => ($sort === 'old') ? 'ASC' : 'DESC'
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <article class="artikel-card">
                <div class="card-img-wrapper">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium_large'); ?>
                    <?php else : ?>
                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #aaa; font-size: 14px;">Tanpa Gambar</div>
                    <?php endif; ?>
                </div>
                
                <div class="card-content">
                    <span class="card-date"><?php echo get_the_date(); ?></span>
                    <h3 class="card-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <div class="card-desc">
                        <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                    </div>
                    
                    <div class="card-footer">
                        <span class="card-author">Oleh: <?php the_author(); ?></span>
                        <a href="<?php the_permalink(); ?>" class="card-link">Baca Selengkapnya &raquo;</a>
                    </div>
                </div>
            </article>
            <?php
        }

        $total_pages = $query->max_num_pages;
        if ($total_pages > 1) {
            echo '<div class="alumni-pagination" style="margin-top: 40px; text-align: center; grid-column: 1 / -1; display: flex; justify-content: center; gap: 8px; width: 100%;">';
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i === $current_page) {
                    $style_pill = 'background: #D74690; color: #fff; border-color: #D74690;';
                    $class_active = 'page-pill active';
                } else {
                    $style_pill = 'background: #fff; color: #666; border-color: #eaeaea;';
                    $class_active = 'page-pill';
                }
                echo '<button class="' . $class_active . '" data-page="' . $i . '" style="padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; border: 1px solid; transition: 0.2s; cursor: pointer; ' . $style_pill . '">' . $i . '</button>';
            }
            echo '</div>';
        }
    } else {
        echo '<div style="width: 100%; text-align: center; padding: 50px 0; color: #999; grid-column: 1 / -1; font-style: italic;">Tidak ada postingan yang ditemukan.</div>';
    }

    wp_reset_postdata();
    die();
}

// =========================================================================
// TAHAP 4: AUTO-APPROVE KOMENTAR
// =========================================================================
add_filter('pre_comment_approved', 'auto_approve_alumni_corner_comments', 10, 2);
function auto_approve_alumni_corner_comments($approved, $commentdata) {
    if (isset($commentdata['comment_post_ID'])) {
        if (get_post_type($commentdata['comment_post_ID']) === 'alumni_corner') {
            return 1; 
        }
    }
    return $approved;
}

// =========================================================================
// TAHAP 5: CUSTOM LAYOUT KOMENTAR & SISTEM AJAX (HAPUS & INLINE EDIT)
// =========================================================================

// A. Fungsi Pembuat Layout Komentar Kustom (Absolute Control)
function ikapsi_alumni_comment_layout($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    
    // Pengecekan Hak Akses
    $can_edit_delete = false;
    if ( is_user_logged_in() ) {
        $current_user_id = get_current_user_id();
        if ( current_user_can( 'moderate_comments' ) || current_user_can( 'manage_options' ) || $current_user_id == $comment->user_id ) {
            $can_edit_delete = true;
        }
    }
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment-body">
            
            <div class="comment-author vcard">
                <?php echo get_avatar($comment, 32); ?>
                <?php printf('<cite class="fn">%s</cite>', get_comment_author_link()); ?>
                <div class="comment-meta">
                    <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                        <time datetime="<?php comment_time('c'); ?>">
                            <?php printf('%1$s pukul %2$s', get_comment_date('d M Y'), get_comment_time()); ?>
                        </time>
                    </a>
                    <?php 
                    // MENGAMBIL DAN MENAMPILKAN WAKTU EDIT JIKA ADA
                    $edited_time = get_comment_meta($comment->comment_ID, '_comment_edited_time', true);
                    $display_edited = $edited_time ? '(Diedit: ' . date_i18n('d M Y - H:i', strtotime($edited_time)) . ')' : '';
                    ?>
                    <span class="edited-time-label" id="edited-time-label-<?php comment_ID(); ?>" style="margin-left:6px; font-style:italic; color:#bcaaa4; font-weight:600; font-size:10px;"><?php echo esc_html($display_edited); ?></span>
                </div>
            </div>

            <div class="comment-content-wrapper">
                <div class="comment-text" id="comment-text-<?php comment_ID(); ?>">
                    <?php comment_text(); ?>
                </div>

                <?php if ($can_edit_delete) : ?>
                    <div class="comment-edit-form" id="comment-edit-form-<?php comment_ID(); ?>" style="display:none; margin-top:10px;">
                        <textarea id="edit-textarea-<?php comment_ID(); ?>" style="width:100%; padding:10px; border-radius:6px; border:1px solid #D74690; font-family:inherit; font-size:13px; resize:vertical;"><?php echo esc_textarea(get_comment_text()); ?></textarea>
                        <div style="margin-top:8px; text-align:right;">
                            <button class="btn-cancel-edit" data-id="<?php comment_ID(); ?>" style="background:#eee; color:#555; border:none; padding:6px 14px; border-radius:20px; font-size:11px; font-weight:bold; cursor:pointer; margin-right:8px;">Batal</button>
                            <button class="btn-save-edit" data-id="<?php comment_ID(); ?>" data-nonce="<?php echo wp_create_nonce('edit_comment_nonce_' . $comment->comment_ID); ?>" style="background:#D74690; color:#fff; border:none; padding:6px 14px; border-radius:20px; font-size:11px; font-weight:bold; cursor:pointer;">Simpan Pembaruan</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="reply">
                <?php 
                // Tombol Balas Asli
                comment_reply_link(array_merge($args, array(
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'reply_text'=> 'Balas ↵'
                ))); 
                ?>
                
                <?php if ($can_edit_delete) : ?>
                    <a href="#" class="btn-inline-edit" data-id="<?php comment_ID(); ?>" style="color:#2a70cc; margin-left:8px;">Edit</a>
                    <a href="#" class="btn-delete-comment" data-id="<?php comment_ID(); ?>" data-nonce="<?php echo wp_create_nonce('delete_comment_nonce_' . $comment->comment_ID); ?>" style="color:#dc3545; margin-left:8px;">Hapus</a>
                <?php endif; ?>
            </div>
            
        </article>
    <?php
}

// B. Handler AJAX untuk DELETE Komentar
add_action('wp_ajax_delete_alumni_comment', 'ikapsi_ajax_delete_comment');
function ikapsi_ajax_delete_comment() {
    $comment_id = isset($_POST['comment_id']) ? intval($_POST['comment_id']) : 0;
    
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'delete_comment_nonce_' . $comment_id)) {
        wp_send_json_error('Otorisasi keamanan gagal.');
    }

    $comment = get_comment($comment_id);
    if (!$comment) {
        wp_send_json_error('Komentar tidak ditemukan.');
    }

    $current_user_id = get_current_user_id();
    $can_delete = false;

    if (current_user_can('moderate_comments') || current_user_can('manage_options') || $current_user_id == $comment->user_id) {
        $can_delete = true;
    }

    if ($can_delete) {
        wp_delete_comment($comment_id, true); 
        wp_send_json_success('Komentar berhasil dihapus.');
    } else {
        wp_send_json_error('Anda tidak memiliki izin.');
    }
}

// C. Handler AJAX untuk UPDATE (INLINE EDIT) Komentar
add_action('wp_ajax_edit_alumni_comment', 'ikapsi_ajax_edit_comment');
function ikapsi_ajax_edit_comment() {
    $comment_id = isset($_POST['comment_id']) ? intval($_POST['comment_id']) : 0;
    $new_content = isset($_POST['content']) ? wp_kses_post($_POST['content']) : '';
    
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'edit_comment_nonce_' . $comment_id)) {
        wp_send_json_error('Otorisasi keamanan gagal.');
    }

    $comment = get_comment($comment_id);
    if (!$comment) {
        wp_send_json_error('Komentar tidak ditemukan.');
    }

    $current_user_id = get_current_user_id();
    $can_edit = false;

    if (current_user_can('moderate_comments') || current_user_can('manage_options') || $current_user_id == $comment->user_id) {
        $can_edit = true;
    }

    if ($can_edit) {
        if (empty(trim($new_content))) {
            wp_send_json_error('Komentar tidak boleh kosong.');
        }

        // Simpan isi komentar baru
        wp_update_comment(array(
            'comment_ID' => $comment_id,
            'comment_content' => $new_content
        ));

        // SIMPAN WAKTU EDIT KE DALAM DATABASE
        $current_time = current_time('mysql');
        update_comment_meta($comment_id, '_comment_edited_time', $current_time);

        // Siapkan format balikan data ke halaman depan
        $formatted_text = apply_filters('comment_text', get_comment_text($comment_id));
        $edited_time_string = '(Diedit: ' . date_i18n('d M Y - H:i', strtotime($current_time)) . ')';
        
        wp_send_json_success(array(
            'new_html' => $formatted_text,
            'edited_time_string' => $edited_time_string
        ));
    } else {
        wp_send_json_error('Anda tidak memiliki izin.');
    }
}
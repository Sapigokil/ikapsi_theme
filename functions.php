<?php
/**
 * IKAPSI UNDIP functions and definitions
 */

if ( ! function_exists( 'ikapsi_undip_setup' ) ) {
    function ikapsi_undip_setup() {
        // Menambahkan dukungan tag title otomatis dari WordPress ke bagian <head>
        add_theme_support( 'title-tag' );

        // Menambahkan dukungan gambar fitur (Featured Image / Thumbnail) pada post dan page
        add_theme_support( 'post-thumbnails' );

        // Mendaftarkan lokasi menu navigasi dasar
        register_nav_menus( array(
            'primary' => __( 'Primary Menu', 'ikapsi-undip' ),
        ) );
    }
}
add_action( 'after_setup_theme', 'ikapsi_undip_setup' );

/**
 * Memuat (Enqueue) file CSS utama tema.
 */
function ikapsi_undip_scripts() {
    // Memanggil file style.css
    wp_enqueue_style( 'ikapsi-undip-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version') );
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
            
            // Ambil Data ACF
            $lokasi       = get_field('lokasi');
            $nama_alumni  = get_field('nama_alumni');
            $angkatan     = get_field('angkatan');
            $foto_alumni  = get_field('foto_alumni');
            $link_project = get_field('link_project');
            
            // Ambil Kategori Pertama untuk Badge
            $terms = get_the_terms(get_the_ID(), 'project_category');
            $badge = $terms && !is_wp_error($terms) ? $terms[0]->name : 'Uncategorized';
            
            // Logika Fallback
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

// 4. Arahkan ke Beranda setelah login berhasil (kecuali Admin & SSO)
add_filter( 'login_redirect', 'ikapsi_after_login_redirect', 999, 3 );
function ikapsi_after_login_redirect( $redirect_to, $request, $user ) {
    // Memastikan objek user valid
    if ( ! is_a( $user, 'WP_User' ) ) {
        return $redirect_to;
    }

    // Jangan merubah alur jika user datang dari tombol "JOIN NOW" (yang memiliki trigger SSO)
    if ( ! empty( $request ) && strpos( $request, 'go_to_member' ) !== false ) {
        return $request;
    }

    // Administrator tetap diarahkan ke dashboard WP (wp-admin)
    if ( in_array( 'administrator', (array) $user->roles ) ) {
        return admin_url();
    }

    // Selain kondisi di atas, paksa alumni untuk diarahkan kembali ke Beranda (Homepage)
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
        
        $laravel_url = defined('LARAVEL_SSO_URL') ? LARAVEL_SSO_URL : 'https://member.ikapsiundip.or.id/sso-login';
        
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
    // Tautan trigger SSO
    $link = home_url('/?go_to_member=1');
    $label = is_user_logged_in() ? 'Masuk Member Area' : 'Login Alumni';
    
    // Jika belum login di WP, arahkan ke login WP dulu baru lempar ke SSO
    if (!is_user_logged_in()) {
        $link = wp_login_url(home_url('/?go_to_member=1'));
    }

    // Output Tombol
    return '<a href="' . esc_url($link) . '" class="btn-member-area" style="background-color: #D74690; color: #ffffff !important; padding: 12px 25px; border-radius: 8px; font-weight: bold; text-decoration: none; display: inline-block; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">' . esc_html($label) . '</a>';
}
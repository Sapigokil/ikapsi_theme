<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    
    <style>
        /* Styling khusus untuk Header Internal (Horizontal Layout) */
        .header-internal {
            background-color: #ffffff;
            border-bottom: 1px solid #eaeaea;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-internal-container {
            max-width: 1350px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Bagian Kiri: Logo */
        .site-branding-internal img {
            max-height: 50px; /* Logo dibuat lebih kecil untuk header horizontal */
            width: auto;
            object-fit: contain;
        }

        /* Bagian Tengah: Navigasi */
        .main-navigation-internal ul {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 30px;
        }
        .main-navigation-internal ul li a {
            color: #555555;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        .main-navigation-internal ul li.current-menu-item a,
        .main-navigation-internal ul li a:hover {
            color: #D74690;
            font-weight: 600;
        }

        /* Bagian Kanan: Aksi (Masuk & Gabung) */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .btn-masuk {
            color: #555555;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        .btn-masuk:hover {
            color: #D74690;
        }
        .btn-gabung {
            background-color: #D74690;
            color: #ffffff;
            padding: 10px 24px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .btn-gabung:hover {
            background-color: #bf3a7d;
        }

        /* Responsif untuk Mobile */
        @media (max-width: 992px) {
            .main-navigation-internal, .header-actions {
                display: none; /* Sembunyikan menu desktop di mobile */
            }
            /* Disini nantinya bisa ditambahkan tombol hamburger icon untuk menu mobile */
        }
    </style>
</head>

<body <?php body_class(); ?> style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #fafafa;">
<?php wp_body_open(); ?>

<header id="masthead-internal" class="header-internal">
    <div class="header-internal-container">
        
        <!-- Bagian Kiri: Logo -->
        <div class="site-branding-internal">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" style="display: inline-block;">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/images/logo-1.png' ); ?>" alt="Logo IKAPSI UNDIP">
            </a>
        </div>

        <!-- Bagian Tengah: Menu Navigasi -->
        <nav id="site-navigation-internal" class="main-navigation-internal">
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu-internal',
                    'container'      => false, 
                    'fallback_cb'    => false, 
                ) );
            } else {
                echo '<p style="color: #888; font-size: 12px; margin: 0;"><em>Atur Menu WP</em></p>';
            }
            ?>
        </nav>

        <!-- Bagian Kanan: Tombol Aksi -->
        <div class="header-actions">
            <a href="#" class="btn-masuk">Masuk</a>
            <a href="#" class="btn-gabung">Gabung Alumni</a>
        </div>

    </div>
</header>

<div id="content" class="site-content">
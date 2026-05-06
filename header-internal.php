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
            position: relative; /* Penting untuk penempatan dropdown mobile */
        }

        /* Bagian Kiri: Logo */
        .site-branding-internal img {
            max-height: 50px; 
            width: auto;
            object-fit: contain;
        }

        /* Wrapper untuk Menu dan Tombol (Desktop) */
        .mobile-menu-wrapper {
            display: flex;
            align-items: center;
            gap: 40px;
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
            white-space: nowrap;
        }
        .btn-gabung:hover {
            background-color: #bf3a7d;
        }

        /* Tombol Hamburger (Disembunyikan di Desktop) */
        .menu-toggle {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 28px;
            height: 20px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            z-index: 1001;
        }
        .menu-toggle span {
            width: 100%;
            height: 3px;
            background-color: #555555;
            border-radius: 4px;
            transition: all 0.3s ease;
            transform-origin: left center;
        }

        /* Responsif untuk Mobile */
        @media (max-width: 992px) {
            .menu-toggle {
                display: flex; /* Munculkan hamburger di layar kecil */
            }

            .mobile-menu-wrapper {
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: #ffffff;
                flex-direction: column;
                align-items: flex-start;
                padding: 20px;
                gap: 20px;
                border-bottom: 1px solid #eaeaea;
                box-shadow: 0 10px 15px rgba(0,0,0,0.05);
                
                /* Efek animasi dropdown */
                visibility: hidden;
                opacity: 0;
                transform: translateY(-10px);
                transition: all 0.3s ease;
            }

            .mobile-menu-wrapper.active {
                visibility: visible;
                opacity: 1;
                transform: translateY(0);
            }

            .main-navigation-internal {
                width: 100%;
            }
            
            .main-navigation-internal ul {
                flex-direction: column;
                gap: 15px;
                width: 100%;
            }
            
            .main-navigation-internal ul li {
                width: 100%;
                border-bottom: 1px solid #f9f9f9;
                padding-bottom: 10px;
            }

            .header-actions {
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .btn-gabung {
                text-align: center;
                width: 100%;
                box-sizing: border-box;
            }

            /* Animasi Icon Hamburger menjadi tanda silang (X) */
            .menu-toggle.active span:nth-child(1) {
                transform: rotate(45deg);
            }
            .menu-toggle.active span:nth-child(2) {
                opacity: 0;
            }
            .menu-toggle.active span:nth-child(3) {
                transform: rotate(-45deg);
            }
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

        <!-- Tombol Hamburger untuk Mobile -->
        <button class="menu-toggle" aria-label="Buka Menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <!-- Wrapper Menu & Aksi (Akan menjadi Dropdown di Mobile) -->
        <div class="mobile-menu-wrapper">
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

    </div>
</header>

<!-- Script Hamburger Menu -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.querySelector('.menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu-wrapper');

        if(menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', function() {
                menuToggle.classList.toggle('active');
                mobileMenu.classList.toggle('active');
                
                // Update ARIA attribute untuk aksesibilitas
                const isExpanded = menuToggle.classList.contains('active');
                menuToggle.setAttribute('aria-expanded', isExpanded);
            });
        }
    });
</script>

<div id="content" class="site-content">
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

        /* Bagian Kanan: Aksi (Satu Tombol Sentral) */
        .header-actions {
            display: flex;
            align-items: center;
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
            border: none;
            cursor: pointer;
            font-family: inherit;
            display: inline-block;
        }
        .btn-gabung:hover {
            background-color: #bf3a7d;
        }

        /* Styling Dropdown Member */
        .dropdown-member-wrapper {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-member-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 15px);
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            width: 180px;
            display: flex;
            flex-direction: column;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1005;
            overflow: hidden;
        }
        
        .dropdown-member-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-member-menu a {
            padding: 12px 20px;
            color: #555555;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-bottom: 1px solid #f5f5f5;
            transition: background 0.2s, color 0.2s;
        }
        
        .dropdown-member-menu a:last-child {
            border-bottom: none;
        }
        
        .dropdown-member-menu a:hover {
            background-color: #FFF0F6; /* Soft pink hover */
            color: #D74690;
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
                display: flex; 
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
            }
            
            /* Penyesuaian Dropdown di HP agar statis dan memanjang */
            .dropdown-member-wrapper {
                width: 100%;
            }
            
            .btn-gabung {
                width: 100%;
                text-align: center;
                box-sizing: border-box;
            }

            .dropdown-member-menu {
                position: static;
                width: 100%;
                box-shadow: none;
                border: 1px solid #eaeaea;
                margin-top: 10px;
                display: none; /* Sembunyikan elemen fisik sebelum dipanggil agar tidak makan ruang */
                transform: none;
                opacity: 1;
                visibility: visible;
            }
            
            .dropdown-member-menu.show {
                display: flex;
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
        
        <div class="site-branding-internal">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" style="display: inline-block;">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/images/logo-1.png' ); ?>" alt="Logo IKAPSI UNDIP">
            </a>
        </div>

        <button class="menu-toggle" aria-label="Buka Menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="mobile-menu-wrapper">
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

            <div class="header-actions">
                <?php if ( is_user_logged_in() ) : ?>
                    
                    <div class="dropdown-member-wrapper">
                        <button class="btn-gabung member-dropdown-toggle">Member ▼</button>
                        <div class="dropdown-member-menu">
                            <a href="<?php echo esc_url( home_url( '/user/' ) ); ?>">Member Area</a>
                            <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">Logout</a>
                        </div>
                    </div>
                    
                <?php else : ?>
                    
                    <a href="<?php echo esc_url( home_url( '/login/' ) ); ?>" class="btn-gabung">Login</a>
                    
                <?php endif; ?>
            </div>
        </div>

    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logika untuk Mobile Hamburger Menu
        const menuToggle = document.querySelector('.menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu-wrapper');

        if(menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                menuToggle.classList.toggle('active');
                mobileMenu.classList.toggle('active');
                
                const isExpanded = menuToggle.classList.contains('active');
                menuToggle.setAttribute('aria-expanded', isExpanded);
            });
        }

        // Logika untuk Dropdown Member
        const memberToggle = document.querySelector('.member-dropdown-toggle');
        const memberMenu = document.querySelector('.dropdown-member-menu');

        if(memberToggle && memberMenu) {
            memberToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                memberMenu.classList.toggle('show');
            });

            // Otomatis menutup dropdown jika user mengklik area lain di layar
            document.addEventListener('click', function(e) {
                if(!memberMenu.contains(e.target) && e.target !== memberToggle) {
                    memberMenu.classList.remove('show');
                }
            });
        }
    });
</script>

<div id="content" class="site-content">
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    
    <style>
        /* Styling tambahan agar menu navigasi sesuai desain */
        .main-navigation ul {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 15px 0 10px 0;
            gap: 25px;
            flex-wrap: wrap;
        }
        .main-navigation ul li a {
            color: #D74690; /* Warna pink baru sesuai permintaan */
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            transition: color 0.3s ease;
        }
        .main-navigation ul li a:hover {
            color: #0b1c4c;
        }
        
        /* Merapikan Search Bar agar berbentuk pil dengan ikon di dalam */
        .header-search form {
            position: relative;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }
        .header-search input[type="search"] {
            width: 100%;
            padding: 10px 20px 10px 40px; /* Padding kiri lebih besar untuk tempat ikon */
            border: 1px solid #ddd;
            border-radius: 30px; /* Membuat ujungnya melengkung penuh */
            outline: none;
            font-size: 14px;
            color: #333;
            background-color: #fcfcfc;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        }
        .header-search input[type="search"]:focus {
            border-color: #D74690;
            background-color: #fff;
        }
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            width: 16px;
            height: 16px;
            pointer-events: none; /* Agar klik tembus langsung ke input teks */
        }
    </style>
</head>

<body <?php body_class(); ?> style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #ffffff;">
<?php wp_body_open(); ?>

<header id="masthead" class="site-header" style="border-bottom: 1px solid #eaeaea; padding-bottom: 25px; background-color: #fff;">
    
    <div class="top-bar" style="background-color: #D74690; color: #ffffff; text-align: center; padding: 8px 0; font-size: 13px; font-weight: 500;">
        <div class="container">
            Fakultas Psikologi Universitas Diponegoro | IKAPSI UNDIP
        </div>
    </div>

    <div class="main-header" style="text-align: center; padding-top: 30px;">
        <div class="container">
            
            <div class="site-branding">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" style="display: inline-block;">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/images/logo-1.png' ); ?>" alt="Logo IKAPSI UNDIP" style="max-height: 85px; width: auto; object-fit: contain;">
                </a>
            </div>

            <nav id="site-navigation" class="main-navigation">
                <?php
                if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false, 
                        'fallback_cb'    => false, 
                    ) );
                } else {
                    echo '<p style="color: #888; font-size: 12px; margin-top: 15px;"><em>Silakan buat dan atur Menu di Dashboard WP untuk menampilkannya di sini.</em></p>';
                }
                ?>
            </nav>

            <div class="header-search" style="margin-top: 15px; padding: 0 20px;">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    
                    <input type="search" placeholder="Search..." value="<?php echo get_search_query(); ?>" name="s" />
                    
                    <button type="submit" style="display: none;">Search</button>
                </form>
            </div>

        </div>
    </div>
</header>

<div id="content" class="site-content">
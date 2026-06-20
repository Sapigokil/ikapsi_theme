<?php
/**
 * Template Name: Front Page
 * Description: Template khusus untuk halaman beranda IKAPSI UNDIP.
 */

get_header(); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
    /* ==========================================================================
       HERO SLIDER STYLING (Responsive Aspect Ratio)
       ========================================================================== */
    .hero-swiper { width: 100%; position: relative; }
    .swiper-slide { position: relative; display: block; }

    .hero-img-element {
        width: 100%;
        height: auto;
        display: block;
        /* Rasio Desktop Lebar (10:4) agar tidak terlalu gepeng namun tidak terlalu tinggi */
        aspect-ratio: 2.5 / 1; 
        object-fit: cover; 
    }
    
    .swiper-pagination-bullet-active { background: #D74690 !important; }
    .swiper-pagination-bullet { background: #fff; opacity: 1; width: 12px; height: 12px; }
    
    .slide-overlay { 
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
        background: linear-gradient(to bottom, rgba(0,0,0,0) 50%, rgba(0,0,0,0.4) 100%); 
        pointer-events: none; z-index: 2;
    }

    .join-button-wrapper { position: absolute; right: 8%; bottom: -30px; z-index: 99; }
    .btn-join { 
        display: inline-block; background-color: #D74690; color: #ffffff; 
        padding: 20px 45px; text-decoration: none; font-weight: bold; font-size: 16px; 
        border-radius: 6px; box-shadow: 0 6px 20px rgba(215,70,144,0.4); transition: 0.3s;
    }
    .btn-join:hover { background-color: #0b1c4c; transform: translateY(-3px); }
    
    /* ==========================================================================
       GLOBAL LAYOUT & GRID SYSTEM (Kunci 3 Kolom)
       ========================================================================== */
    .section-title { color: #D74690; margin: 0; font-size: 32px; font-weight: 700; }
    .section-link { color: #D74690; font-size: 16px; font-weight: 600; text-decoration: none; transition: 0.2s; }
    .section-link:hover { color: #0b1c4c; }

    /* Grid Mutlak 3 Kolom untuk Desktop */
    .grid-3-col { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }

    /* Video Container Responsive */
    .video-responsive { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 12px; background: #000; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .video-responsive iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; }

    /* ==========================================================================
       RESPONSIVE BREAKPOINTS
       ========================================================================== */
    
    /* Half-Desktop / Tablet (Layar Menengah) */
    @media (max-width: 1024px) {
        .hero-img-element { aspect-ratio: 2 / 1; } /* Rasio lebih kotak untuk tablet */
        .grid-3-col { grid-template-columns: repeat(2, 1fr); } /* Turun menjadi 2 kolom */
        .profil-row { flex-direction: column; text-align: center; }
        .profil-img-wrapper { margin: 0 auto 30px; }
    }

    /* Smartphone (Mobile) */
    @media (max-width: 768px) {
        .hero-img-element { aspect-ratio: 16 / 9; } /* Rasio standar HP agar gambar jelas */
        .grid-3-col { grid-template-columns: 1fr; } /* Turun menjadi 1 kolom penuh */
        .join-button-wrapper { right: 50% !important; transform: translateX(50%); bottom: -25px !important; }
        .btn-join { padding: 15px 35px; font-size: 14px; width: max-content; }
        .section-header { flex-direction: column; align-items: flex-start !important; gap: 15px; }
        .section-title { font-size: 26px; }
    }
</style>

<main id="primary" class="site-main" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <section class="hero-section" style="position: relative;">
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                <?php 
                $has_slides = false;
                for ($i = 1; $i <= 5; $i++) {
                    $img_url = get_field('hero_' . $i);
                    $link_url = get_field('url_hero' . $i); 
                    
                    if ($img_url) : 
                        $has_slides = true;
                ?>
                    <div class="swiper-slide">
                        <?php if ($link_url) : ?>
                            <a href="<?php echo esc_url($link_url); ?>" style="display: block; width: 100%; height: 100%; position: relative;">
                        <?php endif; ?>
                        
                        <img src="<?php echo esc_url($img_url); ?>" alt="Banner Hero IKAPSI UNDIP" class="hero-img-element">
                        <div class="slide-overlay"></div>
                        
                        <?php if ($link_url) : ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php 
                    endif;
                } 
                if (!$has_slides) : ?>
                    <div class="swiper-slide">
                        <div class="hero-img-element" style="background-color: #e0e0e0; display:flex; align-items:center; justify-content:center;">
                            <p style="color: #666; margin:0; font-size:18px;">Upload gambar hero (Rekomendasi Rasio Lebar) di ACF Beranda</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="swiper-pagination" style="bottom: 30px; z-index: 10;"></div>
        </div>
        
        <div class="join-button-wrapper">
            <?php if ( is_user_logged_in() ) : ?>
                <a href="<?php echo esc_url( home_url( '/?go_to_member=1' ) ); ?>" class="btn-join">MEMBER AREA</a>
            <?php else : ?>
                <?php 
                $login_url = home_url( '/login/' );
                $redirect_url = urlencode( home_url( '/?go_to_member=1' ) );
                $final_login_url = add_query_arg( 'redirect_to', $redirect_url, $login_url );
                ?>
                <a href="<?php echo esc_url( $final_login_url ); ?>" class="btn-join">JOIN NOW</a>
            <?php endif; ?>
        </div>
    </section>

    <section class="info-terbaru-section" style="padding: 100px 0 60px;">
        <div class="container" style="max-width: 1350px; margin: 0 auto; padding: 0 20px;">
            
            <div class="section-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 50px;">
                <h2 class="section-title">Info Terbaru</h2>
                <a href="<?php echo esc_url(home_url('/artikel/')); ?>" class="section-link">Lihat Semua Info &raquo;</a>
            </div>

            <div class="grid-3-col">
                <?php
                $query_info = new WP_Query(array('post_type' => 'post', 'posts_per_page' => 3));
                if ($query_info->have_posts()) : while ($query_info->have_posts()) : $query_info->the_post(); ?>
                    <div class="card" style="border: 1px solid #eaeaea; border-radius: 8px; overflow: hidden; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; flex-direction: column;">
                        <div class="thumbnail" style="height: 240px;"><?php if (has_post_thumbnail()) the_post_thumbnail('medium_large', array('style' => 'width:100%; height:100%; object-fit:cover;')); ?></div>
                        <div class="content" style="padding: 30px; flex-grow: 1; display: flex; flex-direction: column;">
                            <h3 style="font-size: 20px; color: #2d2424; margin: 0 0 15px 0; font-weight: 700; line-height: 1.4;"><?php the_title(); ?></h3>
                            <p style="font-size: 16px; color: #555; margin-bottom: 25px; line-height: 1.6; flex-grow: 1;"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 15px;">
                                <span style="font-size: 13px; color: #888; font-weight: 600;"><?php echo get_the_date('d M Y'); ?></span>
                                <a href="<?php the_permalink(); ?>" style="color: #D74690; text-decoration: none; font-weight: 700; font-size: 14px;">Selengkapnya &rarr;</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); endif; ?>
            </div>
        </div>
    </section>

    <section class="event-section" style="padding: 40px 0 80px;">
        <div class="container" style="max-width: 1350px; margin: 0 auto; padding: 0 20px;">
            
            <div class="section-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 50px;">
                <h2 class="section-title">Event dan Pelatihan</h2>
                <a href="<?php echo esc_url(home_url('/daftar-event/')); ?>" class="section-link">Semua Event &raquo;</a>
            </div>

            <div class="grid-3-col">
                <?php
                $query_event = new WP_Query(array('post_type' => 'agenda_event', 'posts_per_page' => 3));
                if ($query_event->have_posts()) : while ($query_event->have_posts()) : $query_event->the_post(); 
                    
                    $acf_date = get_field('tanggal_event');
                    if( $acf_date ) {
                        $date_obj = DateTime::createFromFormat('d-m-Y', $acf_date);
                        $event_day = $date_obj->format('d');
                        $event_month_year = $date_obj->format('M Y');
                    } else {
                        $event_day = get_the_date('d');
                        $event_month_year = get_the_date('M Y');
                    }
                ?>
                    <div class="card" style="border: 1px solid #eaeaea; border-radius: 8px; position: relative; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: hidden; display: flex; flex-direction: column;">
                        
                        <div class="date-badge" style="position: absolute; top: 20px; left: 20px; background: #fff; text-align: center; z-index: 2; box-shadow: 0 4px 10px rgba(0,0,0,0.15); border-radius: 6px; overflow: hidden;">
                            <span style="font-size: 30px; font-weight: 800; color: #2d2424; display: block; padding: 8px 15px 0;"><?php echo $event_day; ?></span>
                            <span style="font-size: 12px; background: #D74690; color: #fff; padding: 6px 15px; display: block; margin-top: 5px; font-weight: 700; letter-spacing: 1px;"><?php echo strtoupper($event_month_year); ?></span>
                        </div>
                        
                        <div class="thumbnail" style="height: 240px;"><?php if (has_post_thumbnail()) the_post_thumbnail('medium_large', array('style' => 'width:100%; height:100%; object-fit:cover;')); ?></div>
                        
                        <div class="content" style="padding: 30px; flex-grow: 1; display: flex; flex-direction: column;">
                            <h3 style="font-size: 20px; color: #2d2424; margin: 0 0 15px 0; font-weight: 700; line-height: 1.4;"><?php the_title(); ?></h3>
                            <p style="font-size: 16px; color: #555; margin-bottom: 25px; line-height: 1.6; flex-grow: 1;"><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p>
                            <a href="<?php the_permalink(); ?>" style="display: inline-block; background: #fdfdfd; border: 1px solid #D74690; color: #D74690; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: 700; text-align: center; transition: 0.2s;">Lihat Detail Event</a>
                        </div>
                        
                    </div>
                <?php endwhile; wp_reset_postdata(); endif; ?>
            </div>
        </div>
    </section>

    <section class="galeri-section" style="padding: 20px 0 80px;">
        <div class="container" style="max-width: 1350px; margin: 0 auto; padding: 0 20px;">
            <h2 class="section-title" style="margin-bottom: 50px;">Galeri Foto & Video</h2>
            <div class="galeri-plugin-wrapper" style="background: #fff; padding: 30px; border-radius: 12px; border: 1px solid #eaeaea; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                <?php echo do_shortcode('[ngg src="galleries" ids="1" display="basic_thumbnail" thumbnail_crop="0"]'); ?>
            </div>
        </div>
    </section>

    <section class="profil-section" style="background-color: #0b1c4c; color: #ffffff; padding: 100px 0;">
        <div class="container" style="max-width: 1350px; margin: 0 auto; padding: 0 20px;">
            <div class="row profil-row" style="display: flex; gap: 60px; align-items: center;">
                
                <div class="col-md-5" style="flex: 1; min-width: 300px;">
                    <?php 
                    $profil_logo = get_field('profil_logo');
                    $profil_deskripsi = get_field('profil_deskripsi');
                    
                    if (!$profil_logo) {
                        $profil_logo = get_template_directory_uri() . '/images/logo-1.png';
                    }
                    if (!$profil_deskripsi) {
                        $profil_deskripsi = 'Membangun sinergi antar alumni psikologi Universitas Diponegoro untuk kemajuan bersama dan kontribusi nyata bagi masyarakat.';
                    }
                    ?>
                    <div class="profil-img-wrapper" style="margin-bottom: 30px;">
                        <img src="<?php echo esc_url($profil_logo); ?>" alt="Profil IKAPSI UNDIP" style="width: 100%; max-width: 400px; height: auto; object-fit: contain;">
                    </div>
                    <div style="font-size: 17px; line-height: 1.8; color: #d0d5e0;">
                        <?php echo wpautop(wp_kses_post($profil_deskripsi)); ?>
                    </div>
                </div>
                
                <div class="col-md-7" style="flex: 1.2; min-width: 300px;">
                    <?php 
                    $youtube_embed_code = get_field('link_video_profile'); 
                    if ($youtube_embed_code) :
                    ?>
                        <div class="video-responsive">
                            <?php echo $youtube_embed_code; ?>
                        </div>
                    <?php else : ?>
                        <div style="background-color: #1a2a5a; height: 350px; display: flex; align-items: center; justify-content: center; border-radius: 12px; border: 1px dashed #405599;">
                            <p style="color: #8da2e5; font-size: 16px;">Input Embed YouTube di ACF Beranda</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

    <section class="hubungi-section" style="padding: 100px 0; background-color: #f4f6f9;">
        <div class="container" style="max-width: 1350px; margin: 0 auto; padding: 0 20px;">
            <h2 class="section-title" style="margin-bottom: 50px;">Hubungi IKAPSI UNDIP</h2>
            
            <div class="grid-3-col">
                <?php 
                $cek_kontak = get_field('kontak_1_label'); 

                if ($cek_kontak) :
                    for ($i = 1; $i <= 6; $i++) :
                        $logo_url = get_field('kontak_' . $i . '_logo');
                        $label = get_field('kontak_' . $i . '_label');
                        $deskripsi = get_field('kontak_' . $i . '_deskripsi');
                        $action_url = get_field('kontak_' . $i . '_url');

                        if ($action_url && filter_var($action_url, FILTER_VALIDATE_EMAIL)) {
                            $action_url = 'mailto:' . $action_url;
                        }

                        if ($label) : 
                            if ($action_url) :
                ?>
                                <a href="<?php echo esc_url($action_url); ?>" target="_blank" class="kontak-card" style="background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); display: flex; align-items: center; text-decoration: none; color: inherit; transition: transform 0.3s ease, box-shadow 0.3s ease; border: 1px solid #eaeaea;">
                                    
                                    <div style="width: 60px; height: 60px; background: rgba(215,70,144,0.08); border-radius: 50%; display: flex; justify-content: center; align-items: center; margin-right: 20px; overflow: hidden; flex-shrink: 0;">
                                        <?php if ($logo_url) : ?>
                                            <img src="<?php echo esc_url($logo_url); ?>" style="width: 55%; height: 55%; object-fit: contain;">
                                        <?php else: ?>
                                            <span style="color: #D74690; font-weight: bold; font-size: 20px;">#</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <div style="font-weight: 700; color: #D74690; font-size: 18px; margin-bottom: 5px;"><?php echo esc_html($label); ?></div>
                                        <div style="font-size: 15px; color: #666; line-height: 1.5;"><?php echo esc_html($deskripsi); ?></div>
                                    </div>
                                    
                                </a>
                <?php       else : ?>
                                <div class="kontak-card" style="background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); display: flex; align-items: center; text-decoration: none; color: inherit; transition: transform 0.3s ease, box-shadow 0.3s ease; border: 1px solid #eaeaea;">
                                    
                                    <div style="width: 60px; height: 60px; background: rgba(215,70,144,0.08); border-radius: 50%; display: flex; justify-content: center; align-items: center; margin-right: 20px; overflow: hidden; flex-shrink: 0;">
                                        <?php if ($logo_url) : ?>
                                            <img src="<?php echo esc_url($logo_url); ?>" style="width: 55%; height: 55%; object-fit: contain;">
                                        <?php else: ?>
                                            <span style="color: #D74690; font-weight: bold; font-size: 20px;">#</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <div style="font-weight: 700; color: #D74690; font-size: 18px; margin-bottom: 5px;"><?php echo esc_html($label); ?></div>
                                        <div style="font-size: 15px; color: #666; line-height: 1.5;"><?php echo esc_html($deskripsi); ?></div>
                                    </div>
                                    
                                </div>
                <?php 
                            endif;
                        endif;
                    endfor;

                else : 
                ?>
                    <a href="#" class="kontak-card" style="background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); display: flex; align-items: center; text-decoration: none; color: inherit; border: 1px solid #eaeaea;">
                        <div style="width: 60px; height: 60px; background: rgba(215,70,144,0.08); color: #D74690; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin-right: 20px; font-weight: bold; font-size: 16px;">WA</div>
                        <div><div style="font-weight: 700; color: #D74690; font-size: 18px; margin-bottom: 5px;">WhatsApp IKAPSI</div><div style="font-size: 15px; color: #666;">Silakan isi melalui ACF Beranda</div></div>
                    </a>
                    <a href="#" class="kontak-card" style="background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); display: flex; align-items: center; text-decoration: none; color: inherit; border: 1px solid #eaeaea;">
                        <div style="width: 60px; height: 60px; background: rgba(215,70,144,0.08); color: #D74690; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin-right: 20px; font-weight: bold; font-size: 16px;">IG</div>
                        <div><div style="font-weight: 700; color: #D74690; font-size: 18px; margin-bottom: 5px;">Instagram</div><div style="font-size: 15px; color: #666;">Silakan isi melalui ACF Beranda</div></div>
                    </a>
                    <a href="#" class="kontak-card" style="background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); display: flex; align-items: center; text-decoration: none; color: inherit; border: 1px solid #eaeaea;">
                        <div style="width: 60px; height: 60px; background: rgba(215,70,144,0.08); color: #D74690; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin-right: 20px; font-weight: bold; font-size: 16px;">EM</div>
                        <div><div style="font-weight: 700; color: #D74690; font-size: 18px; margin-bottom: 5px;">Email</div><div style="font-size: 15px; color: #666;">Silakan isi melalui ACF Beranda</div></div>
                    </a>
                <?php endif; ?>
                
            </div>
        </div>
    </section>

</main>

<script>
    const swiper = new Swiper('.hero-swiper', {
        loop: true,
        speed: 1000, 
        autoplay: { delay: 3500, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true },
        effect: 'fade',
        fadeEffect: { crossFade: true }
    });
</script>

<?php get_footer(); ?>
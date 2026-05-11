<?php
/**
 * Template Name: Front Page
 * Description: Template khusus untuk halaman beranda IKAPSI UNDIP.
 */

get_header(); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<style>
    /* Slider Styling - OPSI 1 (Tag IMG Murni dengan Rasio 21:7) */
    .hero-swiper { 
        width: 100%; 
        position: relative; 
    }
    
    .swiper-slide { 
        position: relative;
        display: block; 
    }

    .hero-img-element {
        width: 100%;
        height: auto;
        display: block;
        aspect-ratio: 21 / 7; /* Kunci rasio sesuai resolusi 1700x565 */
        object-fit: cover; 
    }
    
    .swiper-pagination-bullet-active { background: #D74690 !important; }
    .swiper-pagination-bullet { background: #fff; opacity: 1; width: 12px; height: 12px; }
    
    .slide-overlay { 
        position: absolute; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        background: linear-gradient(to bottom, rgba(0,0,0,0) 60%, rgba(0,0,0,0.3) 100%); 
        pointer-events: none; 
        z-index: 2;
    }
    
    /* Video Container Responsive */
    .video-responsive {
        position: relative;
        padding-bottom: 56.25%; /* Ratio 16:9 */
        height: 0;
        overflow: hidden;
        border-radius: 8px;
        background: #000;
    }
    .video-responsive iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    /* Penyesuaian khusus untuk HP (Mobile) */
    @media (max-width: 768px) {
        .hero-img-element {
            aspect-ratio: 16 / 9; 
        }
        .join-button-wrapper {
            right: 50% !important;
            transform: translateX(50%); 
            bottom: -20px !important;
        }
        .section-header {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 10px;
        }
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
                    if ($img_url) : 
                        $has_slides = true;
                ?>
                    <div class="swiper-slide">
                        <img src="<?php echo esc_url($img_url); ?>" alt="Banner Hero IKAPSI UNDIP" class="hero-img-element">
                        <div class="slide-overlay"></div>
                    </div>
                <?php 
                    endif;
                } 
                if (!$has_slides) : ?>
                    <div class="swiper-slide">
                        <div class="hero-img-element" style="background-color: #e0e0e0; display:flex; align-items:center; justify-content:center;">
                            <p style="color: #666; margin:0;">Upload gambar hero (Rekomendasi 1700x565 px) di ACF Beranda</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="swiper-pagination" style="bottom: 30px; z-index: 10;"></div>
        </div>
        
        <div class="join-button-wrapper" style="position: absolute; right: 8%; bottom: -25px; z-index: 99;">
            <?php if ( is_user_logged_in() ) : ?>
                <a href="<?php echo esc_url( home_url( '/?go_to_member=1' ) ); ?>" class="btn-join" style="display: inline-block; background-color: #D74690; color: #ffffff; padding: 18px 40px; text-decoration: none; font-weight: bold; font-size: 16px; border-radius: 4px; box-shadow: 0 4px 15px rgba(215,70,144,0.4);">MEMBER AREA</a>
            <?php else : ?>
                <?php 
                // Menambahkan parameter redirect agar setelah login langsung dilempar ke SSO Laravel
                $login_url = home_url( '/login/' );
                $redirect_url = urlencode( home_url( '/?go_to_member=1' ) );
                $final_login_url = add_query_arg( 'redirect_to', $redirect_url, $login_url );
                ?>
                <a href="<?php echo esc_url( $final_login_url ); ?>" class="btn-join" style="display: inline-block; background-color: #D74690; color: #ffffff; padding: 18px 40px; text-decoration: none; font-weight: bold; font-size: 16px; border-radius: 4px; box-shadow: 0 4px 15px rgba(215,70,144,0.4);">JOIN NOW</a>
            <?php endif; ?>
        </div>
    </section>

    <section class="info-terbaru-section" style="padding: 100px 0 40px;">
        <div class="container" style="max-width: 1350px; margin: 0 auto; padding: 0 20px;">
            
            <div class="section-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px;">
                <h2 style="color: #D74690; margin: 0; font-size: 24px; font-weight: 600;">Info Terbaru</h2>
                <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" style="color: #D74690; font-size: 15px; font-weight: 600; text-decoration: none;">Info Lainnya >></a>
            </div>

            <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                <?php
                $query_info = new WP_Query(array('post_type' => 'post', 'posts_per_page' => 3));
                if ($query_info->have_posts()) : while ($query_info->have_posts()) : $query_info->the_post(); ?>
                    <div class="card" style="border: 1px solid #eaeaea; border-radius: 4px; overflow: hidden; background: #fff;">
                        <div class="thumbnail" style="height: 220px;"><?php if (has_post_thumbnail()) the_post_thumbnail('medium', array('style' => 'width:100%; height:100%; object-fit:cover;')); ?></div>
                        <div class="content" style="padding: 25px 20px;">
                            <h3 style="font-size: 18px; color: #D74690; margin: 0 0 15px 0; font-weight: 600;"><?php the_title(); ?></h3>
                            <p style="font-size: 14px; color: #555; margin-bottom: 25px; line-height: 1.6;"><?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?></p>
                            <div style="display: flex; justify-content: space-between; font-size: 13px; color: #888;">
                                <span><?php echo get_the_date('d M Y'); ?></span>
                                <a href="<?php the_permalink(); ?>" style="color: #D74690; text-decoration: none; font-weight: 600;">Selengkapnya >></a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); endif; ?>
            </div>
        </div>
    </section>

    <section class="event-section" style="padding: 40px 0 60px;">
        <div class="container" style="max-width: 1350px; margin: 0 auto; padding: 0 20px;">
            
            <div class="section-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px;">
                <h2 style="color: #D74690; margin: 0; font-size: 24px; font-weight: 600;">Event dan Pelatihan</h2>
                <a href="https://ikapsiundip.or.id/daftar-event/" style="color: #D74690; font-size: 15px; font-weight: 600; text-decoration: none;">Event Lainnya >></a>
            </div>

            <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
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
                    <div class="card" style="border: 1px solid #eaeaea; position: relative; background: #fff;">
                        <div class="date-badge" style="position: absolute; top: 15px; left: 15px; background: #fff; text-align: center; z-index: 2; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <span style="font-size: 26px; font-weight: 700; display: block; padding: 5px 10px 0;"><?php echo $event_day; ?></span>
                            <span style="font-size: 11px; background: #D74690; color: #fff; padding: 4px 15px; display: block; margin-top: 5px; font-weight: 600;"><?php echo strtoupper($event_month_year); ?></span>
                        </div>
                        <div class="thumbnail" style="height: 220px;"><?php if (has_post_thumbnail()) the_post_thumbnail('medium', array('style' => 'width:100%; height:100%; object-fit:cover;')); ?></div>
                        
                        <div class="content" style="padding: 25px 20px;">
                            <h3 style="font-size: 18px; color: #D74690; margin: 0 0 15px 0; font-weight: 600;"><?php the_title(); ?></h3>
                            <p style="font-size: 14px; color: #555; margin-bottom: 25px; line-height: 1.6;"><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p>
                            <a href="<?php the_permalink(); ?>" style="display: block; text-align: right; color: #D74690; text-decoration: none; font-size: 13px;">Selengkapnya >></a>
                        </div>
                        
                    </div>
                <?php endwhile; wp_reset_postdata(); endif; ?>
            </div>
        </div>
    </section>

    <section class="galeri-section" style="padding: 20px 0 60px;">
        <div class="container" style="max-width: 1350px; margin: 0 auto; padding: 0 20px;">
            <h2 style="color: #D74690; margin-bottom: 40px; font-size: 24px; font-weight: 600;">Galeri Foto/Video</h2>
            <div class="galeri-plugin-wrapper">
                <?php echo do_shortcode('[ngg src="galleries" ids="1" display="basic_thumbnail" thumbnail_crop="0"]'); ?>
            </div>
        </div>
    </section>

    <section class="profil-section" style="background-color: #0b1c4c; color: #ffffff; padding: 80px 0;">
        <div class="container" style="max-width: 1350px; margin: 0 auto; padding: 0 20px;">
            <div class="row" style="display: flex; gap: 60px; align-items: center; flex-wrap: wrap;">
                
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
                    <div style="margin-bottom: 25px;">
                        <img src="<?php echo esc_url($profil_logo); ?>" alt="Profil IKAPSI UNDIP" style="width: 100%; max-width: 380px; height: auto; object-fit: contain;">
                    </div>
                    <div style="font-size: 15px; line-height: 1.8; color: #d0d5e0;">
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
                        <div style="background-color: #1a2a5a; height: 350px; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                            <p style="color: #666;">Input Embed YouTube di ACF Beranda</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

    <section class="hubungi-section" style="padding: 80px 0; background-color: #f0f2f5;">
        <div class="container" style="max-width: 1350px; margin: 0 auto; padding: 0 20px;">
            <h2 style="color: #D74690; margin-bottom: 40px; font-size: 24px; font-weight: 600;">Hubungi IKAPSI UNDIP</h2>
            <div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                
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
                                <a href="<?php echo esc_url($action_url); ?>" target="_blank" class="kontak-card" style="background: #ffffff; padding: 25px; border-radius: 6px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; align-items: center; text-decoration: none; color: inherit; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                    
                                    <div style="width: 50px; height: 50px; background: rgba(215,70,144,0.1); border-radius: 50%; display: flex; justify-content: center; align-items: center; margin-right: 15px; overflow: hidden; flex-shrink: 0;">
                                        <?php if ($logo_url) : ?>
                                            <img src="<?php echo esc_url($logo_url); ?>" style="width: 60%; height: 60%; object-fit: contain;">
                                        <?php else: ?>
                                            <span style="color: #D74690; font-weight: bold;">#</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <div style="font-weight: 600; color: #D74690;"><?php echo esc_html($label); ?></div>
                                        <div style="font-size: 14px; color: #666; margin-top: 3px;"><?php echo esc_html($deskripsi); ?></div>
                                    </div>
                                    
                                </a>
                <?php       else : ?>
                                <div class="kontak-card" style="background: #ffffff; padding: 25px; border-radius: 6px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; align-items: center; text-decoration: none; color: inherit; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                    
                                    <div style="width: 50px; height: 50px; background: rgba(215,70,144,0.1); border-radius: 50%; display: flex; justify-content: center; align-items: center; margin-right: 15px; overflow: hidden; flex-shrink: 0;">
                                        <?php if ($logo_url) : ?>
                                            <img src="<?php echo esc_url($logo_url); ?>" style="width: 60%; height: 60%; object-fit: contain;">
                                        <?php else: ?>
                                            <span style="color: #D74690; font-weight: bold;">#</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <div style="font-weight: 600; color: #D74690;"><?php echo esc_html($label); ?></div>
                                        <div style="font-size: 14px; color: #666; margin-top: 3px;"><?php echo esc_html($deskripsi); ?></div>
                                    </div>
                                    
                                </div>
                <?php 
                            endif;
                        endif;
                    endfor;

                else : 
                ?>
                    <a href="#" class="kontak-card" style="background: #ffffff; padding: 25px; border-radius: 6px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; align-items: center; text-decoration: none; color: inherit;">
                        <div style="width: 50px; height: 50px; background: rgba(215,70,144,0.1); color: #D74690; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin-right: 15px; font-weight: bold;">WA</div>
                        <div><div style="font-weight: 600; color: #D74690;">WhatsApp IKAPSI</div><div style="font-size: 14px; color: #666;">Silakan isi melalui ACF Beranda</div></div>
                    </a>
                    <a href="#" class="kontak-card" style="background: #ffffff; padding: 25px; border-radius: 6px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; align-items: center; text-decoration: none; color: inherit;">
                        <div style="width: 50px; height: 50px; background: rgba(215,70,144,0.1); color: #D74690; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin-right: 15px; font-weight: bold;">IG</div>
                        <div><div style="font-weight: 600; color: #D74690;">Instagram</div><div style="font-size: 14px; color: #666;">Silakan isi melalui ACF Beranda</div></div>
                    </a>
                    <a href="#" class="kontak-card" style="background: #ffffff; padding: 25px; border-radius: 6px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; align-items: center; text-decoration: none; color: inherit;">
                        <div style="width: 50px; height: 50px; background: rgba(215,70,144,0.1); color: #D74690; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin-right: 15px; font-weight: bold;">EM</div>
                        <div><div style="font-weight: 600; color: #D74690;">Email</div><div style="font-size: 14px; color: #666;">Silakan isi melalui ACF Beranda</div></div>
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
        autoplay: { delay: 3000, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true },
        effect: 'fade',
        fadeEffect: { crossFade: true }
    });
</script>

<?php get_footer(); ?>
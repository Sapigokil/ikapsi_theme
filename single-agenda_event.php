<?php
/**
 * Template Name: Single Agenda Event
 * Description: Template khusus untuk menampilkan detail agenda dan event.
 */

get_header('internal'); ?>

<style>
    /* Styling Global Single Event */
    body { background-color: #fdfdfd; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #444; }
    
    .event-header-bg {
        width: 100%;
        height: 45vh;
        min-height: 350px;
        background-color: #4A0A1F;
        background-image: linear-gradient(135deg, #4A0A1F 0%, #D74690 100%);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 0 20px;
    }

    .event-header-bg::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.3);
    }

    .event-header-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
    }

    .event-badge-top {
        display: inline-block;
        background-color: #fff;
        color: #D74690;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 20px;
    }

    .event-title-main {
        color: #fff;
        font-size: 46px;
        font-family: 'Georgia', serif;
        font-weight: bold;
        line-height: 1.2;
        margin: 0 0 15px 0;
        text-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    .event-container {
        max-width: 900px;
        margin: -60px auto 100px;
        padding: 0 20px;
        position: relative;
        z-index: 10;
    }

    .event-card-main {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    /* Kotak Info Highlight (Tanggal dll) */
    .event-highlight-bar {
        display: flex;
        flex-wrap: wrap;
        background-color: #fcfcfc;
        border-bottom: 1px solid #eaeaea;
    }

    .event-highlight-item {
        flex: 1;
        min-width: 250px;
        padding: 25px 30px;
        display: flex;
        align-items: center;
        gap: 15px;
        border-right: 1px solid #eaeaea;
    }

    .event-highlight-item:last-child {
        border-right: none;
    }

    .e-icon {
        width: 45px;
        height: 45px;
        background: rgba(215,70,144,0.1);
        color: #D74690;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 20px;
    }

    .e-label {
        font-size: 12px;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 3px;
        font-weight: 600;
    }

    .e-value {
        font-size: 16px;
        color: #4A0A1F;
        font-weight: bold;
    }

    /* Konten Artikel Event */
    .event-body-content {
        padding: 40px 50px;
    }

    .event-thumbnail-in {
        width: 100%;
        max-height: 450px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    .event-content {
        font-size: 16px;
        line-height: 1.8;
        color: #333;
    }

    .event-content h1, 
    .event-content h2, 
    .event-content h3 {
        color: #4A0A1F;
        font-family: 'Georgia', serif;
        margin-top: 35px;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .event-content p { margin-bottom: 20px; }
    
    .event-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 20px 0;
    }

    .event-content a {
        color: #D74690;
        text-decoration: none;
        font-weight: 600;
    }
    
    .event-content a:hover { text-decoration: underline; }

    /* Footer Action */
    .event-action-box {
        background-color: #fffafc;
        border-top: 1px solid #f2e1e8;
        padding: 30px 50px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .btn-daftar {
        background-color: #D74690;
        color: #fff;
        padding: 14px 35px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: bold;
        font-size: 15px;
        transition: 0.3s;
        box-shadow: 0 4px 15px rgba(215,70,144,0.3);
    }

    .btn-daftar:hover {
        background-color: #bf3a7d;
        color: #fff;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .event-title-main { font-size: 32px; }
        .event-body-content, .event-action-box { padding: 30px 20px; }
        .event-highlight-item { border-right: none; border-bottom: 1px solid #eaeaea; }
        .event-action-box { justify-content: center; text-align: center; }
    }
</style>

<main id="primary" class="site-main">
    <?php
    while ( have_posts() ) :
        the_post();

        // Mengambil data Tanggal dari ACF yang sudah ada
        $acf_date = get_field('tanggal_event');
        $display_date = '';
        
        if( $acf_date ) {
            $date_obj = DateTime::createFromFormat('d-m-Y', $acf_date);
            if ($date_obj) {
                $display_date = $date_obj->format('d F Y');
            } else {
                $display_date = $acf_date;
            }
        } else {
            // Fallback jika tidak diisi
            $display_date = 'Tanggal belum ditentukan';
        }
        ?>

        <article id="post-<?php the_ID(); ?>">

            <!-- Header Gradient -->
            <div class="event-header-bg">
                <div class="event-header-content">
                    <span class="event-badge-top">AGENDA EVENT</span>
                    <h1 class="event-title-main"><?php the_title(); ?></h1>
                </div>
            </div>

            <!-- Kontainer Utama -->
            <div class="event-container">
                <div class="event-card-main">
                    
                    <!-- Bar Informasi Cepat -->
                    <div class="event-highlight-bar">
                        <div class="event-highlight-item">
                            <div class="e-icon">📅</div>
                            <div>
                                <div class="e-label">Tanggal Pelaksanaan</div>
                                <div class="e-value"><?php echo esc_html($display_date); ?></div>
                            </div>
                        </div>
                        
                        <div class="event-highlight-item">
                            <div class="e-icon">📍</div>
                            <div>
                                <div class="e-label">Lokasi / Keterangan</div>
                                <div class="e-value">Cek detail pada deskripsi</div>
                            </div>
                        </div>
                    </div>

                    <!-- Area Konten Utama -->
                    <div class="event-body-content">
                        <?php 
                        // Menampilkan Featured Image di dalam konten jika ada
                        if ( has_post_thumbnail() ) {
                            the_post_thumbnail( 'large', array( 'class' => 'event-thumbnail-in' ) );
                        }
                        ?>

                        <div class="event-content">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <!-- Area Tombol Aksi -->
                    <div class="event-action-box">
                        <div>
                            <div style="font-size: 18px; font-weight: bold; color: #4A0A1F; margin-bottom: 5px;">Tertarik untuk bergabung?</div>
                            <div style="font-size: 14px; color: #666;">Segera daftarkan diri Anda sebelum kuota penuh.</div>
                        </div>
                        <div>
                            <a href="https://wa.me/628212633200" target="_blank" class="btn-daftar">Hubungi Panitia / Daftar</a>
                        </div>
                    </div>

                </div>
            </div>

        </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
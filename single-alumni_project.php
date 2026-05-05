<?php
/**
 * Template Name: Single Alumni Project
 * Description: Template khusus untuk menampilkan detail program kerja / project alumni.
 */

get_header('internal'); ?>

<style>
    /* Styling Global Single Project */
    body { background-color: #fdfdfd; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #444; }
    
    .project-header-img {
        width: 100%;
        height: 60vh;
        min-height: 400px;
        max-height: 600px;
        background-color: #1a1a1a;
        position: relative;
        overflow: hidden;
    }

    .project-header-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.6; /* Dibuat sedikit gelap agar teks di atasnya jika ada terbaca */
    }

    .project-container {
        max-width: 900px;
        margin: -100px auto 100px; /* Menarik konten ke atas agar menimpa gambar */
        padding: 0 20px;
        position: relative;
        z-index: 10;
    }

    .project-card-main {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.08);
        padding: 50px;
        border-top: 5px solid #D74690;
    }

    .project-badge-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .project-status {
        background-color: rgba(215,70,144,0.1);
        color: #D74690;
        padding: 6px 20px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        border: 1px solid rgba(215,70,144,0.2);
    }

    .project-back-link {
        color: #666;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: 0.3s;
    }

    .project-back-link:hover {
        color: #D74690;
    }

    .project-title {
        font-size: 42px;
        color: #4A0A1F;
        font-family: 'Georgia', serif;
        font-weight: bold;
        line-height: 1.2;
        margin-bottom: 15px;
    }

    .project-meta {
        font-size: 15px;
        color: #888;
        padding-bottom: 30px;
        margin-bottom: 30px;
        border-bottom: 1px solid #eaeaea;
    }

    /* Penyesuaian Tipografi di dalam Konten Project */
    .project-content {
        font-size: 17px;
        line-height: 1.8;
        color: #333;
    }

    .project-content h1, 
    .project-content h2, 
    .project-content h3 {
        color: #4A0A1F;
        font-family: 'Georgia', serif;
        margin-top: 40px;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .project-content h2 { font-size: 28px; }
    .project-content h3 { font-size: 22px; }
    .project-content p { margin-bottom: 25px; }
    
    .project-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 20px 0;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }

    .project-content ul, 
    .project-content ol {
        margin-bottom: 25px;
        padding-left: 20px;
    }

    .project-content li { margin-bottom: 10px; }

    .project-content blockquote {
        border-left: 5px solid #D74690;
        margin: 30px 0;
        padding: 20px 30px;
        background-color: rgba(215,70,144,0.03);
        font-style: italic;
        font-size: 18px;
        color: #666;
        border-radius: 0 8px 8px 0;
    }

    /* Box Info Ekstra (Bisa dikembangkan nanti via ACF) */
    .project-info-box {
        background-color: #f9f9f9;
        border-radius: 12px;
        padding: 25px;
        margin-top: 40px;
        border: 1px solid #eaeaea;
    }
    
    .info-box-title {
        font-size: 16px;
        font-weight: bold;
        color: #4A0A1F;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    @media (max-width: 768px) {
        .project-title { font-size: 32px; }
        .project-card-main { padding: 30px 20px; }
        .project-header-img { height: 40vh; min-height: 250px; }
        .project-container { margin-top: -50px; }
    }
</style>

<main id="primary" class="site-main">
    <?php
    while ( have_posts() ) :
        the_post();

        // Ambil status dari ACF, fallback 'ACTIVE' jika kosong
        $status = get_field('status_project');
        if (!$status) { $status = 'ACTIVE'; }
        ?>

        <article id="post-<?php the_ID(); ?>">

            <!-- Header Gambar Besar -->
            <div class="project-header-img">
                <?php 
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail( 'full' );
                } else {
                    // Gambar default jika project tidak punya gambar
                    echo '<img src="' . get_template_directory_uri() . '/images/dummy-office.jpg" alt="Default Project Image">';
                }
                ?>
            </div>

            <!-- Konten Utama Project -->
            <div class="project-container">
                <div class="project-card-main">
                    
                    <div class="project-badge-wrapper">
                        <!-- Tombol Kembali (Bisa diarahkan ke halaman landing page project) -->
                        <a href="javascript:history.back()" class="project-back-link">
                            ← Kembali
                        </a>
                        <!-- Badge Status -->
                        <div class="project-status"><?php echo esc_html($status); ?></div>
                    </div>

                    <h1 class="project-title"><?php the_title(); ?></h1>
                    
                    <div class="project-meta">
                        Dipublikasikan pada: <?php echo get_the_date('d F Y'); ?>
                    </div>

                    <div class="project-content">
                        <?php 
                        // Menampilkan isi teks/detail project
                        the_content(); 
                        ?>
                    </div>

                    <!-- Kotak Informasi Ekstra (Opsional) -->
                    <div class="project-info-box">
                        <div class="info-box-title">Butuh Informasi Lebih Lanjut?</div>
                        <p style="font-size: 14px; color: #666; margin-bottom: 15px;">Jika Anda memiliki pertanyaan mengenai program kerja atau project ini, silakan hubungi tim kami untuk mendiskusikan peluang kolaborasi.</p>
                        <a href="#" style="background-color: #D74690; color: #fff; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: bold; display: inline-block;">Hubungi Kami</a>
                    </div>

                </div>
            </div>

        </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
<?php
/**
 * Template Name: Single Article
 * Description: Template khusus untuk menampilkan detail artikel/berita (Full-width centered).
 */

// Memanggil header (menggunakan header internal agar seragam dengan halaman dalam)
get_header('internal'); ?>

<style>
    /* Styling Global Single Post */
    body { background-color: #fdfdfd; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #444; }
    
    .single-container {
        max-width: 900px; /* Lebar maksimal konten agar terpusat dan nyaman dibaca */
        margin: 60px auto 100px;
        padding: 0 20px;
    }

    .single-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .single-category {
        display: inline-block;
        background-color: rgba(215,70,144,0.1);
        color: #D74690;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
        text-decoration: none;
        transition: 0.3s;
    }
    
    .single-category:hover {
        background-color: #D74690;
        color: #fff;
    }

    .single-title {
        font-size: 42px;
        color: #4A0A1F;
        font-family: 'Georgia', serif;
        font-weight: bold;
        line-height: 1.3;
        margin-bottom: 20px;
    }

    .single-meta {
        font-size: 14px;
        color: #888;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
    }

    .single-thumbnail {
        width: 100%;
        height: auto;
        max-height: 550px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 50px;
    }

    /* Penyesuaian Tipografi di dalam Konten Artikel */
    .single-content {
        font-size: 17px;
        line-height: 1.8;
        color: #333;
    }

    .single-content h1, 
    .single-content h2, 
    .single-content h3 {
        color: #4A0A1F;
        font-family: 'Georgia', serif;
        margin-top: 40px;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .single-content h2 { font-size: 28px; }
    .single-content h3 { font-size: 22px; }

    .single-content p {
        margin-bottom: 25px;
    }

    .single-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 20px 0;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }

    .single-content a {
        color: #D74690;
        text-decoration: none;
        font-weight: 600;
    }

    .single-content a:hover {
        text-decoration: underline;
    }

    .single-content ul, 
    .single-content ol {
        margin-bottom: 25px;
        padding-left: 20px;
    }

    .single-content li {
        margin-bottom: 10px;
    }

    .single-content blockquote {
        border-left: 5px solid #D74690;
        margin: 30px 0;
        padding: 20px 30px;
        background-color: rgba(215,70,144,0.03);
        font-style: italic;
        font-size: 18px;
        color: #666;
        border-radius: 0 8px 8px 0;
    }

    /* Navigasi Artikel Selanjutnya/Sebelumnya */
    .single-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 60px;
        padding-top: 30px;
        border-top: 1px solid #eaeaea;
    }

    .nav-links {
        width: 100%;
        display: flex;
        justify-content: space-between;
    }

    .nav-previous, .nav-next {
        max-width: 45%;
    }

    .nav-subtitle {
        font-size: 12px;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: block;
        margin-bottom: 5px;
    }

    .nav-title {
        font-size: 16px;
        color: #4A0A1F;
        font-weight: bold;
        text-decoration: none;
        transition: color 0.3s;
        line-height: 1.4;
        display: block;
    }

    .nav-title:hover {
        color: #D74690;
    }

    @media (max-width: 768px) {
        .single-title { font-size: 32px; }
        .single-navigation { flex-direction: column; gap: 20px; text-align: center; }
        .nav-links { flex-direction: column; gap: 20px; }
        .nav-previous, .nav-next { max-width: 100%; text-align: center !important; }
    }
</style>

<main id="primary" class="site-main">
    <?php
    // Memulai sistem Loop bawaan WordPress
    while ( have_posts() ) :
        the_post();
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('single-container'); ?>>

            <header class="single-header">
                <?php 
                // Menampilkan Kategori pertama dari artikel
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                    echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" class="single-category">' . esc_html( $categories[0]->name ) . '</a>';
                }
                ?>

                <h1 class="single-title"><?php the_title(); ?></h1>

                <div class="single-meta">
                    <span class="meta-date">📅 <?php echo get_the_date('d F Y'); ?></span>
                    <span class="meta-author">👤 Oleh <?php the_author(); ?></span>
                </div>
            </header>

            <?php 
            // Menampilkan Gambar Andalan (Featured Image)
            if ( has_post_thumbnail() ) : 
            ?>
                <div class="single-thumbnail-wrapper">
                    <?php the_post_thumbnail( 'full', array( 'class' => 'single-thumbnail' ) ); ?>
                </div>
            <?php endif; ?>

            <div class="single-content">
                <?php 
                // Menampilkan isi teks/artikel utama yang ditulis di Gutenberg/Classic Editor
                the_content(); 
                ?>
            </div>

            <div class="single-navigation">
                <div class="nav-links">
                    <div class="nav-previous">
                        <?php 
                        // Link ke artikel sebelumnya
                        $prev_post = get_previous_post();
                        if (!empty( $prev_post )): ?>
                            <span class="nav-subtitle">← Artikel Sebelumnya</span>
                            <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="nav-title"><?php echo esc_html( $prev_post->post_title ); ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="nav-next" style="text-align: right;">
                        <?php 
                        // Link ke artikel selanjutnya
                        $next_post = get_next_post();
                        if (!empty( $next_post )): ?>
                            <span class="nav-subtitle">Artikel Selanjutnya →</span>
                            <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="nav-title"><?php echo esc_html( $next_post->post_title ); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </article>

    <?php endwhile; // Selesai Loop ?>
</main>

<!-- Memanggil Footer -->
<?php get_footer(); ?>
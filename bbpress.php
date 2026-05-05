<?php
/**
 * Template Name: bbPress Forum Wrapper
 * Description: Template khusus untuk membungkus tampilan forum bbPress agar menggunakan header internal dan bergaya modern.
 */

get_header('internal'); ?>

<style>
    /* Styling Dasar Halaman Forum */
    body { background-color: #fafafa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    
    .forum-container { 
        max-width: 1200px; 
        margin: 40px auto 80px; 
        padding: 0 20px; 
    }
    
    .forum-header { 
        text-align: center; 
        margin-bottom: 50px; 
        padding-bottom: 30px; 
        border-bottom: 1px solid #eaeaea;
    }
    .forum-header h1 { 
        font-size: 42px; 
        color: #2d2424; 
        margin-bottom: 15px; 
        font-weight: bold; 
        font-family: Georgia, serif; 
    }
    .forum-header h1 span { color: #D74690; font-style: italic; }
    .forum-header p { color: #666; font-size: 16px; max-width: 600px; margin: 0 auto; line-height: 1.6;}
    
    /* ==========================================================================
       CSS OVERRIDE UNTUK BBPRESS (MENGUBAH TAMPILAN KAKU MENJADI MODERN)
       ========================================================================== */
    
    /* Reset & Ukuran Font Dasar */
    #bbpress-forums { font-size: 14px; color: #444; }

    /* Membuang border bawaan dan membuat kotak melayang ala Card */
    #bbpress-forums ul.bbp-lead-topic, 
    #bbpress-forums ul.bbp-topics, 
    #bbpress-forums ul.bbp-forums, 
    #bbpress-forums ul.bbp-replies, 
    #bbpress-forums ul.bbp-search-results {
        border: none !important;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        background: #fff;
        margin-bottom: 30px !important;
        overflow: hidden;
    }

    /* Baris Judul Kolom (Header Tabel Forum) */
    #bbpress-forums li.bbp-header {
        background-color: #fdfdfd !important; 
        border-bottom: 1px solid #eaeaea !important;
        padding: 18px 25px !important;
    }
    #bbpress-forums li.bbp-header ul li {
        color: #888 !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 1.5px;
    }

    /* Baris Isi Forum / Topik (Hover Effect) */
    #bbpress-forums li.bbp-body ul.forum, 
    #bbpress-forums li.bbp-body ul.topic {
        padding: 25px !important;
        border-bottom: 1px solid #f5f5f5 !important;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }
    #bbpress-forums li.bbp-body ul.forum:hover, 
    #bbpress-forums li.bbp-body ul.topic:hover {
        background-color: rgba(215,70,144,0.02) !important;
    }
    #bbpress-forums li.bbp-body ul.forum:last-child, 
    #bbpress-forums li.bbp-body ul.topic:last-child {
        border-bottom: none !important;
    }

    /* Tautan Judul Forum & Topik */
    #bbpress-forums a.bbp-forum-title, 
    #bbpress-forums a.bbp-topic-permalink {
        font-size: 18px;
        font-weight: 700;
        color: #2d2424 !important;
        text-decoration: none;
        display: block;
        margin-bottom: 8px;
        transition: color 0.2s;
    }
    #bbpress-forums a.bbp-forum-title:hover, 
    #bbpress-forums a.bbp-topic-permalink:hover {
        color: #D74690 !important;
    }

    /* Teks Deskripsi di bawah Judul */
    #bbpress-forums div.bbp-forum-content {
        font-size: 13px;
        color: #777; 
        font-weight: 400;
        line-height: 1.5;
    }

    /* Angka Penghitung (Topic & Reply) */
    #bbpress-forums li.bbp-forum-topic-count, 
    #bbpress-forums li.bbp-topic-voice-count, 
    #bbpress-forums li.bbp-forum-reply-count, 
    #bbpress-forums li.bbp-topic-reply-count {
        font-size: 15px;
        font-weight: 700;
        color: #D74690;
        text-align: center;
    }

    /* Aktivitas Terakhir (Freshness) */
    #bbpress-forums li.bbp-forum-freshness, 
    #bbpress-forums li.bbp-topic-freshness {
        font-size: 12px;
        color: #999;
        text-align: center;
    }
    #bbpress-forums li.bbp-forum-freshness a, 
    #bbpress-forums li.bbp-topic-freshness a {
        color: #666 !important;
        text-decoration: none;
        font-weight: 600;
    }
    #bbpress-forums li.bbp-forum-freshness a:hover, 
    #bbpress-forums li.bbp-topic-freshness a:hover {
        color: #D74690 !important;
    }

    /* Foto Profil (Avatar) */
    #bbpress-forums img.avatar {
        border-radius: 50% !important;
        box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        border: 2px solid #fff;
        margin-bottom: 5px;
    }

    /* Jejak Navigasi (Breadcrumbs) */
    #bbpress-forums div.bbp-breadcrumb {
        float: none !important;
        margin-bottom: 25px !important;
        font-size: 12px !important;
        color: #999 !important;
        background: #fff;
        display: inline-block;
        padding: 10px 20px;
        border-radius: 30px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.03);
    }
    #bbpress-forums div.bbp-breadcrumb a {
        color: #D74690 !important;
        text-decoration: none;
        font-weight: 700;
    }

    /* Gaya Tombol (Subscribe, Favorite, Submit) */
    #bbpress-forums button, 
    #bbpress-forums a.button, 
    #bbpress-forums .submit {
        background-color: #D74690 !important;
        color: #ffffff !important;
        border-radius: 30px !important;
        border: none !important;
        padding: 10px 25px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 4px 15px rgba(215,70,144,0.3) !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    #bbpress-forums button:hover, 
    #bbpress-forums a.button:hover, 
    #bbpress-forums .submit:hover {
        background-color: #bf3a7d !important;
        box-shadow: 0 6px 20px rgba(215,70,144,0.4) !important;
        transform: translateY(-2px);
    }
    
    /* Kotak Peringatan/Notifikasi Bawaan */
    div.bbp-template-notice, div.indicator-hint {
        border: none !important;
        background-color: rgba(215,70,144,0.05) !important;
        color: #D74690 !important;
        border-radius: 8px !important;
        padding: 15px 20px !important;
        border-left: 4px solid #D74690 !important;
        font-weight: 600;
    }

    /* Kotak Pencarian Forum */
    #bbpress-forums div.bbp-search-form {
        margin-bottom: 30px;
        float: none;
        text-align: right;
    }
    #bbpress-forums div.bbp-search-form input[type="text"] {
        padding: 10px 20px;
        border-radius: 30px;
        border: 1px solid #eaeaea;
        outline: none;
        min-width: 250px;
    }
</style>

<main id="primary" class="site-main">
    <div class="forum-container">
        
        <div class="forum-header">
            <h1>Ruang <span>Diskusi</span></h1>
            <p>Wadah interaktif bertukar gagasan, bernostalgia, dan membangun jejaring antar sesama alumni IKAPSI UNDIP.</p>
        </div>
        
        <div class="forum-content">
            <?php
            // Memanggil inti sistem bbPress
            if ( have_posts() ) :
                while ( have_posts() ) :
                    the_post();
                    the_content();
                endwhile;
            endif;
            ?>
        </div>
        
    </div>
</main>

<?php get_footer(); ?>
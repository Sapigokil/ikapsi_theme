<?php
/**
 * Template Name: Halaman Member (UM Wrapper)
 * Description: Template khusus untuk membungkus form Ultimate Member (Login/Register/Profile) agar tampil rapi dan fokus.
 */

get_header('internal'); ?>

<style>
    body { 
        background-color: #f8f9fa; 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
    }
    
    .member-page-wrapper {
        max-width: 700px; /* Dibuat lebih sempit agar form pendaftaran tidak terlalu melar/panjang ke samping */
        margin: 60px auto 100px;
        padding: 0 20px;
    }

    .member-content-box {
        background-color: #ffffff;
        border-radius: 12px;
        border: 1px solid #eaeaea;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        padding: 40px;
        overflow: hidden;
    }

    /* Penyesuaian (Override) Gaya Bawaan Ultimate Member agar sesuai Tema IKAPSI */
    .um .um-form input[type=text], 
    .um .um-form input[type=search], 
    .um .um-form input[type=tel], 
    .um .um-form input[type=number], 
    .um .um-form input[type=password],
    .um .um-form input[type=email] {
        border-radius: 8px !important;
        padding: 12px 15px !important;
        border: 1px solid #ddd !important;
    }
    
    .um .um-form input:focus {
        border-color: #D74690 !important;
    }
    
    .um .um-button {
        background-color: #D74690 !important;
        border-radius: 30px !important;
        font-weight: bold !important;
        padding: 12px 25px !important;
        transition: all 0.3s ease !important;
    }
    
    .um .um-button:hover {
        background-color: #4A0A1F !important;
        box-shadow: 0 5px 15px rgba(215,70,144,0.3) !important;
    }

    .page-title-member {
        text-align: center;
        color: #4A0A1F;
        font-family: 'Georgia', serif;
        font-size: 32px;
        font-weight: bold;
        margin-top: 0;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eaeaea;
    }
    
    @media (max-width: 768px) {
        .member-content-box {
            padding: 25px 15px;
        }
        .page-title-member {
            font-size: 26px;
        }
    }
</style>

<main id="primary" class="site-main">
    <div class="member-page-wrapper">
        
        <div class="member-content-box">
            <?php
            // Memanggil konten dari WordPress (termasuk Shortcode Ultimate Member)
            if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    
                    // Menampilkan judul halaman (Misal: "Pendaftaran Alumni")
                    echo '<h1 class="page-title-member">' . get_the_title() . '</h1>';
                    
                    // Mengeksekusi shortcode form
                    the_content();
                    
                endwhile;
            endif;
            ?>
        </div>

    </div>
</main>

<?php get_footer(); ?>
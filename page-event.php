<?php
/**
 * Template Name: Daftar Event
 * Description: Template khusus untuk menampilkan semua daftar agenda dan event dengan paginasi.
 */

get_header('internal'); ?>

<style>
    /* Styling Global Halaman Daftar Event */
    body { background-color: #fdfdfd; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #444; }
    
    /* Header Halaman (Banner Sederhana) */
    .page-header-banner {
        background: linear-gradient(135deg, #4A0A1F 0%, #D74690 100%);
        padding: 80px 20px;
        text-align: center;
        color: #fff;
    }

    .page-header-banner h1 {
        font-size: 42px;
        font-family: 'Georgia', serif;
        margin: 0 0 10px 0;
        font-weight: bold;
    }

    .page-header-banner p {
        font-size: 16px;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Container Utama Grid */
    .event-archive-section {
        max-width: 1200px;
        margin: 60px auto 100px;
        padding: 0 20px;
    }

    .event-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
    }

    /* Styling Kartu Event (Sama dengan Beranda agar Konsisten) */
    .event-card {
        border: 1px solid #eaeaea; 
        border-radius: 8px;
        position: relative; 
        background: #fff;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(215,70,144,0.1);
    }

    .date-badge {
        position: absolute; 
        top: 15px; 
        left: 15px; 
        background: #fff; 
        text-align: center; 
        z-index: 2; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 4px;
        overflow: hidden;
    }

    .date-day {
        font-size: 26px; 
        font-weight: 700; 
        display: block; 
        padding: 8px 15px 0;
        color: #4A0A1F;
    }

    .date-month {
        font-size: 11px; 
        background: #D74690; 
        color: #fff; 
        padding: 5px 15px; 
        display: block; 
        margin-top: 5px; 
        font-weight: bold;
        letter-spacing: 1px;
    }

    .event-thumbnail {
        height: 220px;
        width: 100%;
        background-color: #f5f5f5;
    }

    .event-thumbnail img {
        width: 100%; 
        height: 100%; 
        object-fit: cover;
    }

    .event-content {
        padding: 30px 25px;
    }

    .event-content h3 {
        font-size: 20px; 
        color: #D74690; 
        margin: 0 0 15px 0; 
        font-weight: 700;
        line-height: 1.3;
    }

    .event-content p {
        font-size: 14px; 
        color: #666; 
        margin-bottom: 25px; 
        line-height: 1.6;
    }

    .event-link {
        display: inline-block; 
        color: #D74690; 
        text-decoration: none; 
        font-size: 14px;
        font-weight: bold;
        transition: 0.3s;
    }

    .event-link:hover {
        color: #4A0A1F;
    }

    /* Paginasi */
    .pagination-wrapper {
        margin-top: 60px;
        text-align: center;
    }

    .pagination-wrapper .page-numbers {
        display: inline-block;
        padding: 10px 18px;
        margin: 0 5px;
        background-color: #fff;
        border: 1px solid #eaeaea;
        color: #4A0A1F;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 600;
        transition: 0.3s;
    }

    .pagination-wrapper .page-numbers:hover {
        background-color: rgba(215,70,144,0.1);
        border-color: #D74690;
        color: #D74690;
    }

    .pagination-wrapper .page-numbers.current {
        background-color: #D74690;
        border-color: #D74690;
        color: #fff;
    }

    @media (max-width: 768px) {
        .page-header-banner { padding: 60px 20px; }
        .page-header-banner h1 { font-size: 32px; }
    }
</style>

<main id="primary" class="site-main">

    <!-- Banner Header -->
    <section class="page-header-banner">
        <h1>Agenda & Pelatihan</h1>
        <p>Ikuti berbagai kegiatan, seminar, dan pelatihan yang diselenggarakan oleh IKAPSI UNDIP untuk meningkatkan kapasitas dan memperluas jaringan Anda.</p>
    </section>

    <!-- Daftar Event Grid -->
    <section class="event-archive-section">
        
        <div class="event-grid">
            <?php
            // Setup paginasi
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
            
            // Query Custom Post Type 'agenda_event'
            $args = array(
                'post_type'      => 'agenda_event',
                'posts_per_page' => 9, // Menampilkan 9 event per halaman
                'paged'          => $paged,
                'post_status'    => 'publish'
            );
            $query_event_archive = new WP_Query($args);

            if ($query_event_archive->have_posts()) : 
                while ($query_event_archive->have_posts()) : $query_event_archive->the_post(); 
                    
                    // Ekstrak Tanggal dari ACF
                    $acf_date = get_field('tanggal_event');
                    if( $acf_date ) {
                        $date_obj = DateTime::createFromFormat('d-m-Y', $acf_date);
                        if($date_obj) {
                            $event_day = $date_obj->format('d');
                            $event_month_year = $date_obj->format('M Y');
                        } else {
                            $event_day = '-';
                            $event_month_year = $acf_date;
                        }
                    } else {
                        // Fallback jika lupa diisi
                        $event_day = get_the_date('d');
                        $event_month_year = get_the_date('M Y');
                    }
            ?>
                    
                    <div class="event-card">
                        <div class="date-badge">
                            <span class="date-day"><?php echo $event_day; ?></span>
                            <span class="date-month"><?php echo strtoupper($event_month_year); ?></span>
                        </div>
                        
                        <div class="event-thumbnail">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium'); ?>
                            <?php else: ?>
                                <!-- Gambar Kosong Default -->
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #ccc;">No Image</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="event-content">
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo wp_trim_words(get_the_excerpt(), 18, '...'); ?></p>
                            <div style="text-align: right;">
                                <a href="<?php the_permalink(); ?>" class="event-link">Lihat Detail ➔</a>
                            </div>
                        </div>
                    </div>

            <?php 
                endwhile; 
            ?>
        </div> <!-- End Grid -->

        <!-- Navigasi Paginasi -->
        <div class="pagination-wrapper">
            <?php 
            echo paginate_links( array(
                'total'        => $query_event_archive->max_num_pages,
                'current'      => max( 1, get_query_var( 'paged' ) ),
                'prev_text'    => '« Sebelumnya',
                'next_text'    => 'Selanjutnya »',
            ) ); 
            ?>
        </div>

        <?php
            wp_reset_postdata();
            else :
                echo '<div style="text-align: center; width: 100%; padding: 50px 0;"><h3 style="color: #999;">Belum ada jadwal event saat ini.</h3></div>';
            endif; 
        ?>

    </section>

</main>

<?php get_footer(); ?>
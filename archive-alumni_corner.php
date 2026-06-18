<?php
/**
 * Template Name: Archive Alumni Corner
 * Description: Template desain baru dengan AJAX Search, Filter, & Paginasi Angka (URL Encoded Fix)
 */

get_header('internal'); ?>

<style>
    /* Styling Dasar Halaman */
    body { background-color: #fafafa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    
    /* Hero Section Bersih Tanpa Subjudul */
    .artikel-hero { text-align: center; padding: 60px 20px 30px; background-color: #ffffff; border-bottom: 1px solid #eaeaea; }
    .artikel-hero h1 { font-size: 42px; color: #2d2424; margin: 0; font-weight: bold; font-family: Georgia, serif; }
    .artikel-hero h1 span { color: #D74690; font-style: italic; }

    /* Filter & Search Section */
    .filter-section { max-width: 1200px; margin: 40px auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
    .search-box { flex: 1; min-width: 250px; position: relative; }
    .search-box input { width: 100%; padding: 12px 20px 12px 40px; border-radius: 30px; border: 1px solid #eaeaea; outline: none; background: #fff; }
    
    .filter-pills { display: flex; gap: 10px; flex-wrap: wrap; flex: 2; justify-content: flex-end; }
    .pill { padding: 8px 20px; border-radius: 20px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid #eaeaea; color: #666; background: #fff; transition: 0.2s; cursor: pointer; }
    .pill.active { background: #D74690; color: #fff; border-color: #D74690; }
    .pill:hover:not(.active) { border-color: #D74690; color: #D74690; }

    /* Grid Artikel (3 Kotak Berjajar) */
    .artikel-grid { max-width: 1200px; margin: 0 auto 60px; padding: 0 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; min-height: 200px; align-content: flex-start; }
    
    .artikel-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.04); transition: transform 0.3s; display: flex; flex-direction: column; border: 1px solid #f5f5f5; }
    .artikel-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
    
    .card-img-wrapper { position: relative; height: 220px; width: 100%; border-bottom: 3px solid #D74690; background: #f5f5f5; }
    .card-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    
    .card-content { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; }
    .card-date { font-size: 12px; color: #999; margin-bottom: 10px; font-weight: 600; display: block; text-transform: uppercase; letter-spacing: 1px; }
    .card-title { font-size: 20px; color: #2d2424; margin: 0 0 15px 0; font-weight: 700; line-height: 1.4; }
    .card-title a { color: #2d2424; text-decoration: none; transition: color 0.2s; }
    .card-title a:hover { color: #D74690; }
    .card-desc { font-size: 14px; color: #555; line-height: 1.6; margin-bottom: 25px; flex-grow: 1; }
    
    .card-footer { border-top: 1px solid #f0f0f0; padding-top: 15px; margin-top: auto; display: flex; justify-content: space-between; align-items: center; }
    .card-author { font-size: 12px; color: #888; font-weight: 600; }
    .card-link { color: #D74690; text-decoration: none; font-size: 13px; font-weight: bold; transition: color 0.2s; }
    .card-link:hover { color: #0b1c4c; }

    /* Efek Hover untuk Tombol Angka Paginasi */
    .page-pill:hover:not(.active) { border-color: #D74690 !important; color: #D74690 !important; }

    /* Area Form & Pending List */
    .interaction-area { max-width: 1200px; margin: 0 auto 60px; padding: 0 20px; }
    .section-title-pink { border-bottom: 2px solid #D74690; padding-bottom: 10px; margin-bottom: 30px; color: #D74690; font-size: 22px; font-family: Georgia, serif; }

    @media (max-width: 768px) {
        .filter-section { flex-direction: column; }
        .filter-pills { justify-content: flex-start; }
    }
</style>

<main id="primary" class="site-main">

    <section class="artikel-hero">
        <h1>Alumni <span>Corner</span></h1>
    </section>

    <section class="filter-section">
        <div class="search-box">
            <input type="text" id="search-alumni" placeholder="🔍 Cari judul atau isi postingan...">
        </div>
        <div class="filter-pills">
            <button class="pill active" data-sort="new">Terbaru</button>
            <button class="pill" data-sort="old">Terlama</button>
        </div>
    </section>

    <section class="artikel-grid" id="alumni-grid">
        </section>

    <?php if (is_user_logged_in()) : ?>
        <section class="interaction-area">
            
            <div style="margin-bottom: 60px;">
                <h2 class="section-title-pink">Bagikan Cerita / Opini Anda</h2>
                <?php echo do_shortcode('[form_alumni_corner]'); ?>
            </div>

            <div style="margin-bottom: 40px;">
                <h2 style="border-bottom: 2px solid #f39c12; padding-bottom: 10px; margin-bottom: 30px; color: #f39c12; font-size: 22px; font-family: Georgia, serif;">Status Tulisan Anda (Pending Review)</h2>
                
                <?php
                $current_user_id = get_current_user_id();
                $args_pending = array(
                    'post_type'      => 'alumni_corner',
                    'post_status'    => 'pending',
                    'posts_per_page' => -1,
                    'author'         => $current_user_id
                );
                $query_pending = new WP_Query($args_pending);

                if ($query_pending->have_posts()) : ?>
                    <div style="overflow-x: auto; border: 1px solid #eaeaea; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                        <table style="width: 100%; border-collapse: collapse; background: #fff; text-align: left;">
                            <thead>
                                <tr style="background: #fafafa; border-bottom: 2px solid #eaeaea;">
                                    <th style="padding: 15px 20px; font-weight: bold; color: #2d2424;">Judul Tulisan</th>
                                    <th style="padding: 15px 20px; font-weight: bold; color: #2d2424;">Tanggal Dikirim</th>
                                    <th style="padding: 15px 20px; font-weight: bold; color: #2d2424;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($query_pending->have_posts()) : $query_pending->the_post(); ?>
                                    <tr style="border-bottom: 1px solid #f5f5f5;">
                                        <td style="padding: 15px 20px; font-weight: bold; color: #D74690;"><?php the_title(); ?></td>
                                        <td style="padding: 15px 20px; color: #666; font-size: 14px;"><?php echo get_the_date(); ?></td>
                                        <td style="padding: 15px 20px;">
                                            <span style="display: inline-block; padding: 6px 15px; background: #fff3cd; color: #856404; font-weight: 600; border-radius: 20px; font-size: 12px;">Menunggu Persetujuan</span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <p style="color: #666; background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #eaeaea; text-align: center;">Anda tidak memiliki tulisan yang tertahan di dalam antrean moderasi.</p>
                <?php endif; ?>
            </div>

        </section>
    <?php endif; ?>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('alumni-grid');
    const pills = document.querySelectorAll('.filter-pills .pill');
    const searchInput = document.getElementById('search-alumni');
    
    const ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';

    function fetchAlumniPosts(sort, search, page) {
        grid.innerHTML = '<div style="width: 100%; text-align: center; padding: 50px 0; color: #999; grid-column: 1 / -1;">Memuat postingan...</div>';

        // MENGUBAH FormData MENJADI URLSearchParams AGAR TIDAK DI-DROP OLEH SERVER
        const params = new URLSearchParams();
        params.append('action', 'filter_alumni_corner'); 
        params.append('sort', sort);
        params.append('search', search);
        params.append('page', page);

        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: params.toString()
        })
        .then(response => response.text())
        .then(data => {
            grid.innerHTML = data;
        })
        .catch(error => {
            grid.innerHTML = '<div style="width: 100%; text-align: center; padding: 50px 0; color: red; grid-column: 1 / -1;">Terjadi kesalahan saat memuat data.</div>';
        });
    }

    // Pemuatan data pertama kali (Urutan Terbaru, Kata Kunci Kosong, Halaman 1)
    fetchAlumniPosts('new', '', 1);

    // Event Listener untuk Tombol Filter Urutan (Pills)
    pills.forEach(pill => {
        pill.addEventListener('click', function(e) {
            e.preventDefault();
            
            pills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            
            const sort = this.getAttribute('data-sort');
            const search = searchInput.value;
            fetchAlumniPosts(sort, search, 1); 
        });
    });

    // Event Listener untuk Kotak Pencarian (Debounce System)
    let typingTimer;
    searchInput.addEventListener('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function() {
            const activeSort = document.querySelector('.filter-pills .pill.active').getAttribute('data-sort');
            const search = searchInput.value;
            fetchAlumniPosts(activeSort, search, 1); 
        }, 500); 
    });

    // Event Listener untuk Klik Tombol Angka Paginasi (Menggunakan Event Delegation)
    grid.addEventListener('click', function(e) {
        if (e.target.classList.contains('page-pill')) {
            e.preventDefault();
            
            const targetPage = e.target.getAttribute('data-page');
            const activeSort = document.querySelector('.filter-pills .pill.active').getAttribute('data-sort');
            const search = searchInput.value;
            
            fetchAlumniPosts(activeSort, search, targetPage);
            
            window.scrollTo({
                top: grid.offsetTop - 120,
                behavior: 'smooth'
            });
        }
    });
});
</script>

<?php get_footer(); ?>
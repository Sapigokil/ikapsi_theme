<?php
/**
 * Template Name: Artikel & News
 * Description: Template untuk halaman kumpulan artikel, berita, dan kabar alumni.
 */

get_header('internal'); ?>

<style>
    /* Styling Dasar Halaman Artikel */
    body { background-color: #fafafa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    
    .artikel-hero { text-align: center; padding: 80px 20px 50px; background-color: #ffffff; border-bottom: 1px solid #eaeaea; }
    .artikel-hero h1 { font-size: 42px; color: #2d2424; margin-bottom: 15px; font-weight: bold; font-family: Georgia, serif; }
    .artikel-hero h1 span { color: #D74690; font-style: italic; }
    .artikel-hero p { color: #666; font-size: 16px; max-width: 650px; margin: 0 auto; line-height: 1.6; }

    /* Filter & Search Section */
    .filter-section { max-width: 1200px; margin: 40px auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
    .search-box { flex: 1; min-width: 250px; position: relative; }
    .search-box input { width: 100%; padding: 12px 20px 12px 40px; border-radius: 30px; border: 1px solid #eaeaea; outline: none; background: #fff; }
    
    .filter-pills { display: flex; gap: 10px; flex-wrap: wrap; flex: 2; justify-content: flex-end; }
    .pill { padding: 8px 20px; border-radius: 20px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid #eaeaea; color: #666; background: #fff; transition: 0.2s; cursor: pointer; }
    .pill.active { background: #D74690; color: #fff; border-color: #D74690; }
    .pill:hover:not(.active) { border-color: #D74690; color: #D74690; }

    /* Grid Artikel (3 Kotak Berjajar) */
    .artikel-grid { max-width: 1200px; margin: 0 auto 60px; padding: 0 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; min-height: 400px; align-content: flex-start; }
    
    .artikel-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.04); transition: transform 0.3s; display: flex; flex-direction: column; border: 1px solid #f5f5f5; }
    .artikel-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
    
    .card-img-wrapper { position: relative; height: 220px; width: 100%; border-bottom: 3px solid #D74690; }
    
    .card-content { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; }
    .card-date { font-size: 12px; color: #999; margin-bottom: 10px; font-weight: 600; display: block; text-transform: uppercase; letter-spacing: 1px; }
    .card-title { font-size: 20px; color: #2d2424; margin: 0 0 15px 0; font-weight: 700; line-height: 1.4; }
    .card-desc { font-size: 14px; color: #555; line-height: 1.6; margin-bottom: 25px; flex-grow: 1; }
    
    .card-footer { border-top: 1px solid #f0f0f0; padding-top: 15px; margin-top: auto; text-align: right; }
    .card-link { color: #D74690; text-decoration: none; font-size: 13px; font-weight: bold; transition: color 0.2s; }
    .card-link:hover { color: #0b1c4c; }

    @media (max-width: 768px) {
        .filter-section { flex-direction: column; }
        .filter-pills { justify-content: flex-start; }
    }
</style>

<main id="primary" class="site-main">

    <!-- HERO SECTION -->
    <section class="artikel-hero">
        <h1>Kabar & <span>News</span></h1>
        <p>Ikuti terus berita terbaru, informasi akademik, cerita inspiratif, dan rekam jejak kegiatan dari keluarga besar IKAPSI UNDIP.</p>
    </section>

    <!-- FILTER SECTION -->
    <section class="filter-section">
        <div class="search-box">
            <input type="text" id="search-artikel" placeholder="🔍 Cari judul berita atau artikel...">
        </div>
        <div class="filter-pills">
            <button class="pill active" data-category="all">Semua</button>
            <?php
            // Mengambil kategori bawaan WordPress (Posts > Categories)
            $categories = get_categories(array(
                'hide_empty' => true,
            ));
            if (!empty($categories)) {
                foreach ($categories as $category) {
                    echo '<button class="pill" data-category="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</button>';
                }
            }
            ?>
        </div>
    </section>

    <!-- GRID ARTIKEL (Wadah untuk injeksi AJAX) -->
    <section class="artikel-grid" id="artikel-grid">
        <!-- Konten akan dimuat otomatis via Javascript -->
    </section>

</main>

<!-- SCRIPT AJAX UNTUK FILTER DAN PENCARIAN -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('artikel-grid');
    const pills = document.querySelectorAll('.filter-pills .pill');
    const searchInput = document.getElementById('search-artikel');
    
    const ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';

    function fetchArticles(category, search) {
        grid.innerHTML = '<div style="width: 100%; text-align: center; padding: 50px 0; color: #999; grid-column: 1 / -1;">Memuat artikel...</div>';

        const formData = new FormData();
        formData.append('action', 'filter_artikel'); // Mengarah ke fungsi baru di functions.php
        formData.append('category', category);
        formData.append('search', search);

        fetch(ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            grid.innerHTML = data;
        })
        .catch(error => {
            grid.innerHTML = '<div style="width: 100%; text-align: center; padding: 50px 0; color: red; grid-column: 1 / -1;">Terjadi kesalahan sistem.</div>';
        });
    }

    // Pemuatan data pertama kali (Semua)
    fetchArticles('all', '');

    // Event Listener untuk Tombol Filter (Pills)
    pills.forEach(pill => {
        pill.addEventListener('click', function(e) {
            e.preventDefault();
            
            pills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.getAttribute('data-category');
            const search = searchInput.value;
            fetchArticles(category, search);
        });
    });

    // Event Listener untuk Kotak Pencarian (Debounce System)
    let typingTimer;
    searchInput.addEventListener('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function() {
            const activeCategory = document.querySelector('.filter-pills .pill.active').getAttribute('data-category');
            const search = searchInput.value;
            fetchArticles(activeCategory, search);
        }, 500); 
    });
});
</script>

<?php get_footer(); ?>
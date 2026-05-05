<?php
/**
 * Template Name: Alumni Project
 * Description: Template landing page elegan untuk program kerja dan project alumni.
 */

get_header('internal'); ?>

<style>
    /* Styling Global Halaman */
    body { background-color: #fdfdfd; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #96757F; }
    h1, h2, h3 { font-family: 'Georgia', serif; color: #4A0A1F; }
    .text-pink { color: #D74690; }
    .btn-pink { background-color: #D74690; color: #fff; padding: 12px 30px; border-radius: 30px; text-decoration: none; font-weight: 600; display: inline-block; transition: 0.3s; border: 1px solid #D74690; }
    .btn-pink:hover { background-color: #bf3a7d; color: #fff; }
    .btn-outline-pink { background-color: transparent; color: #D74690; padding: 12px 30px; border-radius: 30px; text-decoration: none; font-weight: 600; display: inline-block; transition: 0.3s; border: 1px solid #D74690; }
    .btn-outline-pink:hover { background-color: #D74690; color: #fff; }

    /* 1. Hero Section */
    .hero-lp { text-align: center; padding: 80px 20px; background: radial-gradient(circle at top, rgba(215,70,144,0.05) 0%, rgba(253,253,253,1) 70%); }
    .hero-lp h1 { font-size: 52px; color: #4A0A1F; margin-bottom: 10px; font-weight: bold; }
    .hero-lp h1 span { font-style: italic; font-weight: normal; }
    .hero-lp p { font-size: 16px; color: #96757F; max-width: 600px; margin: 0 auto 30px; line-height: 1.6; }
    .hero-buttons { display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; }

    /* 2. Program Kerja (Dinamis dari CPT) */
    .program-section { max-width: 1200px; margin: 0 auto 80px; padding: 0 20px; }
    .program-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; flex-wrap: wrap; gap: 15px; }
    .program-header h2 { font-size: 32px; margin: 0; color: #4A0A1F; }
    .program-header p { color: #96757F; margin: 10px 0 0 0; max-width: 500px; }
    .program-header a { color: #D74690; text-decoration: none; font-weight: bold; font-size: 14px; }

    .program-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; }
    .program-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid #f9f9f9; transition: 0.3s; }
    .program-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(215,70,144,0.08); }
    
    .p-card-img { position: relative; height: 200px; width: 100%; }
    .p-card-img img { width: 100%; height: 100%; object-fit: cover; }
    .p-badge { position: absolute; top: 15px; left: 15px; background: rgba(255,255,255,0.95); color: #D74690; padding: 5px 15px; border-radius: 20px; font-size: 11px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    
    .p-card-body { padding: 30px; position: relative; }
    .p-icon-circle { width: 50px; height: 50px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; position: absolute; top: -25px; left: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); color: #D74690; font-size: 20px; font-weight: bold; }
    .p-card-body h3 { font-size: 20px; margin: 15px 0 10px; font-family: 'Segoe UI', sans-serif; font-weight: 700; color: #4A0A1F; }
    .p-card-body p { font-size: 14px; color: #96757F; line-height: 1.6; margin-bottom: 20px; }
    .p-card-link { font-size: 13px; font-weight: 700; color: #4A0A1F; text-decoration: none; text-transform: uppercase; letter-spacing: 0.5px; }
    .p-card-link:hover { color: #D74690; }

    /* 3. Kolaborasi (Dinamis dari ACF Page) */
    .kolaborasi-section { max-width: 1200px; margin: 0 auto 80px; padding: 0 20px; display: flex; align-items: center; gap: 60px; flex-wrap: wrap; }
    .kolaborasi-text { flex: 1; min-width: 300px; }
    .kolaborasi-text h4 { color: #D74690; font-family: 'Segoe UI', sans-serif; font-size: 13px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; }
    .kolaborasi-text h2 { font-size: 36px; line-height: 1.2; margin-bottom: 20px; color: #4A0A1F; }
    .kolaborasi-text p { color: #96757F; line-height: 1.7; margin-bottom: 25px; }
    .kolaborasi-list { list-style: none; padding: 0; margin: 0 0 30px 0; }
    .kolaborasi-list li { position: relative; padding-left: 30px; margin-bottom: 15px; font-size: 15px; color: #96757F; font-weight: 600; }
    .kolaborasi-list li::before { content: '✓'; position: absolute; left: 0; top: 0; color: #D74690; background: rgba(215,70,144,0.1); width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; }
    
    .kolaborasi-img-wrapper { flex: 1; min-width: 300px; position: relative; }
    .kolaborasi-img-wrapper img { width: 100%; border-radius: 12px; object-fit: cover; height: 400px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    
    /* Perbaikan Floating Card sesuai desain */
    .floating-card { position: absolute; bottom: -20px; left: -30px; background: #fff; padding: 25px 30px; border-radius: 12px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); width: 85%; display: flex; flex-direction: column; gap: 15px; }
    .f-header { display: flex; justify-content: space-between; align-items: center; width: 100%; }
    .floating-card .f-text { font-weight: 700; font-size: 16px; color: #4A0A1F; }
    .floating-card .f-status { color: #D74690; font-size: 11px; font-weight: bold; text-transform: uppercase; background: rgba(215,70,144,0.1); padding: 4px 12px; border-radius: 20px; }
    .progress-container { width: 100%; }
    .progress-bar-bg { width: 100%; height: 6px; background: #eaeaea; border-radius: 3px; overflow: hidden; margin-bottom: 10px; }
    .progress-bar-fill { height: 100%; background: #D74690; border-radius: 3px; transition: width 1s ease-in-out; }
    .progress-text { font-size: 12px; color: #96757F; text-align: right; width: 100%; display: block; font-weight: 600; }

    /* 4. Mitra Section (Dinamis dari ACF Page) */
    .mitra-section { border-top: 1px solid #eaeaea; border-bottom: 1px solid #eaeaea; padding: 40px 20px; margin-bottom: 80px; text-align: center; }
    .mitra-label { font-size: 11px; text-transform: uppercase; color: #96757F; letter-spacing: 2px; margin-bottom: 20px; }
    .mitra-logos { display: flex; justify-content: center; align-items: center; gap: 40px; flex-wrap: wrap; max-width: 1000px; margin: 0 auto; opacity: 0.6; }
    .mitra-logos img { max-height: 40px; max-width: 120px; object-fit: contain; filter: grayscale(100%); transition: 0.3s; }
    .mitra-logos img:hover { filter: grayscale(0%); opacity: 1; }

    /* 5. Statis: Siap Kolaborasi & 3 Kotak */
    .siap-kolaborasi { text-align: center; max-width: 1200px; margin: 0 auto 80px; padding: 0 20px; }
    .siap-kolaborasi h2 { font-size: 32px; color: #4A0A1F; margin-bottom: 10px; }
    .siap-kolaborasi > p { color: #96757F; margin-bottom: 30px; }
    
    .sk-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-top: 50px; text-align: left; }
    .sk-card { background: #fff; padding: 40px 30px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); border: 1px solid #f9f9f9; }
    .sk-icon { width: 40px; height: 40px; background: rgba(215,70,144,0.1); color: #D74690; display: flex; align-items: center; justify-content: center; border-radius: 8px; margin-bottom: 20px; font-weight: bold; font-size: 18px; }
    .sk-card h3 { font-family: 'Segoe UI', sans-serif; font-size: 18px; font-weight: 700; margin-bottom: 15px; color: #4A0A1F; }
    .sk-card p { font-size: 14px; color: #96757F; line-height: 1.6; margin-bottom: 20px; }
    .sk-card a { color: #D74690; font-size: 13px; font-weight: bold; text-decoration: none; }

    /* 6. Statis: Banner Bawah */
    .bottom-banner { max-width: 1000px; margin: 0 auto 80px; background: linear-gradient(to right, #fff, rgba(215,70,144,0.05)); border-radius: 16px; padding: 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 10px 40px rgba(0,0,0,0.05); flex-wrap: wrap; gap: 20px; }
    .bb-text h2 { font-size: 28px; margin: 0 0 10px 0; color: #4A0A1F; }
    .bb-text p { margin: 0; color: #96757F; font-size: 15px; max-width: 400px; }

    @media (max-width: 768px) {
        .kolaborasi-section { flex-direction: column; }
        .floating-card { left: 10px; width: 90%; bottom: -40px; }
        .bottom-banner { flex-direction: column; text-align: center; }
        .kolaborasi-img-wrapper { margin-bottom: 50px; }
    }
</style>

<main id="primary" class="site-main">

    <!-- 1. HERO SECTION (Statis) -->
    <section class="hero-lp">
        <div style="font-size: 12px; color: #D74690; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 15px;">IKAPSI UNDIP PROJECT</div>
        <h1>Berjalan Jauh <br><span class="text-pink">Bersama</span></h1>
        <p>Membangun jembatan yang kokoh antara alumni, industri, dan sektor publik untuk menciptakan dampak yang berkelanjutan bagi Indonesia.</p>
        <div class="hero-buttons">
            <a href="#" class="btn-outline-pink">Ajukan Kerjasama</a>
            <a href="#program" class="btn-pink">Pelajari Lebih Lanjut</a>
        </div>
    </section>

    <!-- 2. PROGRAM KERJA (Dinamis dari CPT Alumni Projects) -->
    <section id="program" class="program-section">
        <div class="program-header">
            <div>
                <h2>Program kerja</h2>
                <p>Inisiatif strategis yang dirancang untuk mendukung alumni dan mengabdi pada masyarakat luas.</p>
            </div>
            <a href="#">Lihat Semua Program →</a>
        </div>

        <div class="program-grid">
            <?php
            $args = array(
                'post_type'      => 'alumni_project',
                'posts_per_page' => 3, // Mengambil 3 project terbaru
                'post_status'    => 'publish'
            );
            $query_project = new WP_Query($args);

            if ($query_project->have_posts()) :
                while ($query_project->have_posts()) : $query_project->the_post();
                    // Ambil status dari ACF yang baru saja dibuat
                    $status = get_field('status_project');
                    if (!$status) { $status = 'ACTIVE'; } // Default fallback
            ?>
                    <div class="program-card">
                        <div class="p-card-img">
                            <div class="p-badge"><?php echo esc_html($status); ?></div>
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium', array('style' => 'width: 100%; height: 100%; object-fit: cover;')); ?>
                            <?php else: ?>
                                <div style="width: 100%; height: 100%; background: #eaeaea;"></div>
                            <?php endif; ?>
                        </div>
                        <div class="p-card-body">
                            <div class="p-icon-circle">⊛</div> <!-- Ikon default statis -->
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p>
                            <a href="<?php the_permalink(); ?>" class="p-card-link">Lihat Detail →</a>
                        </div>
                    </div>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p>Belum ada program kerja yang ditambahkan.</p>';
            endif;
            ?>
        </div>
    </section>

    <!-- 3. KOLABORASI PUBLIK (Dinamis dari ACF Halaman) -->
    <?php 
    // Mengambil data dari ACF halaman ini
    $k_judul = get_field('kolaborasi_judul');
    $k_desc = get_field('kolaborasi_deskripsi');
    $k_poin1 = get_field('kolaborasi_poin_1');
    $k_poin2 = get_field('kolaborasi_poin_2');
    $k_img = get_field('kolaborasi_gambar');
    $k_float = get_field('kolaborasi_floating_teks');
    
    // Mengambil nilai progress (dengan fallback 75)
    $k_progress = get_field('kolaborasi_progress');
    $progress_val = $k_progress ? esc_attr($k_progress) : '75';
    
    // Tampilkan section ini HANYA jika judulnya diisi di dashboard
    if ($k_judul) :
    ?>
    <section class="kolaborasi-section">
        <div class="kolaborasi-text">
            <h4>KOLABORASI PUBLIK</h4>
            <h2><?php echo esc_html($k_judul); ?></h2>
            <p><?php echo esc_html($k_desc); ?></p>
            <ul class="kolaborasi-list">
                <?php if($k_poin1): ?><li><?php echo esc_html($k_poin1); ?></li><?php endif; ?>
                <?php if($k_poin2): ?><li><?php echo esc_html($k_poin2); ?></li><?php endif; ?>
            </ul>
            <a href="#" class="btn-outline-pink" style="padding: 10px 20px; font-size: 14px;">Baca Laporan Lengkap →</a>
        </div>
        <div class="kolaborasi-img-wrapper">
            <img src="<?php echo esc_url($k_img ? $k_img : get_template_directory_uri().'/images/dummy-office.jpg'); ?>" alt="Kolaborasi">
            
            <div class="floating-card">
                <div class="f-header">
                    <span class="f-text"><?php echo esc_html($k_float ? $k_float : 'Partner In Community'); ?></span>
                    <span class="f-status">AKTIF</span>
                </div>
                <div class="progress-container">
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: <?php echo $progress_val; ?>%;"></div>
                    </div>
                    <span class="progress-text">Progress: <?php echo $progress_val; ?>% Lengkap</span>
                </div>
            </div>

        </div>
    </section>
    <?php endif; ?>

    <!-- 4. MITRA LOGO (Dinamis dari ACF Halaman) -->
    <section class="mitra-section">
        <div class="mitra-label">BERKOLABORASI BERSAMA</div>
        <div class="mitra-logos">
            <?php 
            for($i = 1; $i <= 5; $i++) {
                $logo_mitra = get_field('mitra_' . $i);
                if ($logo_mitra) {
                    echo '<img src="'.esc_url($logo_mitra).'" alt="Logo Mitra '.$i.'">';
                }
            }
            // Fallback teks jika logo belum diupload
            if(!get_field('mitra_1')) {
                echo '<span style="font-weight:bold; color:#ccc; font-size:20px;">TECHNO</span>';
                echo '<span style="font-weight:bold; color:#ccc; font-size:20px;">GLOBAL</span>';
                echo '<span style="font-weight:bold; color:#ccc; font-size:20px;">NEXUS</span>';
                echo '<span style="font-weight:bold; color:#ccc; font-size:20px;">ALUMNI</span>';
            }
            ?>
        </div>
    </section>

    <!-- 5. SIAP KOLABORASI & LAYANAN (Statis) -->
    <section class="siap-kolaborasi">
        <h2>Siap untuk Berkolaborasi?</h2>
        <p>Kami menyambut ide dan sinergi dari berbagai pihak untuk membangun ekosistem yang lebih baik.</p>
        <div style="margin-bottom: 40px;">
            <a href="#" class="btn-pink" style="margin-right: 15px;">Mulai Diskusi</a>
            <a href="#" class="btn-outline-pink" style="border:none; text-decoration:underline;">Lihat Legalitas Dokumen</a>
        </div>

        <div class="sk-grid">
            <div class="sk-card">
                <div class="sk-icon">⚲</div>
                <h3>Capacity Building</h3>
                <p>Program peningkatan kapasitas bagi pihak-pihak terkait untuk menjawab tantangan dan kebutuhan industri modern.</p>
                <a href="#">Detail Program →</a>
            </div>
            <div class="sk-card">
                <div class="sk-icon">♺</div>
                <h3>Joint Research</h3>
                <p>Kolaborasi penelitian aplikatif bersama institusi pendidikan dan badan riset tingkat nasional maupun internasional.</p>
                <a href="#">Lihat Riset →</a>
            </div>
            <div class="sk-card">
                <div class="sk-icon">⛬</div>
                <h3>Social Impact</h3>
                <p>Gerakan filantropi dan pengabdian masyarakat terstruktur dengan fokus pada pendidikan dan kesejahteraan rakyat.</p>
                <a href="#">Gabung Aksi →</a>
            </div>
        </div>
    </section>

    <!-- 6. BOTTOM BANNER (Statis) -->
    <section class="bottom-banner">
        <div class="bb-text">
            <h2>Punya Ide Kolaborasi?</h2>
            <p>Jangan ragu untuk menyampaikannya kepada kami. Kami sangat terbuka untuk mengeksplorasi inisiatif baru.</p>
        </div>
        <div>
            <a href="#" class="btn-outline-pink">Ajukan Proposal Ide</a>
        </div>
    </section>

</main>

<?php get_footer(); ?>
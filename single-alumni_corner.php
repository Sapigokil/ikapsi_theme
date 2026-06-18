<?php
/**
 * Template Name: Single Alumni Corner
 * Description: Template pembaca Alumni Corner (Inline Edit, Delete, & Live Edited Time)
 */

get_header('internal'); ?>

<style>
    /* ==========================================================================
       STYLING DASAR & WADAH KERTAS (CARD)
       ========================================================================== */
    body { background-color: #fafafa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .alumni-single-wrapper { max-width: 850px; margin: 40px auto 60px auto; padding: 0 20px; }
    
    .btn-back-alumni { display: inline-block; margin-bottom: 20px; padding: 8px 18px; background: #ffffff; color: #555; text-decoration: none; border-radius: 30px; font-weight: 600; font-size: 13px; border: 1px solid #ddd; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: 0.3s; }
    .btn-back-alumni:hover { background: #D74690; color: #fff; border-color: #D74690; }

    .alumni-card-paper { 
        background: #ffffff; 
        border-radius: 12px; 
        box-shadow: 0 8px 30px rgba(0,0,0,0.05); 
        border: 1px solid #eaeaea; 
        padding: 35px 40px; 
        position: relative;
        margin-bottom: 20px;
    }

    /* ==========================================================================
       STYLING HEADER & KONTEN POSTINGAN
       ========================================================================== */
    .alumni-post-title { font-size: 28px; color: #2d2424; margin-top: 0; margin-bottom: 10px; font-weight: bold; line-height: 1.3; }
    .alumni-post-meta { font-size: 13px; color: #888; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #f8f8f8; }
    .alumni-post-meta span { color: #D74690; font-weight: 600; }

    .alumni-slider-container { position: relative; width: 100%; height: 380px; background: #fdfdfd; border-radius: 8px; overflow: hidden; margin-bottom: 20px; border: 1px solid #f0f0f0; }
    .alumni-slide { display: none; width: 100%; height: 100%; text-align: center; }
    .alumni-slide.active { display: block; }
    .alumni-slide img { width: 100%; height: 100%; object-fit: contain; cursor: zoom-in; }
    
    .slider-btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.4); color: #fff; border: none; padding: 10px 15px; cursor: pointer; border-radius: 50%; font-size: 16px; transition: 0.3s; z-index: 2; }
    .slider-btn:hover { background: #D74690; }
    .slider-prev { left: 15px; }
    .slider-next { right: 15px; }
    
    .slider-dots { text-align: center; margin-top: -12px; margin-bottom: 20px; }
    .dot { display: inline-block; width: 8px; height: 8px; background: #eee; border-radius: 50%; margin: 0 4px; cursor: pointer; transition: 0.3s; }
    .dot.active { background: #D74690; transform: scale(1.2); }

    .alumni-post-content { font-size: 15px; color: #444; line-height: 1.7; margin-bottom: 10px; }

    /* ==========================================================================
       STYLING AREA KOMENTAR (FRAME KE-2)
       ========================================================================== */
    .alumni-comments-card { padding: 25px 35px; }
    .alumni-comments-card h3 { font-size: 18px; color: #2d2424; margin-bottom: 20px; border-left: 4px solid #D74690; padding-left: 12px; margin-top: 0; }
    
    .comment-login-notice { background: #fff4f9; border: 1px solid #D74690; padding: 12px; border-radius: 6px; color: #7d2a54; font-size: 13px; text-align: center; }

    #commentform { background: #fdfdfd; padding: 15px; border-radius: 8px; border: 1px solid #eaeaea; margin-top: 15px; }
    #commentform textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; background: #fff; margin-bottom: 8px; font-family: inherit; font-size: 13px; transition: 0.3s; resize: vertical; }
    #commentform textarea:focus { border-color: #D74690; outline: none; box-shadow: 0 0 0 2px rgba(215, 70, 144, 0.1); }
    #commentform input[type="submit"] { background: #D74690; color: #fff; border: none; padding: 8px 20px; border-radius: 30px; font-weight: bold; cursor: pointer; transition: 0.2s; font-size: 12px; }
    #commentform input[type="submit"]:hover { background: #0b1c4c; }
    
    /* GAYA BUBBLE */
    .comment-list { list-style: none; padding: 0; margin-bottom: 20px; }
    .comment-list .comment { margin-bottom: 8px; }
    
    .comment-list .comment-body {
        background: #ffffff; border: 1px solid #eaeaea; padding: 8px 12px; 
        border-radius: 10px; border-top-left-radius: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        margin-left: 40px; position: relative;
    }

    .comment-list .comment-author img { position: absolute; left: -40px; top: 0; border-radius: 50%; border: 1px solid #D74690; padding: 1px; background: #fff; width: 32px; height: 32px; }
    .comment-author cite.fn { font-style: normal; font-weight: bold; color: #D74690; font-size: 13px; margin-right: 6px; }
    .comment-list .comment-author { display: inline; margin: 0; }
    .comment-meta { display: inline-block; font-size: 11px; color: #aaa; margin: 0; padding: 0; border: none; }
    .comment-meta a { color: #aaa; text-decoration: none; }
    
    .comment-content { font-size: 13px; color: #333; line-height: 1.5; margin-top: 4px; }
    .comment-content p { margin-bottom: 0; }
    
    /* AREA AKSI (Balas, Edit, Hapus) */
    .reply { text-align: right; margin-top: 4px; }
    .reply a { display: inline-block; font-size: 11px; font-weight: bold; color: #999; text-decoration: none; transition: 0.2s; }
    .reply a:hover { color: #D74690; text-decoration: underline; }

    /* BALASAN */
    .comment-list .children { margin-top: 8px; padding-left: 0; list-style: none; margin-left: 20px; } 
    .comment-list .children .comment-body { background: #fffcfd; border-color: #fae3ed; }

    /* LIGHTBOX */
    .lightbox-modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9); }
    .lightbox-content { margin: auto; display: block; max-width: 90%; max-height: 90vh; position: relative; top: 50%; transform: translateY(-50%); object-fit: contain; }
    .lightbox-close { position: absolute; top: 20px; right: 40px; color: #fff; font-size: 50px; font-weight: bold; cursor: pointer; transition: 0.2s; }
    .lightbox-close:hover { color: #D74690; }

    @media (max-width: 768px) {
        .alumni-card-paper { padding: 25px 20px; }
        .alumni-comments-card { padding: 20px 20px; }
        .alumni-slider-container { height: 280px; }
        .comment-list .comment-body { margin-left: 35px; }
        .comment-list .comment-author img { left: -35px; width: 28px; height: 28px; }
        .comment-list .children { margin-left: 10px; }
    }
</style>

<main id="primary" class="site-main">
    <div class="alumni-single-wrapper">
        
        <a href="<?php echo home_url('/alumni-corner/'); ?>" class="btn-back-alumni">&laquo; Kembali ke Daftar Postingan</a>

        <?php while ( have_posts() ) : the_post(); ?>
            
            <article class="alumni-card-paper">
                
                <h1 class="alumni-post-title"><?php the_title(); ?></h1>
                <div class="alumni-post-meta">
                    Oleh <span><?php the_author(); ?></span> | <?php echo get_the_date('d F Y'); ?>
                </div>

                <?php
                $images = array();
                if (has_post_thumbnail()) { $images[] = get_the_post_thumbnail_url(get_the_ID(), 'full'); }
                for ($i = 1; $i <= 3; $i++) {
                    $foto = get_post_meta(get_the_ID(), 'foto_pendukung_' . $i, true);
                    if (!empty($foto)) { $images[] = $foto; }
                }
                ?>

                <?php if (!empty($images)) : ?>
                    <div class="alumni-slider-container">
                        <?php foreach ($images as $index => $img_url) : ?>
                            <div class="alumni-slide <?php echo ($index === 0) ? 'active' : ''; ?>">
                                <img src="<?php echo esc_url($img_url); ?>" alt="Image <?php echo $index + 1; ?>" class="zoomable-image">
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (count($images) > 1) : ?>
                            <button class="slider-btn slider-prev">&#10094;</button>
                            <button class="slider-btn slider-next">&#10095;</button>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (count($images) > 1) : ?>
                        <div class="slider-dots">
                            <?php foreach ($images as $index => $img_url) : ?>
                                <span class="dot <?php echo ($index === 0) ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="alumni-post-content">
                    <?php the_content(); ?>
                </div>

            </article> 

            <section class="alumni-card-paper alumni-comments-card">
                <h3>Diskusi Alumni</h3>

                <?php if (is_user_logged_in()) : ?>
                    
                    <?php if (!comments_open()) : ?>
                        <div style="background:#fff3cd; color:#856404; padding:10px 15px; border-radius:4px; font-size:13px; border:1px solid #ffeeba;">
                            <strong>Info:</strong> Kolom komentar pada postingan ini ditutup.
                        </div>
                    <?php else: ?>
                        
                        <ul class="comment-list">
                            <?php
                            $post_comments = get_comments(array(
                                'post_id' => get_the_ID(),
                                'status'  => 'approve',
                                'order'   => 'ASC'
                            ));

                            wp_list_comments(array(
                                'callback'    => 'ikapsi_alumni_comment_layout',
                                'style'       => 'ul',
                                'short_ping'  => true,
                            ), $post_comments);
                            ?>
                        </ul>

                        <?php 
                        comment_form(array(
                            'title_reply'          => 'Tinggalkan Komentar',
                            'title_reply_to'       => 'Balas Komentar %s',
                            'label_submit'         => 'Kirim Komentar',
                            'comment_notes_before' => '',
                            'comment_field'        => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="2" aria-required="true" placeholder="Tuliskan komentar atau tanggapan Anda di sini..."></textarea></p>',
                        )); 
                        ?>
                    <?php endif; ?>

                <?php else : ?>
                    <div class="comment-login-notice">
                        Mohon maaf, Anda harus <strong><a href="<?php echo wp_login_url(get_permalink()); ?>" style="color:#D74690;">Login Alumni</a></strong> untuk melihat diskusi dan memberikan komentar.
                    </div>
                <?php endif; ?>
            </section>

        <?php endwhile; ?>
    </div>
</main>

<div id="imageLightbox" class="lightbox-modal">
    <span class="lightbox-close">&times;</span>
    <img class="lightbox-content" id="lightboxImage">
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';

    // 1. Slider Logic
    const slides = document.querySelectorAll('.alumni-slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.slider-prev');
    const nextBtn = document.querySelector('.slider-next');
    let currentSlide = 0;

    function showSlide(index) {
        if (slides.length === 0) return;
        slides.forEach(s => s.classList.remove('active'));
        if (dots.length > 0) dots.forEach(d => d.classList.remove('active'));
        if (index >= slides.length) currentSlide = 0;
        else if (index < 0) currentSlide = slides.length - 1;
        else currentSlide = index;
        slides[currentSlide].classList.add('active');
        if (dots.length > 0) dots[currentSlide].classList.add('active');
    }

    if (nextBtn) nextBtn.addEventListener('click', () => showSlide(++currentSlide));
    if (prevBtn) prevBtn.addEventListener('click', () => showSlide(--currentSlide));
    if (dots.length > 0) {
        dots.forEach(d => d.addEventListener('click', function() {
            currentSlide = parseInt(this.getAttribute('data-slide'));
            showSlide(currentSlide);
        }));
    }

    // 2. Lightbox Logic
    const modal = document.getElementById("imageLightbox");
    const modalImg = document.getElementById("lightboxImage");
    const spanClose = document.querySelector(".lightbox-close");
    document.querySelectorAll('.zoomable-image').forEach(img => {
        img.addEventListener('click', function() {
            modal.style.display = "block";
            modalImg.src = this.src;
        });
    });
    if (spanClose) spanClose.onclick = () => modal.style.display = "none";
    if (modal) modal.onclick = (e) => { if (e.target === modal) modal.style.display = "none"; };

    // 3. Event Delegation untuk Tombol Edit, Batal, Simpan, dan Hapus Komentar
    document.querySelector('.comment-list').addEventListener('click', function(e) {
        
        // A. KLIK TOMBOL DELETE
        if (e.target.classList.contains('btn-delete-comment')) {
            e.preventDefault();
            if (!confirm('Apakah Anda yakin ingin menghapus komentar ini? Tindakan ini tidak dapat dibatalkan.')) return;

            const commentId = e.target.getAttribute('data-id');
            const nonce = e.target.getAttribute('data-nonce');
            const commentLi = e.target.closest('.comment');

            const params = new URLSearchParams();
            params.append('action', 'delete_alumni_comment');
            params.append('comment_id', commentId);
            params.append('nonce', nonce);

            fetch(ajaxurl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params.toString()
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    commentLi.style.transition = 'opacity 0.4s ease';
                    commentLi.style.opacity = '0';
                    setTimeout(() => { commentLi.remove(); }, 400);
                } else { alert('Gagal menghapus: ' + data.data); }
            })
            .catch(() => alert('Terjadi kesalahan koneksi.'));
        }

        // B. KLIK TOMBOL EDIT (Memunculkan Form)
        if (e.target.classList.contains('btn-inline-edit')) {
            e.preventDefault();
            const id = e.target.getAttribute('data-id');
            document.getElementById('comment-text-' + id).style.display = 'none';
            document.getElementById('comment-edit-form-' + id).style.display = 'block';
        }

        // C. KLIK TOMBOL BATAL (Menutup Form)
        if (e.target.classList.contains('btn-cancel-edit')) {
            e.preventDefault();
            const id = e.target.getAttribute('data-id');
            document.getElementById('comment-edit-form-' + id).style.display = 'none';
            document.getElementById('comment-text-' + id).style.display = 'block';
        }

        // D. KLIK TOMBOL SIMPAN PEMBARUAN (Eksekusi AJAX Edit)
        if (e.target.classList.contains('btn-save-edit')) {
            e.preventDefault();
            const id = e.target.getAttribute('data-id');
            const nonce = e.target.getAttribute('data-nonce');
            const newContent = document.getElementById('edit-textarea-' + id).value;

            // Efek Loading pada tombol
            const originalText = e.target.innerHTML;
            e.target.innerHTML = 'Menyimpan...';
            e.target.disabled = true;

            const params = new URLSearchParams();
            params.append('action', 'edit_alumni_comment');
            params.append('comment_id', id);
            params.append('content', newContent);
            params.append('nonce', nonce);

            fetch(ajaxurl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params.toString()
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Masukkan teks baru yang sudah berformat HTML ke dalam kolom baca
                    document.getElementById('comment-text-' + id).innerHTML = data.data.new_html;
                    
                    // Masukkan waktu edit baru ke dalam label waktu (real-time tanpa reload)
                    document.getElementById('edited-time-label-' + id).innerHTML = data.data.edited_time_string;
                    
                    // Tutup Form, Munculkan Teks
                    document.getElementById('comment-edit-form-' + id).style.display = 'none';
                    document.getElementById('comment-text-' + id).style.display = 'block';
                } else { 
                    alert('Gagal mengedit: ' + data.data); 
                }
            })
            .catch(() => alert('Terjadi kesalahan koneksi.'))
            .finally(() => {
                e.target.innerHTML = originalText;
                e.target.disabled = false;
            });
        }
    });

});
</script>

<?php get_footer(); ?>
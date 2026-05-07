<?php
/**
 * Template Name: Register Custom
 * Description: Halaman pendaftaran kustom dengan tambahan No Telp dan Alamat.
 */

// Jika user sudah login, langsung lempar ke halaman member area
if ( is_user_logged_in() ) {
    wp_redirect( home_url( '/user/' ) );
    exit;
}

$errors = array();
$success_message = '';

// Memproses data jika tombol submit ditekan
if ( isset( $_POST['submit_register'] ) && isset( $_POST['register_nonce'] ) ) {
    
    // Verifikasi Nonce untuk keamanan
    if ( wp_verify_nonce( $_POST['register_nonce'], 'ikapsi_register_action' ) ) {
        
        // Membersihkan input
        $username     = sanitize_user( $_POST['username'] );
        $email        = sanitize_email( $_POST['email'] );
        $full_name    = sanitize_text_field( $_POST['full_name'] );
        $fakultas     = sanitize_text_field( $_POST['fakultas'] );
        $tahun_lulus  = sanitize_text_field( $_POST['tahun_lulus'] );
        $phone_number = sanitize_text_field( $_POST['phone_number'] );
        $alamat       = sanitize_textarea_field( $_POST['alamat'] );
        $password     = $_POST['password'];
        $password_cek = $_POST['password_confirm'];

        // Validasi kolom wajib
        if ( empty( $username ) || empty( $email ) || empty( $full_name ) || empty( $password ) || empty( $tahun_lulus ) || empty( $phone_number ) || empty( $alamat ) ) {
            $errors[] = 'Mohon lengkapi semua kolom yang wajib diisi.';
        }
        
        if ( $password !== $password_cek ) {
            $errors[] = 'Kata sandi tidak cocok.';
        }
        
        if ( username_exists( $username ) ) {
            $errors[] = 'Username sudah digunakan.';
        }
        if ( email_exists( $email ) ) {
            $errors[] = 'Email sudah terdaftar.';
        }

        if ( empty( $errors ) ) {
            $userdata = array(
                'user_login' => $username,
                'user_email' => $email,
                'user_pass'  => $password,
                'first_name' => $full_name,
                'role'       => 'subscriber'
            );

            $user_id = wp_insert_user( $userdata );

            if ( ! is_wp_error( $user_id ) ) {
                // Menyimpan data tambahan (User Meta)
                update_user_meta( $user_id, 'fakultas', $fakultas );
                update_user_meta( $user_id, 'tahun_lulus', $tahun_lulus );
                update_user_meta( $user_id, 'phone_number', $phone_number );
                update_user_meta( $user_id, 'alamat', $alamat );
                
                // Set status akun menjadi Pending untuk Ultimate Member
                update_user_meta( $user_id, 'account_status', 'awaiting_admin_review' );
                
                $success_message = 'Pendaftaran berhasil! Akun Anda sedang dalam proses <strong>Verifikasi Admin</strong>. Kami akan memberikan informasi lebih lanjut segera.';
            } else {
                $errors[] = $user_id->get_error_message();
            }
        }
    }
}

get_header('internal'); ?>

<style>
    .register-custom-section {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        padding: 60px 20px;
    }

    .register-card {
        background: #ffffff;
        width: 100%;
        max-width: 650px;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(215, 70, 144, 0.1);
        border: 1px solid #eaeaea;
        overflow: hidden;
    }

    .register-card-header {
        background-color: #D74690;
        padding: 40px 30px;
        text-align: center;
        color: #ffffff;
    }

    .register-card-header h2 {
        margin: 0;
        font-family: 'Georgia', serif;
        font-size: 28px;
    }

    .register-card-header p {
        margin: 10px 0 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .register-form-container {
        padding: 40px 35px;
    }

    .register-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 25px;
    }
    
    .full-width-col {
        grid-column: 1 / -1;
    }

    .input-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #4A0A1F; /* Dark Red hanya untuk font */
        font-size: 14px;
    }

    .input-group input, .input-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 15px;
        transition: border-color 0.3s;
        box-sizing: border-box;
        font-family: inherit;
    }

    .input-group input:focus, .input-group textarea:focus {
        border-color: #D74690;
        outline: none;
    }

    .register-submit-btn {
        width: 100%;
        background-color: #D74690;
        color: #fff;
        border: none;
        padding: 14px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s;
        text-transform: uppercase;
    }

    .register-submit-btn:hover {
        background-color: #bf3a7d;
        transform: translateY(-2px);
    }

    .register-card-footer {
        text-align: center;
        padding: 0 35px 40px;
        font-size: 14px;
        color: #666;
    }

    .register-card-footer a {
        color: #D74690;
        text-decoration: none;
        font-weight: 600;
    }

    .register-error {
        background-color: #FFF0F6;
        color: #4A0A1F;
        padding: 12px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 20px;
        border-left: 4px solid #D74690;
    }

    .register-success {
        background-color: #e6f7ec;
        color: #1e7e34;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        border-left: 4px solid #28a745;
    }

    @media (max-width: 600px) {
        .register-form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="register-custom-section">
    <div class="register-card">
        
        <div class="register-card-header">
            <h2>Daftar Alumni</h2>
            <p>Lengkapi data Anda untuk bergabung dalam jejaring alumni</p>
        </div>

        <div class="register-form-container">
            
            <?php if ( !empty($success_message) ) : ?>
                <div class="register-success">
                    <?php echo $success_message; ?>
                </div>
                <div style="text-align: center; margin-top: 30px;">
                    <a href="<?php echo esc_url( home_url( '/login/' ) ); ?>" style="color: #D74690; text-decoration: none; font-weight: 600;">&laquo; Kembali ke Halaman Masuk</a>
                </div>
            <?php else : ?>

                <?php if ( !empty($errors) ) : ?>
                    <div class="register-error">
                        <ul style="margin:0; padding-left:15px;">
                            <?php foreach ( $errors as $error ) : ?>
                                <li><?php echo esc_html( $error ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
                    <?php wp_nonce_field( 'ikapsi_register_action', 'register_nonce' ); ?>
                    
                    <div class="register-form-grid">
                        
                        <div class="input-group full-width-col">
                            <label for="full_name">Nama Lengkap *</label>
                            <input type="text" id="full_name" name="full_name" value="<?php echo isset($_POST['full_name']) ? esc_attr($_POST['full_name']) : ''; ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="username">Username *</label>
                            <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? esc_attr($_POST['username']) : ''; ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="email">Alamat Email *</label>
                            <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="phone_number">No. Telepon / WhatsApp *</label>
                            <input type="tel" id="phone_number" name="phone_number" value="<?php echo isset($_POST['phone_number']) ? esc_attr($_POST['phone_number']) : ''; ?>" placeholder="Contoh: 08123456789" required>
                        </div>

                        <div class="input-group">
                            <label for="tahun_lulus">Tahun Lulus *</label>
                            <input type="number" id="tahun_lulus" name="tahun_lulus" min="1950" max="<?php echo date('Y'); ?>" value="<?php echo isset($_POST['tahun_lulus']) ? esc_attr($_POST['tahun_lulus']) : ''; ?>" required>
                        </div>

                        <div class="input-group full-width-col">
                            <label for="fakultas">Fakultas *</label>
                            <input type="text" id="fakultas" name="fakultas" value="<?php echo isset($_POST['fakultas']) ? esc_attr($_POST['fakultas']) : 'Psikologi'; ?>" required>
                        </div>

                        <div class="input-group full-width-col">
                            <label for="alamat">Alamat Lengkap *</label>
                            <textarea id="alamat" name="alamat" rows="3" required><?php echo isset($_POST['alamat']) ? esc_textarea($_POST['alamat']) : ''; ?></textarea>
                        </div>

                        <div class="input-group">
                            <label for="password">Kata Sandi *</label>
                            <input type="password" id="password" name="password" required>
                        </div>

                        <div class="input-group">
                            <label for="password_confirm">Konfirmasi Sandi *</label>
                            <input type="password" id="password_confirm" name="password_confirm" required>
                        </div>

                    </div>

                    <button type="submit" name="submit_register" class="register-submit-btn">Kirim Pendaftaran</button>
                </form>

            <?php endif; ?>

        </div>

        <?php if ( empty($success_message) ) : ?>
        <div class="register-card-footer">
            Sudah memiliki akun? <a href="<?php echo esc_url( home_url( '/login/' ) ); ?>">Masuk di sini</a>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
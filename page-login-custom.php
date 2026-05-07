<?php
/**
 * Template Name: Login Custom
 * Description: Halaman login kustom dengan desain minimalis dan elegan.
 */

// Jika user sudah login, langsung lempar ke halaman member area
if ( is_user_logged_in() ) {
    wp_redirect( home_url( '/user/' ) );
    exit;
}

get_header('internal'); ?>

<style>
    /* Container Utama */
    .login-custom-section {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        padding: 40px 20px;
    }

    .login-card {
        background: #ffffff;
        width: 100%;
        max-width: 450px;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(215, 70, 144, 0.1); /* Shadow disesuaikan rona pink */
        border: 1px solid #eaeaea;
        overflow: hidden;
    }

    /* Revisi: Sisi atas menggunakan Pink yang sama dengan tombol */
    .login-card-header {
        background-color: #D74690; 
        padding: 40px 30px;
        text-align: center;
        color: #ffffff; /* Teks putih agar kontras di atas pink */
    }

    .login-card-header h2 {
        margin: 0;
        font-family: 'Georgia', serif;
        font-size: 28px;
        letter-spacing: 1px;
    }

    .login-card-header p {
        margin: 10px 0 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .login-form-container {
        padding: 40px 35px;
    }

    /* Styling Default WP Login Form */
    #loginform {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Revisi: Dark Red khusus digunakan untuk Font */
    .login-username label, .login-password label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #4A0A1F; /* Dark Red diaplikasikan ke font label */
        font-size: 14px;
    }

    #user_login, #user_pass {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 15px;
        transition: border-color 0.3s;
        box-sizing: border-box;
    }

    #user_login:focus, #user_pass:focus {
        border-color: #D74690;
        outline: none;
    }

    .login-remember {
        font-size: 13px;
        color: #777;
    }

    .login-submit {
        margin-top: 10px;
    }

    #wp-submit {
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
        letter-spacing: 1px;
    }

    #wp-submit:hover {
        background-color: #bf3a7d;
        transform: translateY(-2px);
    }

    .login-card-footer {
        text-align: center;
        padding: 0 35px 40px;
        font-size: 14px;
        color: #666;
    }

    .login-card-footer a {
        color: #D74690;
        text-decoration: none;
        font-weight: 600;
    }

    /* Error Message */
    .login-error {
        background-color: #FFF0F6; /* Soft pink background */
        color: #4A0A1F; /* Dark red text */
        padding: 12px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 20px;
        border-left: 4px solid #D74690;
    }
</style>

<div class="login-custom-section">
    <div class="login-card">
        <div class="login-card-header">
            <h2>Masuk</h2>
            <p>Portal Alumni Psikologi UNDIP</p>
        </div>

        <div class="login-form-container">
            <?php 
            // Cek apakah ada parameter error di URL
            if ( isset($_GET['login']) && $_GET['login'] == 'failed' ) {
                echo '<div class="login-error">Kombinasi email/username dan password salah. Silakan coba lagi.</div>';
            }
            ?>

            <?php 
            wp_login_form( array(
                'echo'           => true,
                'redirect'       => home_url( '/user/' ), // Redirect setelah login berhasil
                'form_id'        => 'loginform',
                'label_username' => __( 'Username atau Email' ),
                'label_password' => __( 'Kata Sandi' ),
                'label_remember' => __( 'Ingat Saya' ),
                'label_log_in'   => __( 'Masuk Sekarang' ),
                'remember'       => true,
                'value_remember' => true,
            ) ); 
            ?>
        </div>

        <div class="login-card-footer">
            Belum punya akun? <a href="<?php echo esc_url( home_url( '/register/' ) ); ?>">Daftar Alumni</a>
        </div>
    </div>
</div>

<?php get_footer(); ?>
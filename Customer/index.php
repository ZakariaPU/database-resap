<?php
session_start();
include( 'class/adminback.php' );
$obj = new adminback();

// Process order submission
if ( isset( $_POST[ 'order_btn' ] ) ) {
    // Call the function to handle order placement
    $order_msg = $obj->place_order( $_POST );
    // Ensure this function is defined in your adminback class
}
?>

<?php
include ( 'includes/head.php' )
?>

<style>
.order-card .auth-box,
.order-card h3,
.order-card h6,
.order-card label,
.order-card legend,
.order-card input,
.order-card textarea,
.order-card p {
    color: black !important;
}

.order-card {
    padding: 15px;
    box-sizing: border-box;
    /* Pastikan padding tidak mempengaruhi ukuran total */
}

.form-group,
.input-group {
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    /* Agar elemen bisa membungkus ke bawah */
}

.form-group label,
.input-group label {
    width: 100%;
    max-width: 200px;
    /* Lebar maksimal label */
    margin-bottom: 5px;
}

.form-control,
.form-group textarea {
    width: 100%;
    /* Mengisi lebar penuh */
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    /* Termasuk padding dan border dalam ukuran */
    min-width: 250px;
    /* Lebar minimum untuk input */
}

.checkbox {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.checkbox label {
    margin-right: 10px;
    flex-shrink: 0;
    width: 100%;
    /* Agar label mengisi lebar penuh */
}

/* Responsif untuk textbox */
.checkbox textarea {
    width: 100%;
    /* Mengisi lebar penuh */
    box-sizing: border-box;
    /* Termasuk padding dan border dalam ukuran */
}

@media (max-width: 768px) {

    .form-group,
    .input-group,
    .checkbox {
        flex-direction: column;
        /* Kolom untuk tampilan mobile */
        align-items: flex-start;
    }

    .form-group label,
    .input-group label,
    .checkbox label {
        width: 100%;
        /* Label mengikuti lebar penuh */
        margin-bottom: 5px;
    }

    .form-control,
    .checkbox textarea {
        width: 100%;
        /* Mengisi lebar penuh */
        min-width: 250px;
        /* Menetapkan lebar minimum */
        max-width: 100%;
        /* Menetapkan lebar maksimum 100% */
    }
}
</style>

<body>
    <section class='order-form p-fixed d-flex text-center bg-primary common-img-bg'>
        <div class='container'>
            <div class='row'>
                <div class='col-sm-12'>
                    <div class='order-card card-block auth-body mr-auto ml-auto'>
                        <form action='' method='post' class='md-float-material'>
                            <div class='auth-box'>
                                <h3 class='text-left txt-primary'>Resap Form Pemesanan</h3>
                                <h6 class='text-success text-left'>
                                    <?php if ( isset( $order_msg ) ) {
    echo $order_msg;
}
?>
                                </h6>
                                <hr />

                                <fieldset>
                                    <legend>Informasi Pelanggan</legend>
                                    <div class='input-group'>
                                        <label for='first_name'>Nama Depan:</label>
                                        <input type='text' class='form-control' placeholder='Nama Depan'
                                            name='first_name' id='first_name' required>
                                    </div>
                                    <div class='input-group'>
                                        <label for='last_name'>Nama Belakang:</label>
                                        <input type='text' class='form-control' placeholder='Nama Belakang'
                                            name='last_name' id='last_name' required>
                                    </div>
                                    <div class='input-group'>
                                        <label for='email'>Email:</label>
                                        <input type='email' class='form-control' placeholder='Email' name='email'
                                            id='email' required>
                                    </div>
                                    <div class='input-group'>
                                        <label for='phone'>No. HP:</label>
                                        <input type='tel' class='form-control' placeholder='No. HP' name='whatsapp'
                                            id='phone' required>
                                    </div>
                                    <div class='input-group'>
                                        <label for='instagram_username'>Username Instagram:</label>
                                        <input type='text' class='form-control' placeholder='Username Instagram'
                                            name='usernameIG' id='instagram_username'>
                                        <!-- Opsional, tidak ada required -->
                                    </div>
                                </fieldset>

                                <fieldset>
                                    <legend>Detail Pesanan</legend>
                                    <div class='input-group'>
                                        <label for='shipping_address'>Alamat Pengiriman:</label>
                                        <input type='text' class='form-control' placeholder='Alamat Pengiriman'
                                            name='shipping_address' id='shipping_address' required>
                                    </div>
                                    <div class='form-group'>
                                        <label>Jenis Menu:</label>

                                        <div class='checkbox'>
                                            <label><input type='checkbox' name='jenis_menu[]' value='Healthy Lite'>
                                                Healthy Lite</label>
                                            <textarea class='form-control' name='keterangan_healthy_lite'
                                                placeholder='Keterangan (opsional)'></textarea>
                                        </div>

                                        <div class='checkbox'>
                                            <label><input type='checkbox' name='jenis_menu[]' value='Healthy Gaurmet'>
                                                Healthy Gaurmet</label>
                                            <textarea class='form-control' name='keterangan_healthy_lite_tidak_pedas'
                                                placeholder='Keterangan (opsional)'></textarea>
                                        </div>

                                        <div class='checkbox'>
                                            <label><input type='checkbox' name='jenis_menu[]' value='Nusantara Hype'>
                                                Nusantara Hype</label>
                                            <textarea class='form-control' name='keterangan_nusantara_hype'
                                                placeholder='Keterangan (opsional)'></textarea>
                                        </div>

                                        <div class='checkbox'>
                                            <label><input type='checkbox' name='jenis_menu[]' value='Nusantara Fit'>
                                                Nusantara Fit </label>
                                            <textarea class='form-control' name='keterangan_nusantarafit'
                                                placeholder='Keterangan (opsional)'></textarea>
                                        </div>

                                        <!-- Tambahkan checkbox lainnya sesuai kebutuhan -->
                                    </div>

                                    <div class='form-group'>
                                        <label>Pilihan Pesanan:</label>
                                        <div class='radio'>
                                            <label>
                                                <input type='radio' name='PilihanPesanan' value='Makan Siang' required>
                                                Makan Siang
                                            </label>
                                        </div>
                                        <div class='radio'>
                                            <label>
                                                <input type='radio' name='PilihanPesanan' value='Makan Malam'>
                                                Makan Malam
                                            </label>
                                        </div>
                                        <div class='radio'>
                                            <label>
                                                <input type='radio' name='PilihanPesanan' value='Lengkap'>
                                                Lengkap ( Makan Siang & Makan Malam )
                                            </label>
                                        </div>
                                    </div>

                                    <div class='input-group'>
                                        <label for='quantity'>Jumlah:</label>
                                        <input type='number' class='form-control' placeholder='Jumlah' name='quantity'
                                            id='quantity' required>
                                    </div>

                                    <div class='input-group'>
                                        <label for='order_date'>Tanggal Pemesanan:</label>
                                        <input type='date' class='form-control' name='order_date' id='order_date'
                                            required>
                                    </div>
                                </fieldset>

                                <div class='row m-t-30'>
                                    <div class='col-md-12'>
                                        <input type='submit' name='order_btn' class='btn btn-primary btn-md btn-block'
                                            value='Pesan Sekarang'>
                                    </div>
                                </div>
                                <hr />
                                <p class='text-dark h5'>RESAP KITCHEN</p>
                                <p class='text-dark'>Terima kasih telah memesan!</p>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
include ( 'includes/script.php' )
?>
<?php
if ( isset( $_GET[ 'status' ] ) ) {
    $order_id = $_GET[ 'id' ];
    if ( $_GET[ 'status' ] == 'orderEdit' ) {
        $order_info = $obj->show_orderb2c_by_id( $order_id );
        $order = mysqli_fetch_assoc($order_info);
$order['Keterangan'] = json_decode($order['Keterangan'], true);

    }
}

if ( isset( $_POST[ 'update_order' ] ) ) {
    // Proses update data
    $update_msg = $obj->update_orderb2c( $_POST );

    // Setelah update berhasil, panggil kembali show_orderb2c_by_id untuk mendapatkan data yang telah diperbarui
    $order_info = $obj->show_orderb2c_by_id( $_POST[ 'order_id' ] );
    $order = mysqli_fetch_assoc( $order_info );

    // Dekode JSON Keterangan menjadi array asosiatif setelah update
    $order['Keterangan'] = json_decode($order['Keterangan'], true);
}

?>

<div class='container'>
    <h4>Edit Order Information</h4>

    <h6>
        <?php
if ( isset( $update_msg ) ) {
    echo  $update_msg;
}
?>
    </h6>
    <form action='' method='POST'>
        <div class='form-group'>
            <h4>Tanggal Pesanan</h4>
            <input type='date' name='tgl_pesanan' class='form-control' value="<?php echo $order['TanggalPesanan'] ?>"
                required>
            <div class='form-group'>
                <h4>Jenis Menu</h4>
                <input type='text' name='jenis_menu' class='form-control' value="<?php echo $order['JenisMenu'] ?>"
                    required>
            </div>
            <div class='form-group'>
                <h4>Jumlah Pesanan</h4>
                <input type='number' name='jmlh_pesanan' class='form-control'
                    value="<?php echo $order['JumlahPesanan'] ?>" required>
            </div>
                    <div class='form-group'>
            <h4>Pilihan Pesanan</h4>
            <div>
                <input type='radio' name='PilihanPesanan' value='Makan Siang' <?php echo ($order['PilihanPesanan'] == 'Makan Siang') ? 'checked' : ''; ?>> Makan Siang
            </div>
            <div>
                <input type='radio' name='PilihanPesanan' value='Makan Malam' <?php echo ($order['PilihanPesanan'] == 'Makan Malam') ? 'checked' : ''; ?>> Makan Malam
            </div>
            <div>
                <input type='radio' name='PilihanPesanan' value='Lengkap' <?php echo ($order['PilihanPesanan'] == 'Lengkap') ? 'checked' : ''; ?>> Lengkap
            </div>
        </div>
            <div class='form-group'>
                <h4>Total Harga</h4>
                <input type='number' name='ttl_harga' class='form-control' value="<?php echo $order['TotalHarga'] ?>"
                    required>
            </div>

            <div class='form-group'>
                <h4>Alamat</h4>
                <input type='text' name='alamat' class='form-control' value="<?php echo $order['Alamat'] ?>" required>
            </div>
            <div class='form-group'>
    <h4>Jenis Menu</h4>
    <?php
    $jenis_menu = explode(', ', $order['JenisMenu']); // Explode jenis menu

    // Check if 'Keterangan' exists to avoid undefined index error
    $keterangan = isset($order['Keterangan']) ? $order['Keterangan'] : [];
    ?>

    <div class="checkbox">
        <label><input type="checkbox" name="jenis_menu[]" value="Healthy Lite" <?php echo in_array('Healthy Lite', $jenis_menu) ? 'checked' : ''; ?>> Healthy Lite</label>
        <textarea class="form-control" name="keterangan_healthy_lite"><?php echo isset($keterangan['Healthy Lite']) ? $keterangan['Healthy Lite'] : ''; ?></textarea>
    </div>

    <div class="checkbox">
        <label><input type="checkbox" name="jenis_menu[]" value="Healthy Gourmet" <?php echo in_array('Healthy Gourmet', $jenis_menu) ? 'checked' : ''; ?>> Healthy Gourmet</label>
        <textarea class="form-control" name="keterangan_healthy_gourmet"><?php echo isset($keterangan['Healthy Gourmet']) ? $keterangan['Healthy Gourmet'] : ''; ?></textarea>
    </div>

    <div class="checkbox">
        <label><input type="checkbox" name="jenis_menu[]" value="Nusantara Hype" <?php echo in_array('Nusantara Hype', $jenis_menu) ? 'checked' : ''; ?>> Nusantara Hype</label>
        <textarea class="form-control" name="keterangan_nusantara_hype"><?php echo isset($keterangan['Nusantara Hype']) ? $keterangan['Nusantara Hype'] : ''; ?></textarea>
    </div>

    <div class="checkbox">
        <label><input type="checkbox" name="jenis_menu[]" value="Nusantara Fit" <?php echo in_array('Nusantara Fit', $jenis_menu) ? 'checked' : ''; ?>> Nusantara Fit</label>
        <textarea class="form-control" name="keterangan_nusantara_fit"><?php echo isset($keterangan['Nusantara Fit']) ? $keterangan['Nusantara Fit'] : ''; ?></textarea>
    </div>
</div>

            <input type='hidden' name='order_id'
                value="<?php echo isset($order['OrderID']) ? $order['OrderID'] : ''; ?>">

            <div class='form-group'>
                <input type='submit' name='update_order' class='btn btn-primary' value='Update Order'>
            </div>
    </form>
</div>
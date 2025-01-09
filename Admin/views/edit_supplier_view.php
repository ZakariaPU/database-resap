<?php
if ( isset( $_GET[ 'status' ] ) ) {
    $supp_id = $_GET[ 'id' ];
    if ( $_GET[ 'status' ] == 'suppEdit' ) {
        $supp_info = $obj->show_supplier_by_id( $supp_id );
        $supp = mysqli_fetch_assoc( $supp_info );
    }
}

if ( isset( $_POST[ 'update_supp' ] ) ) {
    $update_msg =  $obj->update_supp( $_POST );
    $supp_info = $obj->show_supplier_by_id( $_POST[ 'supp_id' ] );
    $supp = mysqli_fetch_assoc( $supp_info );
}

?>

<div class='container'>
    <h4>Edit Supplier Information</h4>

    <h6>
        <?php
if ( isset( $update_msg ) ) {
    echo  $update_msg;
}
?>
    </h6>
    <form action='' method='POST'>
        <div class='form-group'>
            <h4>Nama Supplier</h4>
            <input type='text' name='supp_nama' class='form-control' value="<?php echo $supp['full_name'] ?>" required>
            <div class='form-group'>
                <h4>Nomor Telepon</h4>
                <input type='number' name='supp_nomor_telepon' class='form-control'
                    value="<?php echo $supp['phone_number'] ?>" required>
            </div>
            <div class='form-group'>
                <h4>Alamat</h4>
                <input type='text' name='supp_alamat' class='form-control' value="<?php echo $supp['addres_supp'] ?>"
                    required>
            </div>
            <div class='form-group'>
                <h4>Keterangan</h4>
                <input type='text' name='keterangan' class='form-control' value="<?php echo $supp['keterangan'] ?>"
                    required>
            </div>


            <!-- <input type = 'hidden' name = 'order_id' value = "<?php echo $supp['supplier_id'] ?>"> -->
            <input type='hidden' name='supp_id'
                value="<?php echo isset($supp['supplier_id']) ? $supp['supplier_id'] : ''; ?>">

            <div class='form-group'>
                <input type='submit' name='update_supp' class='btn btn-primary' value='Update Supplier'>
            </div>
    </form>
</div>
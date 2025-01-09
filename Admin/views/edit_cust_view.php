<?php
if ( isset( $_GET[ 'status' ] ) ) {
    $cust_id = $_GET[ 'id' ];
    if ( $_GET[ 'status' ] == 'custEdit' ) {
        $cust_info = $obj->show_customer_by_id( $cust_id );
        $cust = mysqli_fetch_assoc( $cust_info );
    }
}


if ( isset( $_POST[ 'update_cust' ] ) ) {
    $update_msg =  $obj->update_cust( $_POST );
        // Setelah update berhasil, panggil kembali show_customer_by_id untuk mendapatkan data yang telah diperbarui
        $cust_info = $obj->show_customer_by_id( $_POST[ 'cust_id' ] );
        $cust = mysqli_fetch_assoc( $cust_info );
}
?>

<div class='container'>
    <h4>Edit Customer Information</h4>

    <h6>
        <?php
if ( isset( $update_msg ) ) {
    echo  $update_msg;
}
?>
    </h6>
    <form action='' method='POST'>
        <div class='form-group'>
            <h4>Nama Entitas</h4>
            <input type='text' name='cust_nama_entitas' class='form-control' value="<?php echo $cust['nama_entitas'] ?>"
                required>
            <div class='form-group'>
                <h4>Nomor Telepon Entitas</h4>
                <input type='number' name='cust_nomor_telepon_entitas' class='form-control'
                    value="<?php echo $cust['phone_number_entitas'] ?>" required>
            </div>
            <div class='form-group'>
                <h4>Alamat Entitas</h4>
                <input type='text' name='cust_alamat_entitas' class='form-control'
                    value="<?php echo $cust['alamat_entitas'] ?>" required>
            </div>
            <div class='form-group'>
                <h4>Website</h4>
                <input type='text' name='cust_website' class='form-control' value="<?php echo $cust['website'] ?>"
                    required>
            </div>

            <div class='form-group'>
                <h4>Sosmed</h4>
                <input type='text' name='cust_sosmed' class='form-control' value="<?php echo $cust['sosmed'] ?>"
                    required>
            </div>
            <div class='form-group'>
                <h4>Nama PIC</h4>
                <input type='text' name='cust_nama_pic' class='form-control' value="<?php echo $cust['nama_pic'] ?>"
                    required>
            </div>
            <div class='form-group'>
                <h4>Nomor Telepon PIC</h4>
                <input type='number' name='cust_nomor_telepon_pic' class='form-control'
                    value="<?php echo $cust['phone_number_pic'] ?>" required>
            </div>
            <div class='form-group'>
                <h4>Alamat PIC</h4>
                <input type='text' name='cust_alamat_pic' class='form-control' value="<?php echo $cust['alamat_pic'] ?>"
                    required>
            </div>
            <div class='form-group'>
                <h4>Customer Status</h4>
                <select name='cust_status' class='form-control'>
                    <option disabled selected>--Select--</option>
                    <option value='1' <?php  if ( $cust[ 'cust_status' ] == 1 ) {
    echo 'Selected';
}
?>>Cold</option>
                    <option value='2' <?php  if ( $cust[ 'cust_status' ] == 2 ) {
    echo 'Selected';
}
?>>Warm</option>
                    <option value='3' <?php  if ( $cust[ 'cust_status' ] == 3 ) {
    echo 'Selected';
}
?>>Hot</option>
                </select>
            </div>

            <!-- <input type = 'hidden' name = 'order_id' value = "<?php echo $cust['cust_id'] ?>"> -->
            <input type='hidden' name='cust_id' value="<?php echo isset($cust['cust_id']) ? $cust['cust_id'] : ''; ?>">

            <div class='form-group'>
                <input type='submit' name='update_cust' class='btn btn-primary' value='Update Customer'>
            </div>
    </form>
</div>
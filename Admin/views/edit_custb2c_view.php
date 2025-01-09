<?php
if ( isset( $_GET[ 'status' ] ) ) {
    $cust_id = $_GET[ 'id' ];
    if ( $_GET[ 'status' ] == 'custEdit' ) {
        $cust_info = $obj->show_custb2c_by_id( $cust_id );
        $cust = mysqli_fetch_assoc( $cust_info );
    }
}

if ( isset( $_POST[ 'update_cust' ] ) ) {
    // Proses update data
    $update_msg = $obj->update_custb2c( $_POST );

    // Setelah update berhasil, panggil kembali show_custb2c_by_id untuk mendapatkan data yang telah diperbarui
    $cust_info = $obj->show_custb2c_by_id( $_POST[ 'cust_id' ] );
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
            <h4>Nama Depan</h4>
            <input type='text' name='cust_nama_depan' class='form-control' value="<?php echo $cust['NamaDepan'] ?>"
                required>
            <div class='form-group'>
                <h4>Nama Belakang</h4>
                <input type='text' name='cust_nama_belakang' class='form-control'
                    value="<?php echo $cust['NamaBelakang'] ?>" required>
            </div>
            <div class='form-group'>
                <h4>Alamat</h4>
                <input type='text' name='cust_alamat' class='form-control' value="<?php echo $cust['Alamat'] ?>"
                    required>
            </div>
            <div class='form-group'>
                <h4>Email</h4>
                <input type='text' name='email' class='form-control' value="<?php echo $cust['Email'] ?>" required>
            </div>

            <div class='form-group'>
                <h4>Whatsapp</h4>
                <input type='number' name='whatsapp' class='form-control' value="<?php echo $cust['Whatsapp'] ?>"
                    required>
            </div>
            <div class='form-group'>
                <h4>Username IG</h4>
                <input type='text' name='usernameIg' class='form-control' value="<?php echo $cust['UsernameIG'] ?>"
                    required>
            </div>
            <input type='hidden' name='cust_id'
                value="<?php echo isset($cust['CustomerID']) ? $cust['CustomerID'] : ''; ?>">

            <div class='form-group'>
                <input type='submit' name='update_cust' class='btn btn-primary' value='Update Customer'>
            </div>
    </form>
</div>
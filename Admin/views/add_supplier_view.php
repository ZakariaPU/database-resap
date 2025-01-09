<?php

if ( isset( $_POST[ 'add_supp' ] ) ) {
    $add_msg = $obj->add_supp( $_POST );
}
?>
<div class='container'>
    <h2>Add Supplier Resap</h2>
    <br>
    <h6 class='text-success'>
        <?php
        if (isset($add_msg)) {
            echo $add_msg;
        }
        ?>
    </h6>
    <!-- Hasil Pencarian -->
    <form action='' method='POST' id='supplierForm'>
        <div class='form-group'>
            <h4>Nama Supplier</h4>
            <input type='text' name='nama_supplier' class='form-control' required>
        </div>

        <div class='form-group'>
            <h4>Nomor Handphone</h4>
            <input type='text' name='phone_number' class='form-control' required>
        </div>

        <div class='form-group'>
            <h4>Alamat Supplier</h4>
            <input type='text' name='alamat_supplier' class='form-control' required>
        </div>


        <div class='form-group'>
            <h4>Keterangan</h4>
            <input type='text' name='keterangan' class='form-control' required>
        </div>

        <div id="buttonSection">
            <div class='form-group'>
                <button type="submit" name="add_supp" class="btn btn-primary btn-block">Submit</button>
            </div>
        </div>
    </form>
</div>
<?php

if ( isset( $_POST[ 'add_user' ] ) ) {
    $add_msg = $obj->add_user( $_POST );
}
?>

<div class='container'>
    <h2>Add User Resap Kitchen</h2>
    <br>
    <h6 class='text-success'>
        <?php
if ( isset( $add_msg ) ) {
    echo $add_msg;
}
?>
    </h6>
    <form action='' method='POST'>
        <div class='form-group'>
            <h4>Nama Lengkap</h4>
            <input type='text' name='full_name' class='form-control' required>
        </div>

        <div class='form-group'>
            <h4>Nomor Telepon</h4>
            <input type='text' name='user_phone' class='form-control' required>
        </div>

        <div class='form-group'>
            <h4>Username</h4>
            <input type='text' name='username' class='form-control' required>
        </div>

        <div class='form-group'>
            <h4>Password</h4>
            <input type='password' name='pass_word' class='form-control' required>
        </div>

        <div class='form-group'>
            <h4>Role</h4>
            <select name='user_role' class='form-control'>
                <option disabled selected>--Select--</option>
                <option value='1'>Moderator</option>
                <option value='2'>Admin Resap</option>
                <option value='3'>Admin Inventory</option>
                <option value='4'>Admin B2C</option>
            </select>
        </div>

        <div class='form-group'>
            <input type='submit' name='add_user' class='btn btn-primary'>
        </div>
    </form>
</div>
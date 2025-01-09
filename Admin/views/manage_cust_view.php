<?php
$arry = $obj->show_cust_data();

if ( isset( $_GET[ 'status' ] ) ) {
    $cust_id = $_GET[ 'id' ];
    if ( $_GET[ 'status' ] == 'delete' ) {
        $del_msg = $obj->delete_cust( $cust_id );
    }
    $arry = $obj->show_cust_data();
}

// if ( isset( $_POST[ 'update_status_btn' ] ) ) {
//     $status_msg = $obj->update_status_cust( $_POST );
// }

// After form submission and status update
if (isset($_POST['update_status_btn'])) {
    $status_msg = $obj->update_status_cust($_POST);
    // Re-fetch customer data to display the updated status
    $arry = $obj->show_cust_data();
}

if ( isset( $_GET[ 'bcari' ] ) ) {
    $filter_name = $_GET[ 'filter_name' ];
    if ( !empty( $filter_name ) ) {
        $search_query = $obj->search_cust( $filter_name );

        $arry = array();
        while ( $search = mysqli_fetch_assoc( $search_query ) ) {
            $arry[] = $search;
        }
    }
}
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class='container'>
    <h2>Manage Customer</h2>

    <h4 class='text-success'>
        <?php
if ( isset( $del_msg ) ) {
    echo $del_msg;
}
?>
    </h4>

    <form action='' method='get'>
        <div class='form-group'>
            <div class='input-group'>
                <input type='text' class='form-control' id='filter_name' name='filter_name'
                    placeholder='Search by Customer Name '>
                <div class='input-group-append'>
                    <button type='submit' class='btn btn-primary' name='bcari'>Search</button>
                </div>
            </div>
        </div>
    </form>

    <div class='table-responsive'>
        <table class='table'>
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Entitas</th>
                    <th>Nomor Telepon</th>
                    <th>Alamat Entitas</th>
                    <th>Website</th>
                    <th>Sosmed</th>
                    <th>PIC</th>
                    <th>Nomor HP</th>
                    <th>Alamat PIC</th>
                    <th>Sumber Informasi Mengetahui Resap</th>
                    <th>Status</th>
                    <th>Update Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
foreach ( $arry as $cust ) {
    ?>
                <tr>
                    <td> <?php echo $cust[ 'cust_id' ] ?> </td>
                    <td> <?php echo $cust[ 'nama_entitas' ] ?> </td>
                    <td> <?php echo $cust[ 'phone_number_entitas' ] ?> </td>
                    <td> <?php echo $cust[ 'alamat_entitas' ] ?> </td>
                    <td> <?php echo $cust[ 'website' ] ?> </td>
                    <td> <?php echo $cust[ 'sosmed' ] ?> </td>
                    <td> <?php echo $cust[ 'nama_pic' ] ?> </td>
                    <td> <?php echo $cust[ 'phone_number_pic' ] ?> </td>
                    <td> <?php echo $cust[ 'alamat_pic' ] ?> </td>
                    <td> <?php echo $cust[ 'sumber_info' ] ?> </td>
                    <td> <?php echo $cust['cust_status']; ?> </td>

                    <td>
                        <form action='manage_cust.php' method='POST'>
                            <select name='update_status'>
                                <option>Select</option>
                                <option value='1'>Cold</option>
                                <option value='2'>Warm</option>
                                <option value='3'>Hot</option>
                            </select> <br>
                            <input type='hidden' name='cust_id' value="<?php echo $cust['cust_id']  ?>">
                            <input type='submit' value='update' name='update_status_btn'>
                        </form>
                        
                        

                    </td>
                    <td>
                        <a href="edit_cust.php?status=custEdit&&id=<?php echo $cust['cust_id'] ?>"
                            class='btn btn-sm btn-warning'>Edit </a>
                        <a href="?status=delete&&id=<?php echo $cust['cust_id'] ?>"
                            class='btn btn-sm btn-danger'>Delete</a>

                    </td>
                </tr>

                <?php }
    ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$arry = $obj->show_supp_data();

if ( isset( $_GET[ 'status' ] ) ) {
    $supp_id = $_GET[ 'id' ];
    if ( $_GET[ 'status' ] == 'delete' ) {
        $del_msg = $obj->delete_supp( $supp_id );
    }
    $arry = $obj->show_supp_data();
}

if ( isset( $_GET[ 'bcari' ] ) ) {
    $filter_name = $_GET[ 'filter_name' ];
    if ( !empty( $filter_name ) ) {
        $search_query = $obj->search_supp( $filter_name );

        $arry = array();
        while ( $search = mysqli_fetch_assoc( $search_query ) ) {
            $arry[] = $search;
        }
    }
}
?>

<div class='container'>
    <h2>Manage Supplier</h2>

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
                    placeholder='Search by Supplier Name '>
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
                    <th>Supplier ID</th>
                    <th>Nama Supplier</th>
                    <th>Nomor Telepon</th>
                    <th>Alamat Supplier</th>
                    <th>Keterangan</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
foreach ( $arry as $supp ) {
    ?>
                <tr>
                    <td> <?php echo $supp[ 'supplier_id' ] ?> </td>
                    <td> <?php echo $supp[ 'full_name' ] ?> </td>
                    <td> <?php echo $supp[ 'phone_number' ] ?> </td>
                    <td> <?php echo $supp[ 'addres_supp' ] ?> </td>
                    <td> <?php echo $supp[ 'keterangan' ] ?> </td>


                    <td>
                        <a href="edit_supplier.php?status=suppEdit&&id=<?php echo $supp['supplier_id'] ?>"
                            class='btn btn-sm btn-warning'>Edit </a>
                        <a href="?status=delete&&id=<?php echo $supp['supplier_id'] ?>"
                            class='btn btn-sm btn-danger'>Delete</a>

                    </td>
                </tr>

                <?php }
    ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$arry = $obj->show_data_cust();

if ( isset( $_GET[ 'status' ] ) ) {
    $cust_id = $_GET[ 'id' ];
    if ( $_GET[ 'status' ] == 'delete' ) {
        $del_msg = $obj->delete_customer( $cust_id );
    }
    $arry = $obj->show_data_cust();
}


if ( isset( $_GET[ 'bcari' ] ) ) {
    $filter_name = $_GET[ 'filter_name' ];
    if ( !empty( $filter_name ) ) {
        $search_query = $obj->search_custb2c( $filter_name );

        $arry = array();
        while ( $search = mysqli_fetch_assoc( $search_query ) ) {
            $arry[] = $search;
        }
    }
}

?>

<div class='container'>
    <h2>Data Customer Resap B2C</h2>

    <h4 class='text-success'>
        <?php
if ( isset( $del_msg ) ) {
    echo $del_msg;
}

if ( isset( $import_message ) ) {
    echo $import_message;
}
?>
    </h4>
        <form action='' method='get'>
        <div class='form-group'>
            <div class='input-group'>
                <input type='text' class='form-control' id='filter_name' name='filter_name'
                    placeholder='Search by Customer First Name '>
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
                    <th>Nama Depan</th>
                    <th>Nama Belakang</th>
                    <th>Email</th>
                    <th>Whatsapp</th>
                    <th>Username Instagram</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
foreach ( $arry as $cust ) {
    ?>
                <tr>
                    <td> <?php echo $cust[ 'CustomerID' ] ?> </td>
                    <td> <?php echo $cust[ 'NamaDepan' ] ?> </td>
                    <td> <?php echo $cust[ 'NamaBelakang' ] ?> </td>
                    <td> <?php echo $cust[ 'Email' ] ?> </td>
                    <td> <?php echo $cust[ 'Whatsapp' ] ?> </td>
                    <td> <?php echo $cust[ 'UsernameIG' ] ?> </td>
                    <td>
                        <a href="edit_custb2c.php?status=custEdit&&id=<?php echo $cust['CustomerID'] ?>"
                            class='btn btn-sm btn-warning'>Edit </a>
                        <a href="?status=delete&&id=<?php echo $cust['CustomerID'] ?>"
                            class='btn btn-sm btn-danger'>Delete</a>

                    </td>
                </tr>
                <?php }
    ?>
            </tbody>
        </table>
    </div>
</div>
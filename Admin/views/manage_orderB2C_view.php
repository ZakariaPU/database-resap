<?php
$arry = $obj->show_data_order();
$totalPrice = 0;
// Initialize total price

if ( isset( $_GET[ 'status' ] ) ) {
    $order_id = $_GET[ 'id' ];
    if ( $_GET[ 'status' ] == 'delete' ) {
        $del_msg = $obj->delete_orderb2c( $order_id );
    }
    $arry = $obj->show_data_order();
}

// Handle file import
if ( isset( $_POST[ 'import_excel' ] ) ) {
    $file = $_FILES[ 'import_file' ][ 'tmp_name' ];
    $import_message = $obj->importOrderData( $file );

    // Setelah proses import selesai, panggil kembali fungsi show_data_cust untuk menampilkan data yang baru diimport
    $arry = $obj->show_data_order();
} else {
    // Jika tidak ada proses import, tampilkan data biasa
    $arry = $obj->show_data_order();
}

function formatPrice( $price ) {
    return 'Rp ' . number_format( $price, 0, ',', '.' );
}

if ( isset( $_GET[ 'bcari' ] ) ) {
    $filter_name = $_GET[ 'filter_name' ];
    if ( !empty( $filter_name ) ) {
        $search_query = $obj->search_orderb2c( $filter_name );

        $arry = array();
        while ( $search = mysqli_fetch_assoc( $search_query ) ) {
            $arry[] = $search;
        }
    }
}

?>

<div class='container'>
    <h2>Data Order Resap B2C</h2>

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

    <!-- Form untuk Import Excel -->
    <form action='' method='POST' enctype='multipart/form-data'>
        <div class='form-group'>
            <label for='import_file'>Import Order Data ( Excel )</label>
            <div class='input-group'>
                <input type='file' name='import_file' id='import_file' class='form-control' accept='.xls, .xlsx'
                    required>
                <div class='input-group-append'>
                    <button type='submit' name='import_excel' class='btn btn-primary'>Import</button>
                </div>
            </div>
        </div>
    </form>
    
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
                    <th>Order ID</th>
                    <th>Nama Customer</th>
                    <th>Tanggal Pemesanan</th>
                    <th>Jenis Menu</th>
                    <th>Jumlah Pesanan</th>
                    <th>Total Harga</th>
                    <th>Alamat</th>
                    <th>Keterangan</th>
                    <th>Pilihan Pesanan</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
foreach ( $arry as $order ) {
        $totalPrice += $order[ 'TotalHarga' ];
    // Calculate total price
    ?>
                <tr>
                    <td> <?php echo $order[ 'OrderID' ] ?> </td>
                    <td> <?php echo $order[ 'NamaDepan' ] ?> </td>
                    <td> <?php echo $order[ 'TanggalPesanan' ] ?> </td>
                    <td> <?php echo $order[ 'JenisMenu' ] ?> </td>
                    <td> <?php echo $order[ 'JumlahPesanan' ] ?> </td>
                    <td> <?php echo formatPrice( $order[ 'TotalHarga' ] )?> </td>
                    <td> <?php echo $order[ 'Alamat' ] ?> </td>
                    <td> <?php echo $order[ 'Keterangan' ] ?> </td>
                    <td> <?php echo $order[ 'PilihanPesanan' ] ?> </td>
                    <td>
                        <a href="edit_orderb2c.php?status=orderEdit&&id=<?php echo $order['OrderID'] ?>"
                            class='btn btn-sm btn-warning'>Edit </a>
                        <a href="?status=delete&&id=<?php echo $order['OrderID'] ?>"
                            class='btn btn-sm btn-danger'>Delete</a>

                    </td>
                </tr>
                <?php }
    ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan='7' class='text-right'><strong>Total Order:</strong></td>
                    <td colspan='7' class='total-price'><?php echo formatPrice( $totalPrice );
?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
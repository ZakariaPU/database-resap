<?php
$arry = $obj->show_order_data();

$totalPrice = 0;
// Initialize total price

if ( isset( $_GET[ 'status' ] ) ) {
    $order_id = $_GET[ 'id' ];
    if ( $_GET[ 'status' ] == 'delete' ) {
        $del_msg = $obj->delete_order( $order_id );
    }
}

if ( isset( $_POST[ 'order_status_btn' ] ) ) {
    $status_msg = $obj->update_status_order( $_POST );
}

if ( isset( $_POST[ 'payment_status_btn' ] ) ) {
    $status_msg = $obj->update_status_payment( $_POST );
}

// Check if date filter is applied
// Check if date filter is applied
if (isset($_GET['filterDate'])) {
    $filterDate = $_GET['filterDate'];

    // Determine the start and end dates based on the selected filter option
    $startDate = "";
    $endDate = date("Y-m-d");

    if ($filterDate == date("Y-m-d")) { // Changed from "Y/m/d" to "Y-m-d"
        $startDate = date("Y-m-d");
    } elseif ($filterDate == date('Y-m-d', strtotime('-7 days'))) {
        $startDate = date('Y-m-d', strtotime('-7 days'));
    } elseif ($filterDate == date('Y-m-d', strtotime('-30 days'))) {
        $startDate = date('Y-m-d', strtotime('-30 days'));
    } elseif ($filterDate == date('Y-m-d', strtotime('-365 days'))) {
        $startDate = date('Y-m-d', strtotime('-365 days'));
    } elseif ($filterDate == "2020-01-01") {
        $startDate = "2020-01-01";
    }

    // Fetch filtered orders using the date range
    $arry = $obj->getOrdersByDateRange($startDate, $endDate);
} else {
    // If no date filter is applied, show all order data
    $arry = $obj->show_order_data();
}






if ( isset( $_GET[ 'bcari' ] ) ) {
    $filter_name = $_GET[ 'filter_name' ];
    if ( !empty( $filter_name ) ) {
        $search_query = $obj->search_order( $filter_name );

        $arry = array();
        while ( $search = mysqli_fetch_assoc( $search_query ) ) {
            $arry[] = $search;
        }
    }
}

function formatPrice( $price ) {
    return 'Rp ' . number_format( $price, 0, ',', '.' );
}
?>



<style>
.mydiv {
    width: 200px;
    position: absolute;
    right: 38px;
    overflow: hidden;
}
</style>

<div class='container'>
    <h2>Manage Order</h2>

    <div class="mydiv">
        <form action="" method="get" class="form">
            <select name="filterDate" id="filterDate" class="form-control" onchange="this.form.submit()">
                <option value="">Select Date</option> <!-- Placeholder option -->
                <option value="<?php echo date("Y-m-d") ?>">Today</option>
                <option value="<?php echo date('Y-m-d', strtotime('-7 days')) ?>">This week</option>
                <option value="<?php echo date('Y-m-d', strtotime('-30 days')) ?>">This Month</option>
                <option value="<?php echo date('Y-m-d', strtotime('-365 days')) ?>">This Year</option>
                <option value="2020-01-01">Life Time</option>
            </select>
        </form>
    </div>


    <h4 class='text-success'>
        <?php
if ( isset( $del_msg ) ) {
    echo $del_msg;
}
?>
    </h4>
    <br><br>

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
                    <th>Order Date</th>
                    <th>Delivery Date</th>
                    <th>Customer Name</th>
                    <th>User ID</th>
                    <th>Nama Menu</th>
                    <th>Quantity</th>
                    <th>Harga</th>
                    <th>Keterangan</th>
                    <th>Status Order</th>
                    <th></th>
                    <th>Payment Status</th>
                    <th></th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
foreach ( $arry as $order ) {
    $totalPrice += $order[ 'price' ];
    // Calculate total price
    ?>
                <tr>
                    <td> <?php echo $order[ 'order_id' ];
    ?> </td>
                    <td> <?php echo $order[ 'order_date' ];
    ?> </td>
                    <td> <?php echo $order[ 'delivery_date' ];
    ?> </td>
                    <td> <?php echo $order[ 'nama_entitas' ];
    ?> </td>
                    <td> <?php echo $order[ 'user_id' ];
    ?> </td>
                    <td> <?php echo $order[ 'nama_menu' ];
    ?> </td>
                    <td> <?php echo $order[ 'quantity' ];
    ?> </td>
                    <td> <?php echo formatPrice( $order[ 'price' ] );
    ?> </td>
                    <td> <?php echo $order[ 'keterangan' ];
    ?> </td>
                    <td>
                        <?php
    if ( $order[ 'status_order' ] == 1 ) {
        echo 'Processed';
    } elseif ( $order[ 'status_order' ] == 2 ) {
        echo 'Done';
    } else {
        echo 'Unknown Status';
    }
    ?>
                    </td>
                    <td>
                        <form action='order_data.php' method='POST'>
                            <select name='order_status'>
                                <option>Select</option>
                                <option value='1'>Processed</option>
                                <option value='2'>Done</option>
                            </select> <br>
                            <input type='hidden' name='order_id' value="<?php echo $order['order_id']; ?>">
                            <input type='submit' value='update' name='order_status_btn'>
                        </form>
                    </td>
                    <td>
                        <?php
    if ( $order[ 'payment_status' ] == 1 ) {
        echo 'Belum Bayar';
    } elseif ( $order[ 'payment_status' ] == 2 ) {
        echo 'DP';
    } else {
        echo 'Lunas';
    }
    ?>
                    </td>
                    <td>
                        <form action='order_data.php' method='POST'>
                            <select name='status_payment'>
                                <option>Select</option>
                                <option value='1'>Belum Bayar</option>
                                <option value='2'>DP</option>
                                <option value='3'>Lunas</option>
                            </select> <br>
                            <input type='hidden' name='order_id' value="<?php echo $order['order_id']; ?>">
                            <input type='submit' value='update' name='payment_status_btn'>
                        </form>
                    </td>
                    <td>
                        <a href="edit_order.php?status=orderEdit&&id=<?php echo $order['order_id'] ?>"
                            class='btn btn-sm btn-warning'>Edit </a>
                        <a href="?status=delete&&id=<?php echo $order['order_id']; ?>"
                            class='btn btn-sm btn-danger'>Delete</a>
                    </td>
                </tr>
                <?php
}
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
    <button onclick='printTable()' class='btn btn-primary'>Print</button>
</div>

<script>
function printTable() {
    // Simpan elemen-elemen yang ingin disembunyikan
    var dropdowns = document.querySelectorAll('select');
    var buttons = document.querySelectorAll('.btn');
    var actions = document.querySelectorAll(
        'td:nth-child(11), td:nth-child(13), td:nth-child(14), th:nth-child(11), th:nth-child(13), th:nth-child(14)'
    );

    // Sembunyikan elemen-elemen tersebut
    dropdowns.forEach(function(el) {
        el.style.display = 'none';
    });
    buttons.forEach(function(el) {
        el.style.display = 'none';
    });
    actions.forEach(function(el) {
        el.style.display = 'none';
    });

    // Ambil tabel yang sudah diubah
    var table = document.querySelector('.table-responsive').innerHTML;
    var printWindow = window.open('', '_blank', 'height=500,width=800');

// Tuliskan HTML untuk jendela cetak
    printWindow.document.write('<html><head><title>Resap Kitchen</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: Arial, sans-serif; text-align: center; }');
    printWindow.document.write('.header { display: flex; justify-content: center; align-items: center; margin-bottom: 20px; }');
    printWindow.document.write('.header img { max-width: 80px; height: auto; margin-right: 20px; }');
    printWindow.document.write('.header h1 { margin: 0; font-size: 24px; }');
    printWindow.document.write('table { width: 100%; border-collapse: collapse; margin: 20px 0; }');
    printWindow.document.write('table, th, td { border: 1px solid black; }');
    printWindow.document.write('th, td { padding: 8px; text-align: left; }');
    printWindow.document.write('</style>');
    printWindow.document.write('<link rel="stylesheet" href="/resapb2b/assets/css/style.css">');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<div class="header">');
    printWindow.document.write('<img src="assets/images/logo resap.png" alt="Logo Resap Kitchen">');
    printWindow.document.write('<h1>Resap Kitchen</h1>');
    printWindow.document.write('</div>');
    printWindow.document.write('<table class="table">');
    printWindow.document.write(table);
    printWindow.document.write('</table>');
    printWindow.document.write('</body></html>');





    // Tutup dokumen dan tunggu hingga selesai memuat
    printWindow.document.close();
    printWindow.focus();

    // Cetak tabel dan kemudian kembalikan elemen-elemen yang disembunyikan
    printWindow.onload = function() {
        printWindow.print();
        printWindow.close();

        // Tampilkan kembali elemen-elemen tersebut
        dropdowns.forEach(function(el) {
            el.style.display = '';
        });
        buttons.forEach(function(el) {
            el.style.display = '';
        });
        actions.forEach(function(el) {
            el.style.display = '';
        });
    };
}
</script>
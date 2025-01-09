<?php 
    date_default_timezone_set("Asia/Jakarta");
?>

<!-- Pastikan jQuery sudah terhubung -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {

    // Load Stock Alert ketika halaman pertama kali dibuka
    loadStockAlert();

    $("#filterDate").change(function() {
        var filterId = this.value;

        // Array untuk aksi-aksi yang akan dijalankan
        var actions = [
            { action: 'load_allorder', element: '#totalOrder', type: 'number' },
            { action: 'load_allsell', element: '#totalSell', type: 'currency' },
            { action: 'load_allcustomer', element: '#totalCustomer', type: 'number' },
            { action: 'load_closedwon', element: '#totalclosedwon', type: 'number' },
            { action: 'load_allorderb2c', element: '#totalOrderb2c', type: 'number' },
            { action: 'load_allsellb2c', element: '#totalSellb2c', type: 'currency' },
            { action: 'load_allcustomerb2c', element: '#totalCustomerb2c', type: 'number' }
        ];

        actions.forEach(function(item) {
            $.ajax({
                url: "json/dashboard_json.php",
                method: "POST",
                dataType: 'json',
                data: {
                    action: item.action,
                    did: filterId
                },
                success: function(response) {
                    if(response.status === 'success') {
                        var displayValue = '';
                        if(item.type === 'currency') {
                            displayValue = formatRupiah(response.sum || 0);
                        } else {
                            displayValue = response.total || response.count || 0;
                        }
                        $(item.element).text(displayValue);
                    } else {
                        console.error('Error:', response.message);
                        $(item.element).text('Error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $(item.element).text('Error');
                }
            });
        });

    });

    // Fungsi untuk memuat Stock Alert
    function loadStockAlert() {
        $.ajax({
            url: "json/dashboard_json.php",
            method: "POST",
            dataType: 'json',
            data: {
                action: 'load_allertStock'
            },
            success: function(response) {
                if(response.status === 'success') {
                    var stockData = response.data;
                    var html = '';
                    stockData.forEach(function(stock) {
                        html += '<tr>';
                        html += '<td>' + stock.item_id + '</td>';
                        html += '<td>' + stock.item_name + '</td>';
                        html += '<td>' + stock.current_stock + '</td>';
                        html += '</tr>';
                    });

                    // Update tabel
                    $('.display.dataTable tbody').html(html);
                } else {
                    console.error('Error:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    }

    // Fungsi untuk memformat angka menjadi format mata uang Rupiah
    function formatRupiah(angka, prefix) {
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // Tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

});
</script>
<?php 
// Pastikan user sudah login dan memiliki role yang sesuai
if(!isset($_SESSION['user_role'])){
    // Redirect atau tampilkan pesan error
    exit("Access Denied");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
    .mydiv {
        width: 200px;
        position: absolute;
        right: 38px;
        overflow: hidden;
    }
    </style>
    <!-- Tambahkan link ke CSS DataTables jika belum -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
</head>
<body>
    <h2>Dashboard</h2>

    <div class="mydiv">
        <form action="" class="form">
            <select name="filterDate" id="filterDate" class="form-control">
                <option value="<?php echo date("Y-m-d")?>">Today</option>
                <option value="<?php echo date('Y-m-d', strtotime('-7 days')) ?>">This week</option>
                <option value="<?php echo date('Y-m-d', strtotime('-30 days')) ?>">This Month</option>
                <option value="<?php echo date('Y-m-d', strtotime('-365 days')) ?>">This Year</option>
                <option value="2020-01-01">Life Time</option>
            </select>
        </form>
    </div>

    <br><br><br>
    <div class="row">
        <?php 
        if ($_SESSION['user_role'] == 1 || $_SESSION['user_role'] == 2) {
        ?>
            <div class="col-12">
                <h3>Hai, Admin B2B</h3>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Orders Received</h6>
                        <h2 class="text-right"><i class="ti-shopping-cart f-left"></i><span id="totalOrder">0</span></h2>
                        <p class="m-b-0"><span class="f-right"></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-green order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Total Sales</h6>
                        <h2 class="text-right"><i class="ti-reload f-left"></i><span id="totalSell">0</span></h2>
                        <p class="m-b-0"><span class="f-right"></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-yellow order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Satisfied Customer</h6>
                        <h2 class="text-right"><i class="ti-reload f-left"></i><span id="totalCustomer">0</span></h2>
                        <p class="m-b-0"><span class="f-right"></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-pink order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Customer Closed Won</h6>
                        <h2 class="text-right"><i class="ti-reload f-left"></i><span id="totalclosedwon">0</span></h2>
                        <p class="m-b-0"><span class="f-right"></span></p>
                    </div>
                </div>
            </div>
        <?php 
        } 
        ?>

        <?php 
        if ($_SESSION['user_role'] == 1 || $_SESSION['user_role'] == 4) {
        ?>
            <div class="col-12">
                <h3>Hai, Admin B2C</h3>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Orders Received</h6>
                        <h2 class="text-right"><i class="ti-shopping-cart f-left"></i><span id="totalOrderb2c">0</span></h2>
                        <p class="m-b-0"><span class="f-right"></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-green order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Total Sales</h6>
                        <h2 class="text-right"><i class="ti-reload f-left"></i><span id="totalSellb2c">0</span></h2>
                        <p class="m-b-0"><span class="f-right"></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card bg-c-yellow order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Satisfied Customer</h6>
                        <h2 class="text-right"><i class="ti-reload f-left"></i><span id="totalCustomerb2c">0</span></h2>
                        <p class="m-b-0"><span class="f-right"></span></p>
                    </div>
                </div>
            </div>
        <?php 
        } 
        ?>

        <?php 
        if ($_SESSION['user_role'] == 1 || $_SESSION['user_role'] == 3) {
        ?>
            <div class="col-12">
                <h3>Hai, Admin Inventory</h3>
            </div>

            <div class="col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <b>Stock Alert</b>
                    </div>
                    <div class="card-body">
                        <div class="responsive">
                            <table class="display dataTable text-center" id="stockTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data dari AJAX akan di-inject di sini -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
        } 
        ?>
    </div>

    <!-- Tambahkan link ke DataTables JS jika belum -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <!-- Inisialisasi DataTables -->
    <script>
    $(document).ready(function() {
        $('#stockTable').DataTable();
    });
    </script>
</body>
</html>
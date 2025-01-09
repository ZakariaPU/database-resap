<?php
$item_names = $obj->get_item_names(); // Mendapatkan daftar nama bahan untuk autocomplete

if (isset($_GET['status'])) {
    $inventory_in_id = $_GET['id'];
    if ($_GET['status'] == 'delete') {
        $del_msg = $obj->delete_stockIn($inventory_in_id);
    }
}

$arry = array(); // Inisialisasi array untuk hasil pencarian
$selectedFilterDate = isset($_GET['filterDate']) ? $_GET['filterDate'] : '';

// Jika filter name diterapkan
if (isset($_GET['bcari'])) {
    $filter_name = trim($_GET['filter_name']); // Trim spaces

    if (!empty($filter_name)) {
        // Jika ada filter name, gunakan fungsi pencarian
        $search_query = $obj->search_itemname($filter_name);
        while ($search = mysqli_fetch_assoc($search_query)) {
            $arry[] = $search;
        }

        // Jika hasil pencarian kosong, tambahkan pesan log
        if (empty($arry)) {
            error_log("Pencarian untuk '$filter_name' tidak ditemukan.", 0);
            echo "<div class='alert alert-warning'>Data yang dicari dengan nama '$filter_name' tidak ditemukan.</div>";
        }
    }
}

// Cek apakah filterCategory diisi
if (!empty($_GET['filterCategory'])) {
    $filterCategory = $_GET['filterCategory'];

    // Jika array $arry sudah berisi hasil pencarian nama bahan, lakukan filter berdasarkan kategori
    if (!empty($arry)) {
        $arry = array_filter($arry, function($item) use ($filterCategory) {
            return $item['category'] === $filterCategory;
        });
    } else {
        // Jika tidak ada hasil pencarian nama bahan, ambil data berdasarkan kategori
        $arry = $obj->filterCategory($filterCategory);
    }

    if (empty($arry)) {
        // Jika tidak ada data yang ditemukan
        error_log("Pencarian kategori untuk '$filterCategory' tidak ditemukan.", 0);
        echo "<div class='alert alert-warning'>Data yang dicari dengan kategori '$filterCategory' tidak ditemukan.</div>";
    }
}



// Jika filter date juga diterapkan, lakukan filter pada hasil pencarian
if (isset($_GET['filterDate'])) {
    $filterDate = $_GET['filterDate'];

    // Tentukan tanggal mulai dan akhir berdasarkan filter yang dipilih
    $startDate = "";
    $endDate = date("Y-m-d");

    if ($filterDate == date("Y-m-d")) {
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

    // Jika sudah ada hasil pencarian dengan filter name, filter berdasarkan tanggal
    if (!empty($arry)) {
        $arry = array_filter($arry, function($item) use ($startDate, $endDate) {
            return $item['date_in'] >= $startDate && $item['date_in'] <= $endDate;
        });
    } else {
        // Jika tidak ada hasil pencarian dengan filter name, lakukan pencarian berdasarkan tanggal
        $arry = $obj->getInventoryByDateRange($startDate, $endDate);
    }
}

// Jika tidak ada filter yang diterapkan, tampilkan semua data
// Namun, jika ada filter yang diterapkan dan tidak ada hasil, maka tabel akan kosong
if (empty($arry)) {
    // Cek apakah ada filter pencarian
    if (isset($_GET['bcari']) || isset($_GET['filterDate'])) {
        $arry = []; // Set arry ke array kosong agar tabel kosong
    } else {
        $arry = $obj->show_inventory_data(); // Jika tidak ada filter, tampilkan semua data
    }
}


function formatPrice($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
}
?>



<style>
.form-group {
    display: flex;
    align-items: center;
}

.autocomplete {
    position: relative;
    margin-right: 10px;
}

input[type=text] {
    background-color: #f1f1f1;
    width: 250px;
    padding: 10px;
    font-size: 16px;
    border: none;
    outline: none;
    box-sizing: border-box;
}

input[type=submit] {
    background-color: DodgerBlue;
    color: #fff;
    padding: 10px;
    font-size: 16px;
    border: none;
    cursor: pointer;
    margin-left: 10px;
}

.autocomplete-items {
    position: absolute;
    width: 100%;
    max-height: 200px;
    overflow-y: auto;
    background-color: #fff;
    border: 1px solid #d4d4d4;
    z-index: 99;
}

.autocomplete-items div {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #d4d4d4;
}

.autocomplete-items div:hover {
    background-color: #e9e9e9;
}

.autocomplete-items div.active {
    background-color: DodgerBlue;
    color: #fff;
}

.items-link {
    text-decoration: none;
    color: black;
}

.items-link:hover {
    text-decoration: underline;
}

.date-filter-container {
    margin-right: 10px;
    width: 200px;
}
</style>

<div class='container'>
    <h2>Manage Stock In</h2>

    <h4 class='text-success'>
        <?php
        if (isset($del_msg)) {
            echo $del_msg;
        }
        ?>
    </h4>

    <form action='' method='get' id='filterForm'>
        <div class='form-group'>
            <div class='autocomplete'>
                <input type='text' id='item_name' name='filter_name' placeholder='Search by Nama Bahan'>
                <div class='autocomplete-items' id='autocomplete-list'></div>
            </div>

            <div class="date-filter-container">
                <select name="filterDate" id="filterDate" class="form-control">
                    <option value="">Select Date</option> <!-- Placeholder option -->
                    <option value="<?php echo date("Y-m-d") ?>"
                        <?php if ($selectedFilterDate == date("Y-m-d")) echo 'selected'; ?>>Today</option>
                    <option value="<?php echo date('Y-m-d', strtotime('-7 days')) ?>"
                        <?php if ($selectedFilterDate == date('Y-m-d', strtotime('-7 days'))) echo 'selected'; ?>>This
                        week</option>
                    <option value="<?php echo date('Y-m-d', strtotime('-30 days')) ?>"
                        <?php if ($selectedFilterDate == date('Y-m-d', strtotime('-30 days'))) echo 'selected'; ?>>This
                        Month</option>
                    <option value="<?php echo date('Y-m-d', strtotime('-365 days')) ?>"
                        <?php if ($selectedFilterDate == date('Y-m-d', strtotime('-365 days'))) echo 'selected'; ?>>This
                        Year</option>
                    <option value="2020-01-01" <?php if ($selectedFilterDate == "2020-01-01") echo 'selected'; ?>>Life
                        Time</option>
                </select>
            </div>

            <input type='submit' class='btn btn-primary' name='bcari' value='Search'>
        </div>
        <div class="form-group">
            <label for="filterCategory">Filter Kategori</label>
            <select name="filterCategory" id="filterCategory" class="form-control">
                <option value="">-- Pilih Kategori --</option>
                <option value="Beras">Beras</option>
                <option value="Bumbu & Bahan Masakan">Bumbu & Bahan Masakan</option>
                <option value="Tepung & Olahan Tepung">Tepung & Olahan Tepung</option>
                <option value="Daging & Olahan Daging">Daging & Olahan Daging</option>
                <option value="Seafood">Seafood</option>
                <option value="Telur">Telur</option>
                <option value="Sayur">Sayur</option>
                <option value="Buah">Buah</option>
                <option value="Bahan Kering">Bahan Kering</option>
                <option value="Bahan Kemasan">Bahan Kemasan</option>
            </select>
        </div>
    </form>

    <div class='table-responsive'>
        <table class='table'>
            <thead>
                <tr>
                    <th>Inventory ID</th>
                    <th>User ID</th>
                    <th>Tanggal Pembelian</th>
                    <th>Nama Bahan</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Total Harga</th>
                    <th>Supplier</th>
                    <th>Keterangan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalQuantity = 0; // Initialize total quantity
                foreach ($arry as $inventory) {
                    $totalQuantity += $inventory['quantity'];
                    ?>
                <tr>
                    <td><?php echo $inventory['inventory_in_id']; ?></td>
                    <td><?php echo $inventory['user_id']; ?></td>
                    <td><?php echo $inventory['date_in']; ?></td>
                    <td><?php echo $inventory['item_name']; ?></td>
                    <td><?php echo $inventory['category']; ?></td>
                    <td><?php echo $inventory['quantity']; ?></td>
                    <td><?php echo $inventory['unit']; ?></td>
                    <td><?php echo formatPrice($inventory['unit_price']); ?></td>
                    <td><?php echo formatPrice($inventory['total_price']); ?></td>
                    <td><?php echo $inventory['full_name']; ?></td>
                    <td><?php echo $inventory['description']; ?></td>
                    <td>
                        <a href="edit_stockin.php?status=stockinEdit&id=<?php echo $inventory['inventory_in_id'] ?>"
                            class='btn btn-sm btn-warning'>Edit</a>
                        <a href="?status=delete&&id=<?php echo $inventory['inventory_in_id']; ?>"
                            class='btn btn-sm btn-danger'>Delete</a>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan='5' class='text-right'><strong>Total Quantity:</strong></td>
                    <td colspan='7'><?php echo $totalQuantity; ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>


<script src="js/jquery.js"></script>
<script>
$(document).ready(function() {
    var availableItemNames = <?php echo json_encode($item_names); ?>;

    $('#item_name').on('input', function() {
        var inputVal = $(this).val().toLowerCase();
        var list = $('#autocomplete-list');
        list.empty(); // Menghapus item sebelumnya

        if (inputVal) {
            availableItemNames.forEach(function(item_name) {
                if (item_name.toLowerCase().indexOf(inputVal) !== -1) {
                    list.append('<div class="autocomplete-item">' + item_name + '</div>');
                }
            });
        }

        $('.autocomplete-item').on('click', function() {
            $('#item_name').val($(this).text());
            list.empty(); // Mengosongkan list setelah memilih item
        });
    });
});
document.getElementById('filterCategory').addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});
</script>
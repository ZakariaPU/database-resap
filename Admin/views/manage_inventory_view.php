<?php
$item = $obj->get_item(); // Mendapatkan daftar kategori untuk autocomplete

if (isset($_GET['status'])) {
    $inventory_in_id = $_GET['id'];
    if ($_GET['status'] == 'delete') {
        $del_msg = $obj->delete_stock($inventory_in_id);
    }
}

$arry = array(); // Inisialisasi array untuk hasil pencarian
$selectedFilterDate = isset($_GET['filterDate']) ? $_GET['filterDate'] : '';

// Jika filter name diterapkan
if (isset($_GET['bcari'])) {
    $filter_name = trim($_GET['filter_name']); // Trim spaces

    if (!empty($filter_name)) {
        // Jika ada filter name, gunakan fungsi pencarian
        $search_query = $obj->search_item($filter_name);
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
        $arry = $obj->filterKategori($filterCategory);
    }

    if (empty($arry)) {
        // Jika tidak ada data yang ditemukan
        error_log("Pencarian kategori untuk '$filterCategory' tidak ditemukan.", 0);
        echo "<div class='alert alert-warning'>Data yang dicari dengan kategori '$filterCategory' tidak ditemukan.</div>";
    }
}


// Jika tidak ada filter yang diterapkan, tampilkan semua data
// Namun, jika ada filter yang diterapkan dan tidak ada hasil, maka tabel akan kosong
if (empty($arry)) {
    // Cek apakah ada filter pencarian
    if (isset($_GET['bcari'])) {
        $arry = []; // Set arry ke array kosong agar tabel kosong
    } else {
        $arry = $obj->show_inventory(); // Jika tidak ada filter, tampilkan semua data
    }
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
    <h2>Stock Inventory</h2>

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
                    <th>Bahan ID</th>
                    <th>Nama Bahan</th>
                    <th>Kategori</th>
                    <th>Stock Sisa</th>
                    <th>Satuan</th>
                    <th>Tanggal Pembelian Terakhir</th>
                    <th>Keterangan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($arry as $stockIn) {
                    ?>
                    <tr>
                        <td><?php echo $stockIn['item_id']; ?></td>
                        <td><?php echo $stockIn['item_name']; ?></td>
                        <td><?php echo $stockIn['category']; ?></td>
                        <td><?php echo $stockIn['current_stock']; ?></td>
                        <td><?php echo $stockIn['unit']; ?></td>
                        <td><?php echo $stockIn['latest_date_in']; ?></td>
                        <td><?php echo $stockIn['description']; ?></td>
                        <td>
                            <a href="?status=delete&&id=<?php echo $stockIn['item_id']; ?>" class='btn btn-sm btn-danger'>Delete</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="js/jquery.js"></script>
<script>
$(document).ready(function() {
    var availableCategories = <?php echo json_encode($item); ?>;

    $('#item_name').on('input', function() {
        var inputVal = $(this).val().toLowerCase();
        var list = $('#autocomplete-list');
        list.empty(); // Menghapus item sebelumnya

        if (inputVal) {
            availableCategories.forEach(function(item_name) {
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

<?php

$item_names = $obj->get_item_names(); // Mendapatkan daftar nama bahan untuk autocomplete


if (isset($_POST['add_stock'])) {
    $obj->addStock($_POST);
}

if (isset($_POST['add_item'])) {
    $obj->insertItem($_POST);
}

$supplier_dropdown = $obj->generate_supplier_dropdown(); 
?>

<div class="form-wrapper">
    <div class="form-container">
        <div class="header-container">
            <h2>Tambah Stok</h2>
            <button id="openItemForm" class="btn-secondary">Input Nama Bahan</button>
        </div>
        <form action="" method="POST" class="form-container">
            <div class="form-group">
                <label for="purchaseDate">Tanggal Pembelian:</label>
                <input type="date" id="purchaseDate" name="purchaseDate" class="form-control" required>
            </div>

            <div class="form-group autocomplete">
                <label for="item_name">Nama Bahan:</label>
                <input id="item_name" type="text" name="filter_Name" placeholder="Masukkan Nama Bahan" class="form-control" required>
                <div class="autocomplete-items" id="autocomplete-list"></div>
            </div>

            <div class="form-group">
                <label for="category">Kategori:</label>
                <select id="category" name="category" class="form-control" required>
                    <option value="">Pilih Kategori</option>
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

            <div class="form-group">
                <label for="qty">Jumlah:</label>
                <input type="number" id="qty" name="qty" class="form-control" placeholder="Masukkan Jumlah" step="0.01"
                    required>
            </div>

            <div class="form-group">
                <label for="unit">Satuan:</label>
                <select id="unit" name="unit" class="form-control" required>
                    <option value="">Pilih Satuan</option>
                    <option value="Kg">Kg</option>
                    <option value="Pcs">Pcs</option>
                    <option value="Ikat">Ikat</option>
                    <option value="Bks">Bks</option>
                    <option value="Lirang">Lirang</option>
                    <option value="Liter">Liter</option>
                    <option value="Biji">Biji</option>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Harga:</label>
                <input type="text" id="price" name="price" class="form-control" placeholder="Masukkan Harga" required>
            </div>

            <div class="form-group">
                <label for="totalPrice">Total Harga:</label>
                <input type="text" id="totalPrice" name="totalPrice" class="form-control"
                    placeholder="Masukkan Total Harga" required>
            </div>

            <div class="form-group">
                <label for="supplier_id">Supplier:</label>
                <?php echo $supplier_dropdown; ?>
            </div>

            <div class="form-group">
                <label for="notes">Keterangan:</label>
                <textarea id="notes" name="notes" class="form-control" placeholder="Tambahkan Keterangan"></textarea>
            </div>

            <div class="form-group">
                <button type="submit" name="add_stock" class="btn">Tambah Stok</button>
            </div>
        </form>
    </div>

    <!-- The Modal -->
    <div id="itemForm" class="modal">
        <div class="modal-content">
            <span id="closeItemForm" class="close">&times;</span>
            <h2>Input Nama Bahan</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="itemName">Nama Bahan:</label>
                    <input type="text" id="itemName" name="itemName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="minStock">Stok Minimum:</label>
                    <input type="number" id="minStock" name="minStock" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="currentStock">Stok Saat Ini:</label>
                    <input type="number" id="currentStock" name="currentStock" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi:</label>
                    <textarea id="description" name="description" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" name="add_item" class="btn">Simpan</button>
                </div>
            </form>
        </div>
    </div>


    <style>
    .form-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 20px;
    }

    .form-container {
        flex: 1;
        min-width: 300px;
        max-width: 100%;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 15px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #555;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-top: 5px;
        font-size: 14px;
        color: #333;
        background: #fff;
    }

    textarea {
        resize: vertical;
        height: 80px;
    }

    .btn {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
        margin-top: 10px;
        /* Adjusted for better spacing */
    }

    .btn-secondary {
        width: 20%;
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
        margin-top: 10px;
        /* Adjusted for better spacing */
    }

    .btn-secondary {
        background-color: #28a745;
    }

    .btn:hover,
    .btn-secondary:hover {
        background-color: #0056b3;
    }

    .btn-secondary:hover {
        background-color: #218838;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
        border-radius: 8px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .autocomplete {
        position: relative;
        display: block;
    }

    .autocomplete-items {
        position: absolute;
        border: 1px solid #d4d4d4;
        border-bottom: none;
        border-top: none;
        z-index: 99;
        top: 100%;
        left: 0;
        right: 0;
    }

    .autocomplete-items div {
        padding: 10px;
        cursor: pointer;
        background-color: #fff;
        border-bottom: 1px solid #d4d4d4;
    }

    .autocomplete-items div:hover {
        background-color: #e9e9e9;
    }

    .autocomplete-active {
        background-color: DodgerBlue !important;
        color: #ffffff;
    }
    </style>
    <script src="js/jquery.js"></script>
    <script>
    document.getElementById('openItemForm').addEventListener('click', function() {
        document.getElementById('itemForm').style.display = 'block';
    });

    document.getElementById('closeItemForm').addEventListener('click', function() {
        document.getElementById('itemForm').style.display = 'none';
    });

    // Code for autocomplete, and other interactivity scripts
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    function calculateTotal() {
        // Get the numeric value of price (without formatting)
        var price = document.getElementById("price").value.replace(/[^,\d]/g, '').replace(',', '.');
        var qty = document.getElementById("qty").value;

        // Calculate total as a float
        var total = parseFloat(price) * parseFloat(qty);

        // Format total with currency format
        document.getElementById("totalPrice").value = formatRupiah(total.toFixed(0).toString(), 'Rp. ');
    }

    document.addEventListener("DOMContentLoaded", function() {
        var priceInput = document.getElementById("price");

        priceInput.addEventListener("keyup", function(e) {
            priceInput.value = formatRupiah(this.value, 'Rp. ');
            calculateTotal();
        });
    });
    
    


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
    </script>
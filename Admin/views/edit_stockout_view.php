<?php

if (isset($_GET['status'])) {
    $inventory_out_id = $_GET['id'];
    if ($_GET['status'] == 'stockoutEdit') {
        $stock = $obj->show_stockout_by_id($inventory_out_id); // No need for mysqli_fetch_assoc
    }
}


$item_names = $obj->get_item_names(); // Mendapatkan daftar nama bahan untuk autocomplete

if ( isset( $_POST[ 'edit_stock' ] ) ) {
    // Proses update data
    $update_msg = $obj->update_stockout( $_POST );
    $stock = $obj->show_stockout_by_id($inventory_out_id);
}


?>

<div class="form-wrapper">
    <div class="form-container">
        <div class="header-container">
            <h2>Edit Stok Keluar</h2>
        </div>
        <form action="" method="POST" class="form-container">
            <div class="form-group autocomplete">
                <label for="item_name">Nama Bahan:</label>
                <input id="item_name" type="text" name="filter_Name" placeholder="Masukkan Nama Bahan"
                    class="form-control" value="<?php echo $stock['item_name']; ?>" required>
                <div class="autocomplete-items" id="autocomplete-list"></div>
            </div>

            <div class="form-group">
                <label for="qty">Jumlah:</label>
                <input type="number" id="qty" name="qty" class="form-control" placeholder="Masukkan Jumlah"
                    value="<?php echo $stock['quantity']; ?>" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="purchaseDate">Tanggal Penggunaan:</label>
                <input type="date" id="purchaseDate" name="purchaseDate" class="form-control"
                    value="<?php echo $stock['date_out']; ?>" required>
            </div>

            <div class="form-group">
                <label for="notes">Keterangan:</label>
                <textarea id="notes" name="notes" class="form-control"
                    placeholder="Tambahkan Keterangan"><?php echo $stock['description']; ?></textarea>
            </div>

            <input type='hidden' name='stock_id'
                value="<?php echo isset($stock['inventory_out_id']) ? $stock['inventory_out_id'] : ''; ?>">

            <div class="form-group">
                <input type="submit" name="edit_stock" class="btn btn-primary" value="Update Stock">
            </div>
        </form>
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
    </script>
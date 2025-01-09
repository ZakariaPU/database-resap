<?php
$item_names = $obj->get_item_names(); // Mendapatkan daftar nama bahan untuk autocomplete

if (isset($_POST['reduce_stock'])) {
    $obj->reduceStock($_POST);
}
?>


<form action="" method="POST" class="form-container">
    <h2>Kurangi Stok</h2>

    <div id="itemContainer">
        <div class="autocomplete">
            <input type="text" name="itemName[]" placeholder="Nama Bahan">
        </div>
    </div>
    <button type="button" id="addItemButton" class="btn">Tambah Bahan</button>
    <br>

    <div class="form-group">
        <label for="purchaseDate">Tanggal Penggunaan:</label>
        <input type="date" id="purchaseDate" name="purchaseDate" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="qty">Jumlah yang Dikurangi:</label>
        <input type="number" id="qty" name="qty" class="form-control" placeholder="Masukkan Jumlah" min="0" required>
    </div>

    <div class="form-group">
        <label for="notes">Keterangan:</label>
        <textarea id="notes" name="notes" class="form-control" placeholder="Tambahkan Keterangan"></textarea>
    </div>

    <div class="form-group">
        <button type="submit" name="reduce_stock" class="btn">Kurangi Stok</button>
    </div>
</form>




<style>

.form-container {
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 20px auto;
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #555;
}

input[type="text"],
input[type="number"],
input[type="date"],
textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-top: 5px;
    font-size: 16px;
    color: #333;
    background: #f9f9f9;
    transition: border-color 0.3s;
}

input[type="text"]:focus,
input[type="number"]:focus,
input[type="date"]:focus,
textarea:focus {
    border-color: #007bff;
    outline: none;
    background-color: #fff;
}

textarea {
    resize: vertical;
    height: 100px;
}

.btn {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
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
    border-radius: 0 0 5px 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    background-color: #ffffff;
}

.autocomplete-items div {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #d4d4d4;
    transition: background-color 0.3s;
}

.autocomplete-items div:last-child {
    border-bottom: none;
}

.autocomplete-items div:hover {
    background-color: #e9e9e9;
}

.autocomplete-active {
    background-color: #007bff !important;
    color: #ffffff;
}

#addItemButton {
    margin-bottom: 20px; /* Jarak di bawah button */
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

#addItemButton:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
}

</style>

<script src="js/jquery.js"></script>
<script>
$(document).ready(function() {
    var availableItemNames = <?php echo json_encode($item_names); ?>;

    $(document).on('input', 'input[name="itemName[]"]', function() {
        var inputVal = $(this).val().toLowerCase();
        var list = $(this).siblings('.autocomplete-items');
        if (list.length === 0) {
            list = $('<div class="autocomplete-items"></div>').insertAfter($(this));
        }
        list.empty(); // Menghapus item sebelumnya

        if (inputVal) {
            availableItemNames.forEach(function(item_name) {
                if (item_name.toLowerCase().indexOf(inputVal) !== -1) {
                    list.append('<div class="autocomplete-item">' + item_name + '</div>');
                }
            });
        }

        $('.autocomplete-item').on('click', function() {
            $(this).closest('.autocomplete').find('input').val($(this).text());
            list.empty(); // Mengosongkan list setelah memilih item
        });
    });

    $('#addItemButton').on('click', function() {
        var container = $('#itemContainer');
        var newInput = $('<div class="autocomplete"><input type="text" name="itemName[]" placeholder="Nama Bahan"></div>');
        container.append(newInput);
    });
});
</script>

<?php
if (isset($_POST['add_cust'])) {
    if (isset($_POST['add_cust'])) {
        // Before processing, strip currency formatting from the price inputs
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'price_') === 0) {
                // Strip out 'Rp', commas, and dots, and convert to a float
                $value = preg_replace('/[^\d]/', '', $value);
                $_POST[$key] = floatval($value);
            }
        }
        $add_msg = $obj->add_cust_order($_POST);
    }
    
}
if (isset($_GET['bsearch'])) {
    $filterName = $_GET['filterName'];
    if (!empty($filterName)) {
        $search_query = $obj->search_customer($filterName);

        if ($search_query) {
            $arry = array();
            while ($search = mysqli_fetch_assoc($search_query)) {
                $arry[] = $search;
            }

            if (count($arry) == 1) {
                $result = $arry[0];
                echo "<script>
                        document.addEventListener('DOMContentLoaded', (event) => {
                            fillForm(" . json_encode($result) . ");
                        });
                      </script>";
            } else {
                echo "<script>
                        var searchResults = " . json_encode($arry) . ";
                        document.addEventListener('DOMContentLoaded', (event) => {
                            displaySearchResults(searchResults);
                        });
                      </script>";
            }
        } else {
            echo "Error: " . mysqli_error($obj->connection);
        }
    }
}
?>

<div class='container'>
    <h2>Add Customer Resap</h2>
    <br>
    <h6 class='text-success'>
        <?php
        if (isset($add_msg)) {
            echo $add_msg;
        }
        ?>
    </h6>
    <!-- Form Pencarian -->
    <form action='' method='GET'>
        <div class='form-group'>
            <input type='text' class='form-control' id='filterName' name='filterName'
                placeholder="Search by Phone Number PIC" required>
        </div>
        <button type='submit' name='bsearch' class='btn btn-primary btn-block'>Search</button>
    </form>
    <br>
    <!-- Hasil Pencarian -->
    <div id="searchResultsContainer"></div>
    <form action='' method='POST' id='customerForm'>
        <div class='form-group'>
            <h4>Nama Entitas</h4>
            <input type='text' name='nama_entitas' class='form-control' required>
        </div>

        <div class='form-group'>
            <h4>Alamat Entitas</h4>
            <input type='text' name='alamat_entitas' class='form-control' required>
        </div>

        <div class='form-group'>
            <h4>Nomor Handphone Entitas</h4>
            <input type='text' name='phone_number_entitas' class='form-control' required>
        </div>

        <div class='form-group'>
            <h4>Website</h4>
            <input type='text' name='website' class='form-control' required>
        </div>

        <div class='form-group'>
            <h4>Sosmed</h4>
            <input type='text' name='sosmed' class='form-control' required>
        </div>

        <div class='form-group'>
            <h4>Nama PIC</h4>
            <input type='text' name='nama_pic' class='form-control' required>
        </div>

        <div class='form-group'>
            <h4>Nomor Handphone PIC</h4>
            <input type='text' name='phone_number_pic' class='form-control' required>
        </div>

        <div class='form-group'>
            <h4>Alamat PIC</h4>
            <input type='text' name='alamat_pic' class='form-control' required>
        </div>
        <div class='form-group'>
            <h4>Sumber Informasi Customer Mengetahui Resap</h4>
            <input type='text' name='sumber_info' class='form-control' required>
        </div>

        <div class='form-group'>
            <h4>Lead Status</h4>
            <select name='cust_status' class='form-control'>
                <option disabled selected>--Select--</option>
                <option value='1'>Cold</option>
                <option value='2'>Warm</option>
                <option value='3'>Hot</option>
            </select>
        </div>

        <div class='form-group'>
            <button type="button" class="btn btn-primary btn-block" id="initialAddOrderButton" onclick="addOrder()">Add
                Order</button>
        </div>

        <div id="orderSection"></div>
        <br>
        <div id="buttonSection">
            <div class='form-group'>
                <button type="submit" name="add_cust" class="btn btn-primary btn-block">Submit</button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent form submission
            }
        });
    });

    // Display search results
    const searchResultsContainer = document.getElementById('searchResultsContainer');
    searchResultsContainer.innerHTML = ''; // Clear container before adding new search results
    if (typeof searchResults !== 'undefined' && searchResults.length > 0) {
        searchResults.forEach((result, index) => {
            const resultItem = document.createElement('div');
            resultItem.classList.add('search-result-item');
            // Display resultItem content
            searchResultsContainer.appendChild(resultItem);
        });
    }
});

function fillForm(data) {
    document.querySelector('input[name="nama_entitas"]').value = data.nama_entitas;
    document.querySelector('input[name="alamat_entitas"]').value = data.alamat_entitas;
    document.querySelector('input[name="phone_number_entitas"]').value = data.phone_number_entitas;
    document.querySelector('input[name="website"]').value = data.website;
    document.querySelector('input[name="sosmed"]').value = data.sosmed;
    document.querySelector('input[name="nama_pic"]').value = data.nama_pic;
    document.querySelector('input[name="phone_number_pic"]').value = data.phone_number_pic;
    document.querySelector('input[name="alamat_pic"]').value = data.alamat_pic;
    document.querySelector('input[name="sumber_info"]').value = data.sumber_info;
    document.querySelector('select[name="cust_status"]').value = data.cust_status;
}

let counter = 0;
let orderSectionAdded = false;

function addOrder() {
    counter++;

    // If the orderSection element does not exist, create it
    if (!orderSectionAdded) {
        const orderSection = document.createElement('div');
        orderSection.setAttribute('id', 'orderSection');
        document.getElementById('orderSection').appendChild(orderSection);
        orderSectionAdded = true;
    }

    // Add a new order
    const orderForm = document.createElement('div');
    orderForm.innerHTML = `
    <br>
    <h3 class="title-bar">Order Information</h3>
    <h4>Order ${counter}</h4>
    <div class='form-group'>
        <h4>Order Date</h4>
        <input type="date" name="order_date_${counter}" class="form-control" required>
    </div>
    <div class='form-group'>
        <h4>Delivery Date</h4>
        <input type="date" name="delivery_date_${counter}" class="form-control" required>
    </div>
    <div class='form-group'>
        <h4>Nama Menu</h4>
        <input type="text" name="nama_menu_${counter}" class="form-control" required>
    </div>
    <div class='form-group'>
        <h4>Quantity</h4>
        <input type="number" name="quantity_${counter}" class="form-control" required>
    </div>
    <div class='form-group'>
        <h4>Price</h4>
        <input type="text" name="price_${counter}" class="form-control" oninput="formatCurrency(this)" required>
    </div>
    <div class='form-group'>
        <h4>Keterangan</h4>
        <input type="text" name="keterangan_${counter}" class="form-control" required>
    </div>
    <div class='form-group'>
        <h4>Status Order</h4>
        <select name='status_order_${counter}' class='form-control'>
            <option disabled selected>--Select--</option>
            <option value='1'>Processed</option>
            <option value='2'>Done</option>
        </select>
    </div>
    <div class='form-group'>
        <h4>Payment Status</h4>
        <select name='payment_status_${counter}' class='form-control'>
            <option disabled selected>--Select--</option>
            <option value='1'>Belum Bayar</option>
            <option value='2'>DP</option>
            <option value='3'>Lunas</option>
        </select>
    </div>
    `;
    document.getElementById('orderSection').appendChild(orderForm);

    // Tambahkan tombol "Add Order" baru setelah setiap pesanan
    const newAddOrderButton = document.createElement('button');
    newAddOrderButton.type = "button";
    newAddOrderButton.className = "btn btn-primary btn-block";
    newAddOrderButton.textContent = "Add Another Order";
    newAddOrderButton.onclick = addOrder;

    // Tambahkan tombol baru setelah form order baru
    document.getElementById('orderSection').appendChild(newAddOrderButton);

    // Sembunyikan tombol "Add Order" pertama setelah tombol pertama kali diklik
    const initialAddOrderButton = document.getElementById('initialAddOrderButton');
    if (initialAddOrderButton) {
        initialAddOrderButton.style.display = 'none';
    }
}


function formatCurrency(input) {
    // Get the current input value
    let value = input.value;

    // Remove all characters except digits and commas
    value = value.replace(/[^\d,]/g, '');

    // Format the value with IDR currency and thousands separator
    value = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value.replace(/\./g, ''));

    // Set the formatted value back to the input field
    input.value = value;
}
</script>
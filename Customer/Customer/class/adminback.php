<?php

class  adminback
 {
    private $connection;

    function __construct()
 {
        $dbhost = 'localhost';
        $dbuser = 'resr7526_b2buser';
        $dbpass = 'oXc4Uob#dxD,';
        $dbname = 'resr7526_resapb2b';

        $this->connection = mysqli_connect( $dbhost, $dbuser, $dbpass, $dbname );

        if ( !$this->connection ) {
            die( 'Databse connection error!!!' );
        }
    }


    
    public function place_order($data) {
    // Membuat koneksi ke database
    $conn = new mysqli('localhost', 'resr7526_b2buser', 'oXc4Uob#dxD,', 'resr7526_resapb2b');

    // Mengecek koneksi
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Menyiapkan data pelanggan
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $email = $data['email'];
    $whatsapp = $data['whatsapp'];
    $usernameIG = $data['usernameIG'];

    // Memasukkan data pelanggan ke tabel cust_b2c
    $stmt_cust = $conn->prepare('INSERT INTO cust_b2c (NamaDepan, NamaBelakang, Email, Whatsapp, UsernameIG) VALUES (?, ?, ?, ?, ?)');
    $stmt_cust->bind_param('sssss', $first_name, $last_name, $email, $whatsapp, $usernameIG);

    if (!$stmt_cust->execute()) {
        echo 'Error: ' . $stmt_cust->error;
        $stmt_cust->close();
        $conn->close();
        return 'Error: ' . $stmt_cust->error;
    }

    // Mendapatkan CustomerID yang baru saja ditambahkan
    $customerID = $stmt_cust->insert_id;
    $stmt_cust->close();

    // Mengumpulkan data dari checkbox
    $jenis_menu = isset($data['jenis_menu']) ? $data['jenis_menu'] : [];

    // Gabungkan jenis menu menjadi satu string dengan pemisah koma
    $jenis_menu_str = implode(', ', $jenis_menu);

    // Ambil keterangan berdasarkan nilai checkbox yang dipilih
    $keterangan = [];
    foreach ($jenis_menu as $menu) {
        // Menggunakan str_replace untuk mengganti spasi dengan underscore
        $keterangan_key = 'keterangan_' . strtolower(str_replace(' ', '_', $menu));
        
        // Mengecek apakah keterangan untuk menu tersebut diisi, jika ya, tambahkan ke array keterangan
        if (isset($data[$keterangan_key]) && !empty($data[$keterangan_key])) {
            $keterangan[$menu] = $data[$keterangan_key];
        } else {
            $keterangan[$menu] = ''; // Jika tidak ada keterangan, beri nilai kosong
        }
    }

    // Gabungkan keterangan menjadi satu string (opsional jika diperlukan)
    // Misalnya jika ingin menyimpan keterangan dalam bentuk serialized (simpan sebagai string JSON)
    $keterangan_str = json_encode($keterangan);

    // Menyiapkan data pesanan
    $order_date = $data['order_date'];
    $quantity = $data['quantity'];
    $total_price = $data['total_price'];
    $address = $data['shipping_address'];
    $pemilihanPesanan = $_POST['PilihanPesanan']; // Get the selected meal option

    // Memasukkan data pesanan ke tabel order_datab2c
    $stmt_order = $conn->prepare('INSERT INTO order_datab2c (CustomerID, TanggalPesanan, JenisMenu, JumlahPesanan, TotalHarga, Alamat, Keterangan, PilihanPesanan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
$stmt_order->bind_param('ississss', $customerID, $order_date, $jenis_menu_str, $quantity, $total_price, $address, $keterangan_str, $pemilihanPesanan);


    if (!$stmt_order->execute()) {
        echo 'Error: ' . $stmt_order->error;
    }

    $stmt_order->close();
    $conn->close();
    return 'Pesanan berhasil dibuat!';
}




    }
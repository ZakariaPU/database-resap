<?php

class  adminback
 {
    private $connection;

    function __construct()
 {

        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = '';
        $dbname = 'resapb2b';

        $this->connection = mysqli_connect( $dbhost, $dbuser, $dbpass, $dbname );

        if ( !$this->connection ) {
            die( 'Databse connection error!!!' );
        }
    }
    // untuk user perihal login dsb

    public function show_orders()
    {
        $query = $this->connection->query("SELECT * FROM `order_detail`");
        return $query;
    }

    public function formatPrice($price)
    {
        return number_format($price, 2);
    }

    function user_login( $data )
 {
        $username = $data[ 'username' ];
        $user_pass = md5( $data[ 'user_pass' ] );

        $query = "SELECT * FROM `user_detail` WHERE username = '$username' AND user_pass = '$user_pass'";

        if ( mysqli_query( $this->connection, $query ) ) {
            $result = mysqli_query( $this->connection, $query );
            $user_detail = mysqli_fetch_assoc( $result );
            if ( $user_detail ) {
                header( 'location:dashboard.php' );
                session_start();
                $_SESSION[ 'user_id' ] = $user_detail[ 'user_id' ];
                $_SESSION[ 'username' ] = $user_detail[ 'username' ];
                $_SESSION[ 'user_role' ] = $user_detail[ 'user_role' ];
            } else {
                $log_msg = 'Username or password wrong';
                return $log_msg;
            }
        }
    }

    // untuk fungsi logout user/admin

    function user_logout()
{
    // Memeriksa apakah session sudah dimulai
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Hapus data session
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    unset($_SESSION['user_role']);

    // Menghancurkan session
    session_destroy();

    // Redirect ke halaman index.php
    header('Location: index.php');
    exit(); // Penting untuk menghentikan eksekusi script setelah header()
}


    //fungsi untuk menambah user/admin

    function add_user($data) {
        $fullname = $data['full_name'];
        $phone_num = $data['user_phone'];
        $user_name = $data['username'];
        $user_pass = md5($data['pass_word']);
        $user_role = $data['user_role'];
    
        $query = "INSERT INTO `user_detail`(`full_name`,`phone_number`,`username`, `user_pass`, `user_role`) VALUES ('$fullname','$phone_num','$user_name','$user_pass',$user_role)";
    
        if (mysqli_query($this->connection, $query)) {
            // Dapatkan ID user yang baru ditambahkan
            $new_user_id = mysqli_insert_id($this->connection);
    
            // Tambahkan ke Activity Log: User Added
            $this->saveLog($this->connection, $_SESSION['user_id'], 'User Added', "Added new user with ID: $new_user_id");
    
            $msg = "{$user_name} added as a user successfully";
            return $msg;
        }
    }
    function admin_password_recover( $recover_username )
 {
        $query = "SELECT * FROM `user_detail` WHERE `username`='$recover_username'";
        if ( mysqli_query( $this->connection, $query ) ) {
            $row =  mysqli_query( $this->connection, $query );
            return $row;
        }
    }

    function update_user_password( $data )
 {
        $u_user_id = $data[ 'user_update_id' ];
        $u_user_pass = md5( $data[ 'user_update_password' ] );

        $query = "UPDATE `user_detail` SET `user_pass`='$u_user_pass' WHERE `user_id`= $u_user_id";

        if ( mysqli_query( $this->connection, $query ) ) {
            $update_mag = 'You password updated successfull';
            return $update_mag;
        } else {
            return 'Failed';
        }
    }

    function show_user() {
        $query = 'SELECT * FROM `user_detail`';
    
        if (mysqli_query($this->connection, $query)) {
            $result = mysqli_query($this->connection, $query);
            
            // Tambahkan ke Activity Log: User Viewed
            $this->saveLog($this->connection, $_SESSION['user_id'], 'User Viewed', 'Viewed user list');
    
            return $result;
        }
    }

    function delete_user($user_id) {
        $query = "DELETE FROM `user_detail` WHERE `user_id`=$user_id";
    
        if (mysqli_query($this->connection, $query)) {
            // Tambahkan ke Activity Log: User Deleted
            $this->saveLog($this->connection, $_SESSION['user_id'], 'User Deleted', "Deleted user with ID: $user_id");
    
            $del_msg = 'User Deleted Successfully';
            return $del_msg;
        }
    }
    


    

    function show_user_by_id($user_id) {
        $query = "SELECT * FROM `user_detail` WHERE `user_id`=$user_id";
    
        if (mysqli_query($this->connection, $query)) {
            $result = mysqli_query($this->connection, $query);
    
            // Tambahkan ke Activity Log: User Viewed by ID
            $this->saveLog($this->connection, $_SESSION['user_id'], 'User Viewed', "Viewed user details with ID: $user_id");
    
            return $result;
        }
    }
    
    function add_supp( $data ) {
        $name = $data[ 'nama_supplier' ];
        $phone = $data[ 'phone_number' ];
        $alamat = $data[ 'alamat_supplier' ];
        $keterangan = $data[ 'keterangan' ];

        $query = "INSERT INTO supplier_detail( full_name,phone_number,addres_supp, keterangan) VALUES ('$name','$phone', '$alamat', '$keterangan')";

        if ( mysqli_query( $this->connection, $query ) ) {
                        // Dapatkan ID user yang baru ditambahkan
            $new_supplier_id = mysqli_insert_id($this->connection);
                        // Tambahkan ke Activity Log: User Added
                        $this->saveLog($this->connection, $_SESSION['user_id'], 'Supplier Added', "Added new supplier with ID: $new_supplier_id" );

            $msg = "{$name} add as a supplier successfully";
            return $msg;
        }
    }
    
    function show_supplier_by_id( $supp_id ) {
            $query = "SELECT * FROM supplier_detail WHERE supplier_id=$supp_id";
            if ( mysqli_query( $this->connection, $query ) ) {
                $result = mysqli_query( $this->connection, $query );
                // Tambahkan ke Activity Log: User Viewed by ID
                $this->saveLog($this->connection, $_SESSION['user_id'], 'Supplier Viewed', "Viewed supplier details with ID: $supp_id");
                return $result;
            }
        }
        
    function update_supp( $data ) {
        $u_id = $data[ 'supp_id' ];
        $u_name = $data[ 'supp_nama' ];
        $u_phone = $data[ 'supp_nomor_telepon' ];
        $u_address = $data[ 'supp_alamat' ];
        $u_keterangan = $data[ 'keterangan' ];
        $query = "UPDATE supplier_detail SET 
        full_name='$u_name',
        phone_number='$u_phone',
        addres_supp='$u_address',
        keterangan='$u_keterangan'
      WHERE supplier_id= $u_id";
        if ( mysqli_query( $this->connection, $query ) ) {
            $up_msg = 'Udated successfully';
            return $up_msg;
        }

    }
    


    function add_cust_detail( $data ) {
        $full_name = $data[ 'full_name' ];
        $cust_address = $data[ 'cust_address' ];
        $phone_number = $data[ 'phone_number' ];

        $query = "INSERT INTO `customer_detail`( `full_name`,`cust_address`, `phone_number`) VALUES ('$full_name','$cust_address',$phone_number)";

        if ( mysqli_query( $this->connection, $query ) ) {
            $msg = "{$full_name} add as a user successfully";
            return $msg;
        }
    }
    function update_admin($data) {
        $u_id = $data['user_id'];
        $u_name = $data['u-user-name'];
        $u_role = $data['u_user_role'];
        
        $query = "UPDATE `user_detail` SET `username`='$u_name', `user_role`=$u_role WHERE `user_id`=$u_id";
        
        if (mysqli_query($this->connection, $query)) {
            // Tambahkan ke Activity Log: Admin Updated
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Admin Updated', "Updated admin with ID: $u_id");
    
            $up_msg = 'Updated successfully';
            return $up_msg;
        }
    }
    

    function show_cust_data() {
        $query = 'SELECT * FROM `customer_detail`';
        if ( mysqli_query( $this->connection, $query ) ) {
            $result = mysqli_query( $this->connection, $query );  
            // Tambahkan ke Activity Log: Customer Viewed
            $this->saveLog($this->connection, $_SESSION['user_id'], 'User Viewed', 'Viewed customer list');
    
            return $result;
        }
    }


    /* fungsi untuk nampilin semua customer by id nya misal klik edit, delet pas di row ntar muncul ini */

    function show_customer_by_id( $cust_id ) {
        $query = "SELECT * FROM `customer_detail` WHERE `cust_id`=$cust_id";
        if ( mysqli_query( $this->connection, $query ) ) {
            $result = mysqli_query( $this->connection, $query );
            // Tambahkan ke Activity Log: Customer Viewed by ID
            $this->saveLog($this->connection, $_SESSION['user_id'], 'User Viewed', "Viewed user details with ID: $cust_id");
    
            return $result;
        }
    }
    /* fungsi update biar ga lupa, status belum ditambah di database kalau belum jalan */

    
    function update_cust( $data ) {
        $u_id = $data[ 'cust_id' ];
        $u_name = $data[ 'cust_nama_entitas' ];
        $u_phone = $data[ 'cust_nomor_telepon_entitas' ];
        $u_address = $data[ 'cust_alamat_entitas' ];
        $u_website = $data[ 'cust_website' ];
        $u_sosmed = $data[ 'cust_sosmed' ];
        $u_pic_name = $data[ 'cust_nama_pic' ];
        $u_pic_phone = $data[ 'cust_nomor_telepon_pic' ];
        $u_pic_address = $data[ 'cust_alamat_pic' ];
        $u_status = $data[ 'cust_status' ];
        $query = "UPDATE `customer_detail` SET 
        `nama_entitas`='$u_name',
        `alamat_entitas`='$u_address',
        `phone_number_entitas`='$u_phone',
        `website`='$u_website',
        `sosmed`='$u_sosmed',
        `nama_pic`='$u_pic_name',
        `phone_number_pic`='$u_pic_phone',
        `alamat_pic`='$u_pic_address',
        `cust_status`='$u_status' 
      WHERE `cust_id`= $u_id";
        if ( mysqli_query( $this->connection, $query ) ) {
            // Tambahkan ke Activity Log: Customer Updated
        $this->saveLog($this->connection, $_SESSION['user_id'], 'Customer Updated', "Updated customer with ID: $u_id");
            $up_msg = 'Udated successfully';
            return $up_msg;
        }

    }
    /* fungsi delete biar ga lupa */

    function delete_cust($cust_id) {
        $query = "DELETE FROM `customer_detail` WHERE `cust_id`=$cust_id";
        if (mysqli_query($this->connection, $query)) {
            // Tambahkan ke Activity Log: Customer Deleted
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Customer Deleted', "Deleted customer with ID: $cust_id");
            
            $del_msg = 'Customer Deleted Successfully';
            return $del_msg;
        }
    }

    function show_lead_data()
 {
        $query = 'SELECT * FROM `customer_detail`,`lead_detail`';

        if ( mysqli_query( $this->connection, $query ) ) {
            $lead_info = mysqli_query( $this->connection, $query );
            return $lead_info;
        }
    }
    function show_order_data() {

    $query = "SELECT order_detail.*, customer_detail.nama_entitas FROM order_detail JOIN customer_detail ON order_detail.cust_id = customer_detail.cust_id";


        if ( mysqli_query( $this->connection, $query ) ) {
            $ctg_info = mysqli_query( $this->connection, $query );
            // Tambahkan ke Activity Log: Customer Viewed
            $this->saveLog($this->connection, $_SESSION['user_id'], 'User Viewed', 'Viewed Order Data list');
            return $ctg_info;
        }
    }
    
    

    function show_order_by_id( $order_id ) {
        $query = "SELECT * FROM order_detail WHERE order_id=$order_id";
        if ( mysqli_query( $this->connection, $query ) ) {
            $result = mysqli_query( $this->connection, $query );
            return $result;
        }
    }

    public function update_order( $data ) {
        $u_id = $data[ 'order_id' ];
        $orderDate = $data[ 'order_date' ];
        $deliveryDate = $data[ 'delivery_date' ];
        $namaMenu = $data[ 'nama_menu' ];
        $quantity = $data[ 'quantity' ];
        $price = $data[ 'price' ];
        $keterangan = $data[ 'keterangan' ];
        $query = "UPDATE order_detail SET order_date='$orderDate',delivery_date='$deliveryDate',nama_menu='$namaMenu',quantity= '$quantity', price= '$price', keterangan= '$keterangan' WHERE order_id= $u_id ";
        if ( mysqli_query( $this->connection, $query ) ) {
            $up_msg = 'Udated successfully';
            return $up_msg;
        }
    }

    function delete_order( $order_id ) {
        $query = "DELETE FROM order_detail WHERE order_id=$order_id";
        if ( mysqli_query( $this->connection, $query ) ) {
            $del_msg = 'User Deleted Successfully';
            return $del_msg;
        }
    }


    function show_lead_detail( $section ) {
        $query = "SELECT c.cust_id, c.nama_entitas, c.nama_pic, c.phone_number_pic, l.prog_date
                FROM customer_detail c
                INNER JOIN lead_detail l ON l.cust_id = c.cust_id
                WHERE l.progress = '$section'
                ORDER BY l.prog_date DESC;";
        if ( mysqli_query( $this->connection, $query ) ) {
            $result = mysqli_query( $this->connection, $query );
            // Tambahkan ke Activity Log: Customer Viewed
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Lead Viewed', 'Viewed Lead Data');
    
            return $result;
        }
    }
    function update_lead($cust_id, $new_prog) {
        // Ambil status progress sebelumnya dari database
        $query_old = "SELECT progress FROM lead_detail WHERE cust_id = $cust_id";
        $result = mysqli_query($this->connection, $query_old);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $old_prog = $row['progress'];
    
            // Update status progress dengan nilai baru
            $query = "UPDATE lead_detail SET progress='$new_prog', prog_date = CURRENT_TIMESTAMP WHERE cust_id= $cust_id";
        
            if (mysqli_query($this->connection, $query)) {
                // Tambahkan ke Activity Log: Lead Updated, menyimpan status progress sebelumnya dan yang baru
                $this->saveLog($this->connection, $_SESSION['user_id'], 'Lead Updated', "Updated lead progress for customer with ID: $cust_id from $old_prog to $new_prog");
        
                $update_msg = 'New progress to lead';
                return $update_msg;
            } else {
                return 'Failed to update lead progress';
            }
        } else {
            return 'Failed to retrieve old progress';
        }
    }

    function show_cust_data_add_lead() {
        $query = "SELECT c.* 
        FROM customer_detail c
        LEFT JOIN lead_detail l on c.cust_id = l.cust_id
        WHERE l.cust_id is NULL;";

        if ( mysqli_query( $this->connection, $query ) ) {
            $result = mysqli_query( $this->connection, $query );
            return $result;
        }
    }

    public function check_customer_exists($phone_number_pic) {
        $phone_number_pic = mysqli_real_escape_string($this->connection, $phone_number_pic);
        $query = "SELECT cust_id FROM customer_detail WHERE phone_number_pic = '$phone_number_pic'";
        $result = mysqli_query($this->connection, $query);
    
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $cust_id = $row['cust_id'];
    
            // Tambahkan ke Activity Log: Customer Checked
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Customer Checked', "Checked existence of customer with phone number: $phone_number_pic");
    
            return $cust_id;
        }
        return false;
    }
    

public function update_status_cust($data) {
    $u_pdt_id = $data['cust_id'];
    $u_pdt_status = $data['update_status'];

    // Map status dari integer ke string
    $status_map = [
        1 => 'Cold',
        2 => 'Warm',
        3 => 'Hot'
    ];

    // Ambil status progress sebelumnya dari database
    $query_old = "SELECT cust_status FROM customer_detail WHERE cust_id = $u_pdt_id";
    $result = mysqli_query($this->connection, $query_old);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $old_stat_int = $row['cust_status'];

        // Ambil status sebelumnya dari mapping
        $old_stat = isset($status_map[$old_stat_int]) ? $status_map[$old_stat_int] : 'Unknown';
    } else {
        $old_stat = 'Unknown'; // Jika status lama tidak ditemukan
    }

    // Ambil status baru dari mapping
    $status_string = isset($status_map[$u_pdt_status]) ? $status_map[$u_pdt_status] : 'Unknown';

    $query = "UPDATE customer_detail SET cust_status= '$status_string' WHERE cust_id= $u_pdt_id";

    if (mysqli_query($this->connection, $query)) {
        // Tambahkan ke Activity Log: Customer Status Updated
        $this->saveLog($this->connection, $_SESSION['user_id'], 'Customer Status Updated', "Updated status for customer with ID: $u_pdt_id from $old_stat to $status_string");

        $status_msg = 'Customer Status updated successfully';
        
        // Optionally re-fetch the updated customer data if needed (like in the order update case)
        $this->show_cust_data();

        return $status_msg;
    }
    
    return 'Failed to update customer status';
}

    
    

    public function update_status_order( $data ) {
        $u_pdt_id = $data[ 'order_id' ];
        $u_ord_status = $data[ 'order_status' ];

        $query = "UPDATE order_detail SET status_order=  $u_ord_status WHERE order_id= $u_pdt_id";
        if ( mysqli_query( $this->connection, $query ) ) {
            $status_msg = 'Customer Status updated successfully';
            return $status_msg;
        }
    }

    public function update_status_payment( $data ) {
        $u_pdt_id = $data[ 'order_id' ];
        $u_pay_status = $data[ 'status_payment' ];

        $query = "UPDATE order_detail SET payment_status=  $u_pay_status WHERE order_id= $u_pdt_id";
        if ( mysqli_query( $this->connection, $query ) ) {
            $status_msg = 'Customer Status updated successfully';
            return $status_msg;
        }
    }

    public function add_cust_order($data) {
        $conn = new mysqli('localhost', 'root', '', 'resapb2b');
    
        if ( $conn->connect_error ) {
            die( 'Connection failed: ' . $conn->connect_error );
        }
    
        $phone_number_pic = $data[ 'phone_number_pic' ];
    
        // Cek apakah pelanggan sudah ada
        $cust_id = $this->check_customer_exists( $phone_number_pic );
    
        if ( !$cust_id ) {
            // Pelanggan tidak ada, masukkan data pelanggan baru
            $stmt = $conn->prepare( 'INSERT INTO customer_detail (nama_entitas, alamat_entitas, cust_status, phone_number_entitas, website, sosmed, nama_pic, phone_number_pic, alamat_pic, sumber_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)' );
            $stmt->bind_param( 'ssssssssss', $data[ 'nama_entitas' ], $data[ 'alamat_entitas' ], $data[ 'cust_status' ], $data[ 'phone_number_entitas' ], $data[ 'website' ], $data[ 'sosmed' ], $data[ 'nama_pic' ], $data[ 'phone_number_pic' ], $data[ 'alamat_pic' ], $data[ 'sumber_info' ] );
    
            if ( $stmt->execute() ) {
                $cust_id = $stmt->insert_id;
                $stmt->close();
                
                // Tambahkan ke Activity Log: Customer added
                $this->saveLog($conn, $_SESSION['user_id'], 'Add Customer', 'Added new customer with ID: ' . $cust_id);
            } else {
                echo 'Error: ' . $stmt->error;
                $stmt->close();
                $conn->close();
                return 'Error: ' . $stmt->error;
            }
        }
    
        // Retrieve user_id from session
        if ( isset( $_SESSION[ 'user_id' ] ) ) {
            $user_id = $_SESSION[ 'user_id' ];
        } else {
            // Handle case where user_id is not set in the session
            echo 'Error: User ID is not set.';
            $conn->close();
            return 'Error: User ID is not set.';
        }
    
        // Cek apakah ada order data
        $order_exists = false;
        foreach ( $data as $key => $value ) {
            if ( strpos( $key, 'order_date_' ) === 0 ) {
                $order_exists = true;
                break;
            }
        }
    
        // Cek apakah lead sudah ada
        $lead_exists = $this->check_lead_exists( $cust_id );
    
        if ( !$lead_exists ) {
            // Masukkan data lead baru ke dalam tabel lead_detail
            $cust_status = $data[ 'cust_status' ];
            $progress = '';
            if ( $order_exists ) {
                $progress = 'Closed-Won';
            } else {
                $progress = 'New Leads';
            }
    
            $prog_date = date( 'Y-m-d' );
            // Set today's date for progress date
            $keterangan = ''; // Kosongkan keterangan
    
            $stmt_lead = $conn->prepare('INSERT INTO lead_detail ( cust_id, prog_date, progress, keterangan ) VALUES ( ?, ?, ?, ? )');
            $stmt_lead->bind_param('isss', $cust_id, $prog_date, $progress, $keterangan);
    
            if (!$stmt_lead->execute()) {
                echo 'Error: ' . $stmt_lead->error;
                $stmt_lead->close();
                $conn->close();
                return 'Error: ' . $stmt_lead->error;
            }
    
            $stmt_lead->close();
            
            // Tambahkan ke Activity Log: Lead added
            $this->saveLog($conn, $user_id, 'Add Lead', 'Added new lead for customer ID: ' . $cust_id);
        } elseif ($order_exists) {
            // Update lead_detail menjadi 'Closed-Won' jika order ada
            $progress = 'Closed-Won';
            $prog_date = date('Y-m-d');
            $stmt_update_lead = $conn->prepare('UPDATE lead_detail SET progress = ?, prog_date = ? WHERE cust_id = ?');
            $stmt_update_lead->bind_param('ssi', $progress, $prog_date, $cust_id);
    
            if (!$stmt_update_lead->execute()) {
                echo 'Error: ' . $stmt_update_lead->error;
                $stmt_update_lead->close();
                $conn->close();
                return 'Error: ' . $stmt_update_lead->error;
            }
    
            $stmt_update_lead->close();
            
            // Tambahkan ke Activity Log: Lead updated to Closed-Won
            $this->saveLog($conn, $user_id, 'Update Lead', 'Updated lead to Closed-Won for customer ID: ' . $cust_id);
        }
    
        // Masukkan data pesanan baru
        foreach ($data as $key => $value) {
            if (strpos($key, 'order_date_') === 0) {
                $index = str_replace('order_date_', '', $key);
    
                $order_date = $data["order_date_$index"];
                $delivery_date = $data["delivery_date_$index"];
                $nama_menu = $data["nama_menu_$index"];
                $quantity = $data["quantity_$index"];
                $price = $data["price_$index"];
                $keterangan = $data["keterangan_$index"];
                $status_order = $data["status_order_$index"];
                $payment_status = $data["payment_status_$index"];
    
                $stmt_order = $conn->prepare('INSERT INTO order_detail ( order_date, delivery_date, cust_id, nama_menu, quantity, price, keterangan, status_order, payment_status, user_id ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )');
                $stmt_order->bind_param('ssisssssii', $order_date, $delivery_date, $cust_id, $nama_menu, $quantity, $price, $keterangan, $status_order, $payment_status, $user_id);
    
                if ($stmt_order->execute()) {
                    // Tambahkan ke Activity Log: Order added
                    $this->saveLog($conn, $user_id, 'Add Order', "Added new order for customer ID: $cust_id, Order: $nama_menu, Quantity: $quantity");
                } else {
                    echo 'Error: ' . $stmt_order->error;
                }
    
                $stmt_order->close();
            }
        }
    
        $conn->close();
        return 'Customer and Order added successfully!';
    }

    
    
    private function check_lead_exists($cust_id) {
        // $conn = new mysqli('localhost', 'root', '', 'resapb2b');
        $conn = new mysqli('localhost', 'root', '', 'resapb2b');
    
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }
    
        $stmt = $conn->prepare('SELECT * FROM lead_detail WHERE cust_id = ?');
        $stmt->bind_param('i', $cust_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $exists = $result->num_rows > 0;
    
        $stmt->close();
        $conn->close();
    
        return $exists;
    }
    
    public function show_lead_progress(){
        $query = 'SELECT * FROM lead_progress';
        if ( mysqli_query( $this->connection, $query ) ) {
            $result = mysqli_query( $this->connection, $query );
            return $result;
        }
    }
    
    public function getFilteredProgress($filterProg = null, $startDate = null, $endDate = null) {
        $dateCondition = "";
        if ($startDate && $endDate) {
            // Ensure endDate is set to the end of the day
            $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
            $dateCondition = "AND log_time BETWEEN ? AND ?";
        }
    
        $progCondition = "";
        if ($filterProg) {
            $progCondition = "AND activity_details LIKE ?";
        }
    
        // Prepare the query with optional conditions and order by log_time DESC
        $query = "SELECT * FROM lead_progress WHERE 1=1 $dateCondition $progCondition ORDER BY log_time DESC";
    
        $stmt = mysqli_prepare($this->connection, $query);
    
        $params = [];
        $types = "";
    
        if ($dateCondition) {
            $types .= "ss";
            $params[] = $startDate;
            $params[] = $endDate;
        }
    
        if ($progCondition) {
            $types .= "s";
            $params[] = '%' . $filterProg . '%';
        }
    
        // Bind parameters
        if ($params) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
    
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        if ($result) {
            $progress = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
            mysqli_stmt_close($stmt);
            return $progress;
        } else {
            echo 'Error: ' . mysqli_error($this->connection);
            return false;
        }
    }

    
    

    public function search_customer($keyword) {
        $keyword = mysqli_real_escape_string($this->connection, $keyword);
        $query = "SELECT * FROM customer_detail WHERE phone_number_pic LIKE '%$keyword%'";
        $search_query = mysqli_query($this->connection, $query);
    
        if ($search_query) {
            // Tambahkan ke Activity Log: Customer Searched
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Customer Searched', "Searched customer by phone number with keyword: $keyword");
    
            return $search_query;
        } else {
            echo 'Error: ' . mysqli_error($this->connection);
        }
    }

    function search_cust($keyword) {
        $query = "SELECT * FROM customer_detail WHERE nama_entitas LIKE '%$keyword%'";
    
        if (mysqli_query($this->connection, $query)) {
            $search_query = mysqli_query($this->connection, $query);
    
            // Tambahkan ke Activity Log: Customer Searched
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Customer Searched', "Searched customer by entity name with keyword: $keyword");
    
            return $search_query;
        }
    }
    
        function search_custb2c($keyword) {
        $query = "SELECT * FROM cust_b2c WHERE NamaDepan LIKE '%$keyword%'";
    
        if (mysqli_query($this->connection, $query)) {
            $search_query = mysqli_query($this->connection, $query);
    
            return $search_query;
        }
    }

    function search_order( $keyword ) {
        $query = "SELECT order_detail.*, customer_detail.nama_entitas 
                  FROM order_detail 
                  JOIN customer_detail 
                  ON order_detail.cust_id = customer_detail.cust_id 
                  WHERE customer_detail.nama_entitas LIKE '%$keyword%'";

            if ( mysqli_query( $this->connection, $query ) ) {
                $search_query = mysqli_query( $this->connection, $query );
                return $search_query;
            }
        }
        
        function search_orderb2c( $keyword ) {
        $query = "SELECT order_datab2c.*, cust_b2c.NamaDepan 
                  FROM order_datab2c 
                  JOIN cust_b2c 
                  ON order_datab2c.CustomerID = cust_b2c.CustomerID 
                  WHERE cust_b2c.NamaDepan LIKE '%$keyword%'";

            if ( mysqli_query( $this->connection, $query ) ) {
                $search_query = mysqli_query( $this->connection, $query );
                return $search_query;
            }
        }

        public function saveLog($conn, $user_id, $activity_type, $activity_details) {
            $stmt_log = $conn->prepare('INSERT INTO activity_log (user_id, activity_type, activity_details, log_time) VALUES (?, ?, ?, NOW())');
            $stmt_log->bind_param('iss', $user_id, $activity_type, $activity_details);
        
            if (!$stmt_log->execute()) {
                echo 'Error: ' . $stmt_log->error;
            }
        
            $stmt_log->close();
        }

        public function getOrdersByDateRange($startDate, $endDate) {
            // Prepare the SQL query with JOIN to fetch orders within the date range
            $query = "SELECT order_detail.*, customer_detail.nama_entitas 
                      FROM order_detail 
                      JOIN customer_detail 
                      ON order_detail.cust_id = customer_detail.cust_id 
                      WHERE order_detail.order_date BETWEEN ? AND ? 
                      ORDER BY order_detail.order_date DESC";
    
            // Prepare the statement
            $stmt = mysqli_prepare($this->connection, $query);
            mysqli_stmt_bind_param($stmt, "ss", $startDate, $endDate);
    
            // Execute the statement
            mysqli_stmt_execute($stmt);
    
            // Get the result
            $result = mysqli_stmt_get_result($stmt);
    
            // Fetch all the results as an associative array
            $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
            // Free the result and close the statement
            mysqli_free_result($result);
            mysqli_stmt_close($stmt);
    
            return $orders;
        }
        
        public function get_item_names() {
        $query = "SELECT DISTINCT item_name FROM items";
        $result = mysqli_query($this->connection, $query);
        $item_names = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $item_names[] = $row['item_name'];
        }
        return $item_names;
    }
    
        public function updateItemStock($itemId, $quantity) {
        // Prepare SQL query to update current_stock based on item_id
        $sql = "UPDATE items SET current_stock = current_stock + ? WHERE item_id = ?";
        
        if ($stmt = $this->connection->prepare($sql)) {
            // Bind the parameters
            $stmt->bind_param("ii", $quantity, $itemId);
            
            // Execute the statement
            if ($stmt->execute()) {
                echo '<p style="color: green;">Item stock updated successfully!</p>';
            } else {
                echo "Error: " . $stmt->error;
            }
            
            // Close the statement
            $stmt->close();
        } else {
            echo "Error: " . $this->connection->error;
        }
    }
    
        public function addStock($data) {
        // Extract data from the form submission
        $purchaseDate = $data['purchaseDate'];
        $itemName = $data['filter_Name'];
        $category = $data['category'];
        $qty = $data['qty'];
        $unit = $data['unit'];
        $price = str_replace(['Rp. ', '.'], '', $data['price']);
        $price = floatval(str_replace(',', '.', $price));
        $totalPrice = str_replace(['Rp. ', '.'], '', $data['totalPrice']);
        $totalPrice = floatval(str_replace(',', '.', $totalPrice));
        $supplierId = $data['supplier_id'];
        $notes = $data['notes'];
        
                        // Ambil user_id dari session
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Log untuk memastikan nilai user_id
    error_log("User ID in addStock function: " . $user_id);
    
    // Check if user_id is set in the session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        // If user_id is not set, handle the error
        echo 'Error: User ID is not set in session. Please login.';
        return;  // Exit the function early if user_id is missing
    }
    
        // Get item_id by itemName
        $itemId = $this->getItemIdByName($itemName);
    
        if ($itemId === null) {
            echo "Error: Item not found.";
            return;
        }
    
        // Prepare the SQL query to insert data, now including user_id
        $sql = "INSERT INTO inventory_in (user_id, date_in, item_id, category, quantity, unit, unit_price, total_price, supplier_id, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
    
        // Prepare the statement
        if ($stmt = $this->connection->prepare($sql)) {
            // Bind parameters to the prepared statement
            $stmt->bind_param(
                "issssssiss", 
                $user_id, 
                $purchaseDate, 
                $itemId, 
                $category, 
                $qty, 
                $unit, 
                $price, 
                $totalPrice, 
                $supplierId, 
                $notes
            );
            
            
            // Execute the statement
            if ($stmt->execute()) {
                echo "Stock added successfully!";
                
                // Update item stock in the items table
                $this->updateItemStock($itemId, $qty);
    
                // Get the current stock from the items table after update
                $currentStock = $this->getCurrentStock($itemId);
    
                // Call saveLog to log the activity with current stock
                $logMessage = "Added stock for item with ID: $itemId, Name: $itemName, Quantity: $qty, Total Stock: $currentStock, Date: $purchaseDate";
                $this->saveLog($this->connection, $user_id, 'Stock In', $logMessage);
            } else {
                echo "Error: " . $stmt->error;
            }
    
            // Close the statement
            $stmt->close();
        } else {
            echo "Error: " . $this->connection->error;
        }
    }


        public function insertItem($data) {
    
        // Extract data from the form submission
        $itemName = $data['itemName'];
        $minStock = $data['minStock'];
        $currentStock = $data['currentStock'];
        $description = $data['description'];
    
        // Prepare the SQL query to insert data
        $sql = "INSERT INTO items (item_name, min_stock, current_stock, description) VALUES (?, ?, ?, ?)";
    
        // Prepare the statement
        if ($stmt = $this->connection->prepare($sql)) {
            // Bind parameters to the prepared statement
            $stmt->bind_param("ssis", $itemName, $minStock, $currentStock, $description);
    
            // Execute the statement
            if ($stmt->execute()) {
                echo "Item added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
    
            // Close the statement
            $stmt->close();
        } else {
            echo "Error: " . $this->connection->error;
        }
    }
    
        public function generate_supplier_dropdown() {
        $html = '<select id="supplier" name="supplier_id" class="form-control" required>';
        $html .= '<option value="">Pilih Supplier</option>';
    
        $query = "SELECT supplier_id, full_name FROM supplier_detail";
        if ($result = mysqli_query($this->connection, $query)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $html .= "<option value='" . $row['supplier_id'] . "'>" . $row['full_name'] . "</option>";
            }
        } else {
            $html .= "<option value=''>Error fetching suppliers</option>";
        }
    
        $html .= '</select>';
        return $html;
    }
    
        function generate_supplier_dropdown_in($selected_id = null) {
        $query = "SELECT supplier_id, full_name FROM supplier_detail";
        $result = mysqli_query($this->connection, $query);
        
        $dropdown = '';
        while ($row = mysqli_fetch_assoc($result)) {
            // Check if this supplier_id is the selected one
            $selected = ($selected_id == $row['supplier_id']) ? 'selected' : '';
            $dropdown .= '<option value="' . $row['supplier_id'] . '" ' . $selected . '>' . $row['full_name'] . '</option>';
        }
        return $dropdown;
    }
    
    public function get_item() {
        $query = "SELECT DISTINCT item_name FROM stock_inventory";
        $result = mysqli_query($this->connection, $query);
        $categories = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row['item_name'];
        }
        return $categories;
    }
    
    
    public function getItemIdByName($itemName) {
    $sql = "SELECT item_id FROM items WHERE item_name = ?";
    
    // Gunakan LIKE untuk pencarian tanpa case-sensitive jika database tidak case-insensitive
    //$sql = "SELECT item_id FROM items WHERE item_name LIKE ?";
    
    if ($stmt = $this->connection->prepare($sql)) {
        // Bind item_name
        $stmt->bind_param("s", $itemName);
        $stmt->execute();
        $stmt->bind_result($itemId);
        if ($stmt->fetch()) {
            return $itemId; // Return the found item ID
        }
        $stmt->close();
    }
    
    return null; // Return null if item not found
}

    
    
    function show_inventory() {
        $query = 'SELECT * FROM `stock_inventory` ORDER BY `latest_date_in` DESC';
    
        if (mysqli_query($this->connection, $query)) {
            $result = mysqli_query($this->connection, $query);
            
            // Tambahkan ke Activity Log: User Viewed
            $this->saveLog($this->connection, $_SESSION['user_id'], 'User Viewed', 'Viewed Stock Inventory');
    
            return $result;
        }
    }
    
    function delete_stock( $item_id ) {
        $query = "DELETE FROM `stock_inventory` WHERE `item_id`=$item_id";
        if ( mysqli_query( $this->connection, $query ) ) {
            // Tambahkan ke Activity Log: Customer Deleted
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Stock Deleted', "Deleted Stock with ID: $item_id");
                
            $del_msg = 'Stock Deleted Successfully';
            return $del_msg;
        }
    }
    
    public function search_item($keyword) {
        $keyword = mysqli_real_escape_string($this->connection, $keyword);
        $query = "SELECT * FROM stock_inventory WHERE item_name = '$keyword'";
        return mysqli_query($this->connection, $query);
    }
    
        public function filterKategori($keyword) {
        $connection = $this->connection;
        $arry = []; // Inisialisasi array kosong
    
        // Menyiapkan query dengan menggunakan prepared statement
        $query = "SELECT * FROM stock_inventory WHERE category LIKE ?";
        $stmt = mysqli_prepare($connection, $query);
        
        if ($stmt) {
            // Menambahkan wildcard untuk LIKE clause dan mengikat parameter
            $keyword = "%{$keyword}%";
            mysqli_stmt_bind_param($stmt, 's', $keyword);
            
            // Mengeksekusi statement
            if (mysqli_stmt_execute($stmt)) {
                // Mengambil hasil query
                $result = mysqli_stmt_get_result($stmt);
    
                // Jika ada hasil, isi array dengan data; jika tidak, tetap kosong
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $arry[] = $row; // Menambahkan setiap baris hasil ke array
                    }
    
                    // Tambahkan ke Activity Log: Category Filtered
                    $this->saveLog($connection, $_SESSION['user_id'], 'Category Filtered', "Filtered items by category name with keyword: $keyword");
                }
                // Jika tidak ada hasil, biarkan $arry tetap kosong
            } else {
                // Menangani kesalahan jika eksekusi statement gagal
                echo "Error executing statement: " . mysqli_stmt_error($stmt);
            }
        } else {
            // Menangani kesalahan jika prepare statement gagal
            echo "Error preparing statement: " . mysqli_error($connection);
        }
        
        return $arry; // Kembalikan array, bisa kosong jika tidak ada hasil
    }
    
    public function getFilteredProgressStock($filterProg = null, $startDate = null, $endDate = null) {
        $dateCondition = "";
        if ($startDate && $endDate) {
            // Ensure endDate is set to the end of the day
            $endDate = date('Y-m-d 23:59:59', strtotime($endDate));
            $dateCondition = "AND log_time BETWEEN ? AND ?";
        }
    
        $progCondition = "";
        if ($filterProg) {
            $progCondition = "AND activity_details LIKE ?";
        }
    
        // Prepare the query with optional conditions and order by log_time DESC
        $query = "SELECT * FROM stock_progress WHERE 1=1 $dateCondition $progCondition ORDER BY log_time DESC";
    
        $stmt = mysqli_prepare($this->connection, $query);
    
        $params = [];
        $types = "";
    
        if ($dateCondition) {
            $types .= "ss";
            $params[] = $startDate;
            $params[] = $endDate;
        }
    
        if ($progCondition) {
            $types .= "s";
            $params[] = '%' . $filterProg . '%';
        }
    
        // Bind parameters
        if ($params) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
    
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        if ($result) {
            $progress = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
            mysqli_stmt_close($stmt);
            return $progress;
        } else {
            echo 'Error: ' . mysqli_error($this->connection);
            return false;
        }
    }
    
        function show_stockin_by_id($stock_id) {
        // Prepare the SQL query to join inventory_in, supplier_detail, and items
        $query = "
            SELECT i.*, it.item_name, sd.full_name 
            FROM inventory_in i 
            JOIN items it ON i.item_id = it.item_id 
            JOIN supplier_detail sd ON i.supplier_id = sd.supplier_id 
            WHERE i.inventory_in_id = ?
        ";
    
        // Prepare the statement
        $stmt = $this->connection->prepare($query);
        
        // Bind the stock_id parameter
        $stmt->bind_param("i", $stock_id);
        
        // Execute the statement
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
    
            // Tambahkan ke Activity Log: Stock In Viewed by ID
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Stock In Viewed', "Viewed Stock In details with ID: $stock_id");
    
            return $row; // Kembalikan data yang ditemukan
        } else {
            return null; // Tidak ada data yang ditemukan
        }
    }
    
        function show_stockout_by_id($stock_id) {
        $query = "
        SELECT i.*, it.item_name 
        FROM inventory_out i 
        JOIN items it ON i.item_id = it.item_id 
        WHERE i.inventory_out_id = ?
    ";
    
        // Prepare the statement
        $stmt = $this->connection->prepare($query);
        
        // Bind the stock_id parameter
        $stmt->bind_param("i", $stock_id);
        
        // Execute the statement
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
    
            // Tambahkan ke Activity Log: Stock In Viewed by ID
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Stock Out Viewed', "Viewed Stock Out details with ID: $stock_id");
    
            return $row; // Kembalikan data yang ditemukan
        } else {
            return null; // Tidak ada data yang ditemukan
        }
    }
    
        public function update_stockin($data) {
        // Ambil data dari form
        $stock_id = $data['stock_id'];
        $purchaseDate = $data['purchaseDate'];
        $itemName = $data['filter_Name'];
        $category = $data['category'];
        $qty = $data['qty'];
        $unit = $data['unit'];
        $supplier_id = $data['supplier_id'];
        $notes = $data['notes'];
    
        // Ambil nilai price dan totalPrice dari form
        $price = str_replace(['Rp. ', '.'], '', $data['price']);
        $price = floatval(str_replace(',', '.', $price));
        $totalPrice = str_replace(['Rp. ', '.'], '', $data['totalPrice']);
        $totalPrice = floatval(str_replace(',', '.', $totalPrice));
    
        // Dapatkan item_id berdasarkan itemName
        $item_id = $this->getItemIdByName($itemName);
        if ($item_id === null) {
            echo "Error: Item not found.";
            return;
        }
    
        // Ambil price dan totalPrice saat ini dari database jika kosong
        if (empty($data['price']) || $price <= 0) {
            $price = $this->getCurrentPrice($stock_id);
        }
    
        if (empty($data['totalPrice']) || $totalPrice <= 0) {
            $totalPrice = $this->getCurrentTotalPrice($stock_id);
        }
    
        // Ambil quantity sebelumnya untuk menghitung perubahan stock
        $previousQty = $this->getPreviousQuantity($stock_id);
        $qtyDifference = $qty - $previousQty;
    
        // Query untuk memperbarui stok masuk
        $query = "UPDATE `inventory_in` SET 
            `date_in` = ?, 
            `item_id` = ?, 
            `category` = ?, 
            `quantity` = ?, 
            `unit` = ?, 
            `unit_price` = ?, 
            `total_price` = ?, 
            `supplier_id` = ?, 
            `description` = ? 
          WHERE `inventory_in_id` = ?";
    
        if ($stmt = $this->connection->prepare($query)) {
            $stmt->bind_param(
                "sisssdsdsi", 
                $purchaseDate, 
                $item_id, 
                $category, 
                $qty, 
                $unit, 
                $price, 
                $totalPrice, 
                $supplier_id, 
                $notes, 
                $stock_id
            );
    
            if ($stmt->execute()) {
                // Update stock di items table
                if ($qtyDifference !== 0) {
                    $this->updateItemStock($item_id, $qtyDifference);
                }
    
                $this->saveLog($this->connection, $_SESSION['user_id'], 'Stock In Updated', "Updated stock with ID: $stock_id");
                // Tambahkan pesan sukses
                echo '<p style="color: green;">Stock in updated successfully!</p>';
                return; // Jika tidak ada pesan kesalahan, akhiri fungsi di sini
            } else {
                return 'Gagal memperbarui stok: ' . $stmt->error;
            }
        } else {
            return 'Error: ' . $this->connection->error;
        }
    }
    
    private function getPreviousQuantity($stock_id) {
        $sql = "SELECT quantity FROM inventory_in WHERE inventory_in_id = ?";
        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param("i", $stock_id);
            $stmt->execute();
            $stmt->bind_result($previousQuantity);
            $stmt->fetch();
            $stmt->close();
            return $previousQuantity;
        } else {
            echo "Error: " . $this->connection->error;
            return null;
        }
    }
    
    
    
    // Fungsi untuk mendapatkan price saat ini dari database
    private function getCurrentPrice($stock_id) {
        $query = "SELECT unit_price FROM inventory_in WHERE inventory_in_id = ?";
        if ($stmt = $this->connection->prepare($query)) {
            $stmt->bind_param("i", $stock_id);
            $stmt->execute();
            $stmt->bind_result($price);
            $stmt->fetch();
            $stmt->close();
            return $price;
        }
        return 0; // Default jika tidak ada
    }
    
    // Fungsi untuk mendapatkan totalPrice saat ini dari database
    private function getCurrentTotalPrice($stock_id) {
        $query = "SELECT total_price FROM inventory_in WHERE inventory_in_id = ?";
        if ($stmt = $this->connection->prepare($query)) {
            $stmt->bind_param("i", $stock_id);
            $stmt->execute();
            $stmt->bind_result($totalPrice);
            $stmt->fetch();
            $stmt->close();
            return $totalPrice;
        }
        return 0; // Default jika tidak ada
    }
    
        public function update_stockout($data) {
        // Ambil data dari form
        $stock_id = $data['stock_id'];
        $itemName = $data['filter_Name'];
        $qty = $data['qty'];
        $description = $data['notes']; // Use destination directly
        $dateOut = $data['purchaseDate']; // Assume this field exists in your form
    
        // Dapatkan item_id berdasarkan itemName
        $item_id = $this->getItemIdByName($itemName);
        if ($item_id === null) {
            echo "Error: Item not found.";
            return;
        }
    
        // Ambil quantity sebelumnya untuk menghitung perubahan stock
        $previousQty = $this->getPreviousQuantityOut($stock_id); // New function to get previous quantity
        $qtyDifference = $qty - $previousQty;
    
        // Query untuk memperbarui stok keluar
        $query = "UPDATE `inventory_out` SET 
            `date_out` = ?, 
            `item_id` = ?, 
            `quantity` = ?, 
            `description` = ? 
          WHERE `inventory_out_id` = ?";
    
        if ($stmt = $this->connection->prepare($query)) {
            $stmt->bind_param(
                "sissi", 
                $dateOut, 
                $item_id, 
                $qty, 
                $description, 
                $stock_id
            );
    
            if ($stmt->execute()) {
                // Update stock di items table
                if ($qtyDifference !== 0) {
                    $this->updateItemStock($item_id, -$qtyDifference); // Decrease stock
                }
    
                $this->saveLog($this->connection, $_SESSION['user_id'], 'Stock Out Updated', "Updated stock out with ID: $stock_id");
                // Tambahkan pesan sukses
                echo '<p style="color: green;">Stock out updated successfully!</p>';
                return; // Jika tidak ada pesan kesalahan, akhiri fungsi di sini
            } else {
                return 'Gagal memperbarui stok: ' . $stmt->error;
            }
        } else {
            return 'Error: ' . $this->connection->error;
        }
    }
    
    private function getPreviousQuantityOut($stock_id) {
        $sql = "SELECT quantity FROM inventory_out WHERE inventory_out_id = ?";
        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param("i", $stock_id);
            $stmt->execute();
            $stmt->bind_result($previousQuantity);
            $stmt->fetch();
            $stmt->close();
            return $previousQuantity;
        } else {
            echo "Error: " . $this->connection->error;
            return null;
        }
    }
    
    
    function show_supp_data() {
            $query = 'SELECT * FROM supplier_detail';
            if ( mysqli_query( $this->connection, $query ) ) {
                $result = mysqli_query( $this->connection, $query );
                
                // Tambahkan ke Activity Log: User Viewed
                $this->saveLog($this->connection, $_SESSION['user_id'], 'Supplier Viewed', 'Viewed supplier list');
                return $result;
            }
        }
    
    function delete_supp( $supp_id ) {
            $query = "DELETE FROM supplier_detail WHERE supplier_id=$supp_id";
            if ( mysqli_query( $this->connection, $query ) ) {

                // Tambahkan ke Activity Log: User Deleted
                $this->saveLog($this->connection, $_SESSION['user_id'], 'Supplier Deleted', "Deleted supplier with ID: $supp_id");
                $del_msg = 'Supplier Deleted Successfully';
                return $del_msg;
            }
        }
        
        public function getInventoryViewByDateRange( $startDate, $endDate ) {
        $query = "SELECT item_id, item_name, current_stock, description, latest_date_in, category, unit 
              FROM stock_inventory 
              WHERE latest_date_in BETWEEN ? AND ?
              ORDER BY latest_date_in DESC";

        $stmt = $this->connection->prepare( $query );
        $stmt->bind_param( 'ss', $startDate, $endDate );

        if ( $stmt->execute() ) {
            $inventory_info = $stmt->get_result();
            // Activity Log: Filter Inventory Data Berdasarkan Rentang Tanggal
            $this->saveLog( $this->connection, $_SESSION[ 'user_id' ], 'User Filtered', 'Filtered Inventory Data by Date Range' );
            return $inventory_info;
        }
    }
    
    function search_supp( $keyword ) {
        $query = "SELECT *
                  FROM supplier_detail
                  WHERE full_name LIKE '%$keyword%'";

        if ( mysqli_query( $this->connection, $query ) ) {
            $search_query = mysqli_query( $this->connection, $query );
            return $search_query;
        }
    }
    
        public function usageItemStock($itemId, $quantity) {
        // Prepare SQL query to update current_stock based on item_id
        $sql = "UPDATE items SET current_stock = current_stock - ? WHERE item_id = ?";
    
        if ($stmt = $this->connection->prepare($sql)) {
            // Bind the parameters
            $stmt->bind_param("ii", $quantity, $itemId);
            
            // Execute the statement
            if ($stmt->execute()) {
                echo '<p style="color: green;">Item stock updated successfully!</p>';
            } else {
                // Output the SQL error
                echo "Error: " . $stmt->error;
            }
            
            // Close the statement
            $stmt->close();
        } else {
            // Output the connection error
            echo "Error: " . $this->connection->error;
        }
    }
    
    public function reduceStock($data) {
    // Make sure session is started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Retrieve the user_id from session
    $userId = $_SESSION['user_id'];

    // Extract data from the form submission
    $purchaseDate = $data['purchaseDate'];
    $itemNames = $data['itemName']; // Array of item names
    $qty = $data['qty']; // Same quantity for all items
    $notes = $data['notes']; // Same notes for all items

    foreach ($itemNames as $itemName) {
        // Get item_id by itemName
        $itemId = $this->getItemIdByName($itemName);

        if ($itemId === null) {
            echo "Error: Item '$itemName' not found.";
            continue; // Skip to the next item if not found
        }

        // Check current stock
        $currentStock = $this->getCurrentStock($itemId);
        
        // Validasi apakah qty melebihi current_stock
        if ($qty > $currentStock) {
            echo "<span style='color: red;'>Pengurangan stok gagal untuk '$itemName'. Jumlah yang ingin dikurangi ($qty) melebihi sisa stok yang tersedia ($currentStock). Silakan masukkan jumlah yang lebih kecil.</span>";
            continue; // Skip to the next item
        }

        // Prepare the SQL query to insert data, now including user_id
        $sql = "INSERT INTO inventory_out (user_id, date_out, item_id, quantity, description) VALUES (?, ?, ?, ?, ?)";

        // Prepare the statement
        if ($stmt = $this->connection->prepare($sql)) {
            // Bind parameters to the prepared statement
            $stmt->bind_param("issis", $userId, $purchaseDate, $itemId, $qty, $notes);

            // Execute the statement
            if ($stmt->execute()) {
                echo "Pengurangan stok untuk '$itemName' berhasil!";

                // Update item stock in the items table
                $this->usageItemStock($itemId, $qty);

                // Get the current stock from the items table after update
                $currentStock = $this->getCurrentStock($itemId);

                // Call saveLog to log the activity with current stock
                $logMessage = "Reduced stock for item with ID: $itemId, Name: $itemName, Quantity: $qty, Remaining Stock: $currentStock, Date: $purchaseDate";
                $this->saveLog($this->connection, $userId, 'Stock Out', $logMessage);
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error: " . $this->connection->error;
        }
    }
}


    
        // Function to get the current stock from the items table
    private function getCurrentStock($itemId) {
        $sql = "SELECT current_stock FROM items WHERE item_id = ?";
        if ($stmt = $this->connection->prepare($sql)) {
            $stmt->bind_param("i", $itemId);
            $stmt->execute();
            $stmt->bind_result($currentStock);
            $stmt->fetch();
            $stmt->close();
            return $currentStock;
        } else {
            echo "Error: " . $this->connection->error;
            return null;
        }
    }
    
    function delete_stockIn( $inventory_in_id ) {
        $query = "DELETE FROM `inventory_in` WHERE `inventory_in_id`=$inventory_in_id";
        if ( mysqli_query( $this->connection, $query ) ) {
            // Tambahkan ke Activity Log: Customer Deleted
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Stock Deleted', "Deleted Stock with ID: $inventory_in_id");
                
            $del_msg = 'Stock Deleted Successfully';
            return $del_msg;
        }
    }
    
function search_itemname($keyword) {
    
    $query = "
    SELECT i.inventory_in_id, i.user_id, i.date_in, it.item_name, i.category, i.quantity, i.unit, i.unit_price, i.total_price, s.full_name, i.description, ud.user_role
    FROM inventory_in i
    LEFT JOIN items it ON i.item_id = it.item_id
    LEFT JOIN supplier_detail s ON i.supplier_id = s.supplier_id
    LEFT JOIN user_detail ud ON i.user_id = ud.user_id
    WHERE it.item_name = '$keyword'
    ORDER BY i.date_in DESC";
$stmt = mysqli_prepare($this->connection, $query);
mysqli_stmt_bind_param($stmt, "s", $keyword);
mysqli_stmt_execute($stmt);
$search_query = mysqli_stmt_get_result($stmt);



    if (mysqli_query($this->connection, $query)) {
        $search_query = mysqli_query($this->connection, $query);
        return $search_query;
    }
}


    
    function show_inventory_data(){
    $query = 'SELECT i.inventory_in_id, i.user_id, i.date_in, it.item_name, i.category, i.quantity, i.unit, i.unit_price, i.total_price, s.full_name, i.description, ud.user_role
              FROM inventory_in i
              LEFT JOIN items it ON i.item_id = it.item_id
              LEFT JOIN supplier_detail s ON i.supplier_id = s.supplier_id
              LEFT JOIN user_detail ud ON i.user_id = ud.user_id 
              ORDER BY i.date_in DESC';

    $inventory_info = mysqli_query($this->connection, $query);

    if ($inventory_info) {
        // Tambahkan ke Activity Log: User Viewed Inventory Data
        $this->saveLog($this->connection, $_SESSION['user_id'], 'User Viewed', 'Viewed Inventory Data');

        // Ambil data dan kembalikan hasilnya
        return mysqli_fetch_all($inventory_info, MYSQLI_ASSOC);
    } else {
        // Tangani error query
        die("Query error: " . mysqli_error($this->connection));
    }
}



    
    public function filterCategory($keyword) {
        $connection = $this->connection;
        $arry = []; // Inisialisasi array kosong
    
        // Menyiapkan query dengan menggunakan prepared statement
        $query = "SELECT i.inventory_in_id, i.user_id, i.date_in, it.item_name, i.category, i.quantity, i.unit, i.unit_price, i.total_price, s.full_name, i.description, ud.user_role
                FROM inventory_in i
                LEFT JOIN items it ON i.item_id = it.item_id
                LEFT JOIN supplier_detail s ON i.supplier_id = s.supplier_id
                LEFT JOIN user_detail ud ON i.user_id = ud.user_id 
                WHERE i.category LIKE ?";
        $stmt = mysqli_prepare($connection, $query);
        
        if ($stmt) {
            // Menambahkan wildcard untuk LIKE clause dan mengikat parameter
            $keyword = "%{$keyword}%";
            mysqli_stmt_bind_param($stmt, 's', $keyword);
            
            // Mengeksekusi statement
            if (mysqli_stmt_execute($stmt)) {
                // Mengambil hasil query
                $result = mysqli_stmt_get_result($stmt);
    
                // Jika ada hasil, isi array dengan data; jika tidak, tetap kosong
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $arry[] = $row; // Menambahkan setiap baris hasil ke array
                    }
    
                    // Tambahkan ke Activity Log: Category Filtered
                    $this->saveLog($connection, $_SESSION['user_id'], 'Category Filtered', "Filtered items by category name with keyword: $keyword");
                }
                // Jika tidak ada hasil, biarkan $arry tetap kosong
            } else {
                // Menangani kesalahan jika eksekusi statement gagal
                echo "Error executing statement: " . mysqli_stmt_error($stmt);
            }
        } else {
            // Menangani kesalahan jika prepare statement gagal
            echo "Error preparing statement: " . mysqli_error($connection);
        }
        
        return $arry; // Kembalikan array, bisa kosong jika tidak ada hasil
    }
    
    function getInventoryByDateRange( $startDate, $endDate ) {
        $query = "SELECT i.inventory_in_id, i.user_id, i.date_in, it.item_name, i.category, i.quantity, i.unit, i.unit_price, i.total_price, s.full_name, i.description, ud.user_role
              FROM inventory_in i
              JOIN items it ON i.item_id = it.item_id
              JOIN supplier_detail s ON i.supplier_id = s.supplier_id
              JOIN user_detail ud ON i.user_id = ud.user_id
              WHERE i.date_in BETWEEN ? AND ?
              ORDER BY i.date_in DESC";

        $stmt = $this->connection->prepare( $query );
        $stmt->bind_param( 'ss', $startDate, $endDate );

        if ( $stmt->execute() ) {
            $inventory_info = $stmt->get_result();
            // Activity Log: Filter Inventory Data Berdasarkan Rentang Tanggal
            $this->saveLog( $this->connection, $_SESSION[ 'user_id' ], 'User Filtered', 'Filtered Stock In Data by Date Range' );
            return $inventory_info;
        }
    }

    
    function delete_stockOut( $inventory_out_id ) {
        $query = "DELETE FROM `inventory_out` WHERE `inventory_out_id`=$inventory_out_id";
        if ( mysqli_query( $this->connection, $query ) ) {
            // Tambahkan ke Activity Log: Customer Deleted
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Stock Deleted', "Deleted Stock with ID: $inventory_out_id");
                
            $del_msg = 'Stock Deleted Successfully';
            return $del_msg;
        }
    }
    
    
    function search_stockOut($keyword) {
        $query = "
        SELECT i.inventory_out_id, i.user_id, i.date_out, it.item_name, i.quantity, i.description, ud.user_role
        FROM inventory_out i
        LEFT JOIN items it ON i.item_id = it.item_id
        LEFT JOIN user_detail ud ON i.user_id = ud.user_id
        WHERE it.item_name = ?
        ORDER BY i.date_out DESC";

    $stmt = $this->connection->prepare($query);
    $stmt->bind_param("s", $keyword);

    if ($stmt->execute()) {
        $search_query = $stmt->get_result();
        // Tambahkan ke Activity Log: Order Searched
        $this->saveLog($this->connection, $_SESSION['user_id'], 'Order Searched', "Searched order by customer entity name with keyword: $keyword");
        return $search_query;
    }
    return false; // return false jika gagal
    }
    
    public function getInventoryOutByDateRange( $startDate, $endDate ) {
        $query = "SELECT i.inventory_out_id, it.item_name, i.quantity, i.date_out, i.description, ud.user_role 
    FROM inventory_out i 
    LEFT JOIN items it ON i.item_id = it.item_id 
    LEFT JOIN user_detail ud ON i.user_id = ud.user_id 
    WHERE i.date_out BETWEEN ? AND ?
    ORDER BY i.date_out DESC";

        $stmt = $this->connection->prepare( $query );
        $stmt->bind_param( 'ss', $startDate, $endDate );

        if ( $stmt->execute() ) {
            $inventory_info = $stmt->get_result();
            // Activity Log: Filter Inventory Data Berdasarkan Rentang Tanggal
            $this->saveLog( $this->connection, $_SESSION[ 'user_id' ], 'User Filtered', 'Filtered Inventory Data by Date Range' );
            return $inventory_info;
        }
    }
    
    function show_stockOut() {
        $query = 'SELECT i.inventory_out_id, it.item_name, i.quantity, i.date_out, i.description, ud.user_role 
        FROM inventory_out i 
        LEFT JOIN items it ON i.item_id = it.item_id 
        LEFT JOIN user_detail ud ON i.user_id = ud.user_id 
        ORDER BY i.date_out DESC';
        
        if (mysqli_query($this->connection, $query)) {
            $stockOut_info = mysqli_query($this->connection, $query);
            // Activity LOg: Lihat Inventory Data 
            $this->saveLog($this->connection, $_SESSION['user_id'], 'User Viewed', 'Viewed Inventory Data');
            return $stockOut_info;
        }
    }
    
    function show_data_cust() {
        $query = 'SELECT * FROM cust_b2c';
        if ( mysqli_query( $this->connection, $query ) ) {
            $result = mysqli_query( $this->connection, $query );  
            // Tambahkan ke Activity Log: Customer Viewed
            $this->saveLog($this->connection, $_SESSION['user_id'], 'User Viewed', 'Viewed customer B2C list');
    
            return $result;
        } 
    }
    
    function delete_customer($cust_id) {
        $query = "DELETE FROM `cust_b2c` WHERE `CustomerID`=$cust_id";
        if (mysqli_query($this->connection, $query)) {
            // Tambahkan ke Activity Log: Customer Deleted
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Customer B2C Deleted', "Deleted customer with ID: $cust_id");
            
            $del_msg = 'Customer Deleted Successfully';
            return $del_msg;
        }
    }
    
    function show_custb2c_by_id( $cust_id ) {
        $query = "SELECT * FROM `cust_b2c` WHERE `CustomerID`=$cust_id";
        if ( mysqli_query( $this->connection, $query ) ) {
            $result = mysqli_query( $this->connection, $query );
            // Tambahkan ke Activity Log: Customer Viewed by ID
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Customer Viewed', "Viewed customer details with ID: $cust_id");
    
            return $result;
        }
    }
    
        function update_custb2c($data) {
        $u_id = $data['cust_id'];
        $u_firstname = $data['cust_nama_depan'];
        $u_lastname = $data['cust_nama_belakang'];
        $u_email = $data['email'];
        $u_wa = $data['whatsapp'];
        $u_username = $data['usernameIg'];
    
        // Update query
        $query = "UPDATE `cust_b2c` SET 
            `NamaDepan`='$u_firstname',
            `NamaBelakang`='$u_lastname',
            `Email`='$u_email',
            `Whatsapp`='$u_wa',
            `UsernameIG`='$u_username' 
          WHERE `CustomerID` = $u_id";
    
        if (mysqli_query($this->connection, $query)) {
            // Log activity
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Customer B2C Updated', "Updated customer with ID: $u_id");
            $up_msg = 'Updated successfully';
            return $up_msg;
        } else {
            // Catch error message
            $up_msg = 'Update failed: ' . mysqli_error($this->connection);
            return $up_msg;
        }
    }
    
            // Fungsi untuk import data customer dari file Excel
        function importCustomerData($file) {
            require 'vendor/autoload.php'; // Pastikan sudah install PHPSpreadsheet
        
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rowCount = $sheet->getHighestRow();
        
            // Loop melalui setiap baris dan insert ke database
            for ($row = 2; $row <= $rowCount; $row++) {
                // Abaikan CustomerID (Kolom A)
                $namaDepan = $sheet->getCell('B' . $row)->getValue(); // Kolom B
                $namaBelakang = $sheet->getCell('C' . $row)->getValue(); // Kolom C
                $alamat = $sheet->getCell('D' . $row)->getValue(); // Kolom D
                $email = $sheet->getCell('E' . $row)->getValue(); // Kolom E
                $whatsapp = $sheet->getCell('F' . $row)->getValue(); // Kolom F
                $preferensiMenu = $sheet->getCell('G' . $row)->getValue(); // Kolom G
        
                // Konversi Tanggal dari kolom H
                $tanggalExcel = $sheet->getCell('H' . $row)->getValue();
                if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($sheet->getCell('H' . $row))) {
                    $tanggalPemesanan = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalExcel)->format('Y-m-d');
                } else {
                    $tanggalPemesanan = date('Y-m-d', strtotime($tanggalExcel));
                }
        
                $usernameIG = $sheet->getCell('I' . $row)->getValue(); // Kolom I
        
                // Insert data ke database (tanpa CustomerID karena itu auto-increment)
                $query = "INSERT INTO cust_b2c (NamaDepan, NamaBelakang, Alamat, Email, Whatsapp, PreferensiMenu, TanggalPemesanan, UsernameIG) 
                          VALUES ('$namaDepan', '$namaBelakang', '$alamat', '$email', '$whatsapp', '$preferensiMenu', '$tanggalPemesanan', '$usernameIG')";
        
                if (!mysqli_query($this->connection, $query)) {
                    return "Error importing data: " . mysqli_error($this->connection);
                }
            }
            return "Data imported successfully!";
        }
        
        function show_data_order() {
        $query = "SELECT order_datab2c.*, cust_b2c.NamaDepan FROM order_datab2c JOIN cust_b2c ON order_datab2c.CustomerID = cust_b2c.CustomerID";
        if ( mysqli_query( $this->connection, $query ) ) {
            $result = mysqli_query( $this->connection, $query );  
            // Tambahkan ke Activity Log: Customer Viewed
            $this->saveLog($this->connection, $_SESSION['user_id'], 'User Viewed', 'Viewed customer B2C list');
    
            return $result;
        } 
    }
    
        function delete_orderb2c($order_id) {
        $query = "DELETE FROM `order_datab2c` WHERE `OrderID`=$order_id";
        if (mysqli_query($this->connection, $query)) {
            // Tambahkan ke Activity Log: Customer Deleted
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Order B2C Deleted', "Deleted order with ID: $order_id");
            
            $del_msg = 'Order Deleted Successfully';
            return $del_msg;
        }
    }
    
    function importOrderData($file) {
    require 'vendor/autoload.php'; // Pastikan sudah install PHPSpreadsheet
    
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rowCount = $sheet->getHighestRow();

    // Mulai transaksi
    mysqli_begin_transaction($this->connection);

    try {
        // Loop melalui setiap baris dan insert ke database
        for ($row = 2; $row <= $rowCount; $row++) {
            // Ambil data dari sheet Excel
            $namaDepan = $sheet->getCell('B' . $row)->getValue(); // Kolom NamaDepan
            $namaBelakang = $sheet->getCell('C' . $row)->getValue(); // Kolom NamaBelakang
            $alamat = $sheet->getCell('H' . $row)->getValue(); // Kolom Alamat
            $email = $sheet->getCell('I' . $row)->getValue(); // Kolom Email
            $whatsapp = $sheet->getCell('J' . $row)->getValue(); // Kolom Whatsapp
            $preferensiMenu = $sheet->getCell('K' . $row)->getValue(); // Kolom PreferensiMenu
            $tanggalPemesanan = $sheet->getCell('L' . $row)->getValue(); // Kolom TanggalPemesanan
            $usernameIG = $sheet->getCell('M' . $row)->getValue(); // Kolom UsernameIG
        
            // Insert ke tabel cust_b2c
            $queryCustomer = "INSERT INTO cust_b2c (NamaDepan, NamaBelakang, Alamat, Email, Whatsapp, PreferensiMenu, TanggalPemesanan, UsernameIG)
                              VALUES ('$namaDepan', '$namaBelakang', '$alamat', '$email', '$whatsapp', '$preferensiMenu', '$tanggalPemesanan', '$usernameIG')";
        
            if (!mysqli_query($this->connection, $queryCustomer)) {
                throw new Exception("Error inserting into cust_b2c: " . mysqli_error($this->connection));
            }
        
            // Ambil CustomerID dari insert ke cust_b2c
            $customerID = mysqli_insert_id($this->connection);
        
            // Konversi Tanggal dari kolom D
            $tanggalExcel = $sheet->getCell('L' . $row)->getValue();
            if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($sheet->getCell('L' . $row))) {
                $tanggalPesanan = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalExcel)->format('Y-m-d');
            } else {
                $tanggalPesanan = date('Y-m-d', strtotime($tanggalExcel));
            }
        
            // Ambil data lain dari sheet
            $jenisMenu = $sheet->getCell('E' . $row)->getValue(); // Kolom JenisMenu
            $jumlahPesanan = $sheet->getCell('F' . $row)->getValue(); // Kolom JumlahPesanan
            $totalHarga = $sheet->getCell('G' . $row)->getValue(); // Kolom TotalHarga
        
            // Insert ke tabel order_datab2c (tanpa user_id)
            $queryOrder = "INSERT INTO order_datab2c (CustomerID, TanggalPesanan, JenisMenu, JumlahPesanan, TotalHarga, Alamat) 
                           VALUES ('$customerID', '$tanggalPesanan', '$jenisMenu', '$jumlahPesanan', '$totalHarga', '$alamat')";
        
            if (!mysqli_query($this->connection, $queryOrder)) {
                throw new Exception("Error inserting into order_datab2c: " . mysqli_error($this->connection));
            }
        }
        

        // Jika semua berhasil, commit transaksi
        mysqli_commit($this->connection);
        return "Data imported successfully!";
        
    } catch (Exception $e) {
        // Rollback jika terjadi error
        mysqli_rollback($this->connection);
        return $e->getMessage();
    }
}

function update_orderb2c($data) {
    $u_id = $data['order_id'];
    $u_tgl = $data['tgl_pesanan'];
    $u_jenis = implode(', ', $data['jenis_menu']);  // Gabungkan jenis menu menjadi string
    $u_jumlah = $data['jmlh_pesanan'];
    $u_ttl = $data['ttl_harga'];
    $u_alamat = $data['alamat'];
    
    // Buat array asosiatif untuk menyimpan keterangan hanya jika checkbox terkait dicentang
$keterangan = [];

// Cek apakah Healthy Lite dicentang dan ada keterangan
if (in_array('Healthy Lite', $data['jenis_menu'])) {
    $keterangan['Healthy Lite'] = isset($data['keterangan_healthy_lite']) ? $data['keterangan_healthy_lite'] : '';
}

// Cek apakah Healthy Gourmet dicentang dan ada keterangan
if (in_array('Healthy Gourmet', $data['jenis_menu'])) {
    $keterangan['Healthy Gourmet'] = isset($data['keterangan_healthy_gourmet']) ? $data['keterangan_healthy_gourmet'] : '';
}

// Cek apakah Nusantara Hype dicentang dan ada keterangan
if (in_array('Nusantara Hype', $data['jenis_menu'])) {
    $keterangan['Nusantara Hype'] = isset($data['keterangan_nusantara_hype']) ? $data['keterangan_nusantara_hype'] : '';
}

// Cek apakah Nusantara Fit dicentang dan ada keterangan
if (in_array('Nusantara Fit', $data['jenis_menu'])) {
    $keterangan['Nusantara Fit'] = isset($data['keterangan_nusantara_fit']) ? $data['keterangan_nusantara_fit'] : '';
}

// Encode array keterangan menjadi JSON
$u_keterangan_json = json_encode($keterangan, JSON_UNESCAPED_UNICODE);


    // Update query
    $query = "UPDATE `order_datab2c` SET 
        `TanggalPesanan`='$u_tgl',
        `JenisMenu`='$u_jenis',
        `JumlahPesanan`='$u_jumlah',
        `TotalHarga`='$u_ttl',
        `Alamat`='$u_alamat',
        `Keterangan`='$u_keterangan_json'  -- Update kolom keterangan
      WHERE `OrderID` = $u_id";

    if (mysqli_query($this->connection, $query)) {
        // Log activity
        $this->saveLog($this->connection, $_SESSION['user_id'], 'Order B2C Updated', "Updated order with ID: $u_id");
        $up_msg = 'Updated successfully';
        return $up_msg;
    } else {
        // Catch error message
        $up_msg = 'Update failed: ' . mysqli_error($this->connection);
        return $up_msg;
    }
}

    
        function show_orderb2c_by_id( $order_id ) {
        $query = "SELECT * FROM `order_datab2c` WHERE `OrderID`=$order_id";
        if ( mysqli_query( $this->connection, $query ) ) {
            $result = mysqli_query( $this->connection, $query );
            // Tambahkan ke Activity Log: Customer Viewed by ID
            $this->saveLog($this->connection, $_SESSION['user_id'], 'Order B2C Viewed', "Viewed Order B2C details with ID: $order_id");
    
            return $result;
        }
    }


    }
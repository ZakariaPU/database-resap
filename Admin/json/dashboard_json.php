<?php
// Set Header untuk JSON
header( 'Content-Type: application/json' );

// Set Timezone
date_default_timezone_set( 'Asia/Jakarta' );

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'resapb2b';

// Membuat Koneksi menggunakan mysqli OOP
$connection = new mysqli( $dbhost, $dbuser, $dbpass, $dbname );

// Cek Koneksi
if ( $connection->connect_error ) {
    echo json_encode( [ 'status' => 'error', 'message' => 'Connection failed: ' . $connection->connect_error ] );
    exit();
}

// Fungsi untuk Membersihkan Input

function sanitize_input( $data, $connection ) {
    return htmlspecialchars( $connection->real_escape_string( trim( $data ) ) );
}

// Periksa apakah 'action' di-set
if ( isset( $_POST[ 'action' ] ) ) {
    $action = sanitize_input( $_POST[ 'action' ], $connection );

    switch( $action ) {
        case 'load_allorder':
        if ( isset( $_POST[ 'did' ] ) ) {
            $date = sanitize_input( $_POST[ 'did' ], $connection );

            // Validasi format tanggal
            if ( DateTime::createFromFormat( 'Y-m-d', $date ) !== FALSE || $date == '2020-01-01' ) {
                // Jika 'Life Time' dipilih, gunakan semua tanggal
                if ( $date == '2020-01-01' ) {
                    $stmt = $connection->prepare( 'SELECT COUNT(*) as total FROM `order_detail`' );
                    $stmt->execute();
                    $stmt->bind_result( $total );
                    $stmt->fetch();
                    echo json_encode( [ 'status' => 'success', 'total' => $total ] );
                    $stmt->close();
                } else {
                    $stmt = $connection->prepare( 'SELECT COUNT(*) as total FROM `order_detail` WHERE `order_date` BETWEEN ? AND CURDATE()' );
                    $stmt->bind_param( 's', $date );
                    $stmt->execute();
                    $stmt->bind_result( $total );
                    $stmt->fetch();
                    echo json_encode( [ 'status' => 'success', 'total' => $total ] );
                    $stmt->close();
                }
            } else {
                echo json_encode( [ 'status' => 'error', 'message' => 'Invalid date format.' ] );
            }
        } else {
            echo json_encode( [ 'status' => 'error', 'message' => 'Date parameter missing.' ] );
        }
        break;

        case 'load_allsell':
        if ( isset( $_POST[ 'did' ] ) ) {
            $date = sanitize_input( $_POST[ 'did' ], $connection );

            if ( DateTime::createFromFormat( 'Y-m-d', $date ) !== FALSE || $date == '2020-01-01' ) {
                if ( $date == '2020-01-01' ) {
                    $stmt = $connection->prepare( 'SELECT SUM(`price`) AS `sum` FROM `order_detail`' );
                    $stmt->execute();
                    $stmt->bind_result( $sum );
                    $stmt->fetch();
                    echo json_encode( [ 'status' => 'success', 'sum' => $sum ? $sum : 0 ] );
                    $stmt->close();
                } else {
                    $stmt = $connection->prepare( 'SELECT SUM(`price`) AS `sum` FROM `order_detail` WHERE `order_date` BETWEEN ? AND CURDATE()' );
                    $stmt->bind_param( 's', $date );
                    $stmt->execute();
                    $stmt->bind_result( $sum );
                    $stmt->fetch();
                    echo json_encode( [ 'status' => 'success', 'sum' => $sum ? $sum : 0 ] );
                    $stmt->close();
                }
            } else {
                echo json_encode( [ 'status' => 'error', 'message' => 'Invalid date format.' ] );
            }
        } else {
            echo json_encode( [ 'status' => 'error', 'message' => 'Date parameter missing.' ] );
        }
        break;

        case 'load_allcustomer':
        $stmt = $connection->prepare( 'SELECT COUNT(*) as total FROM `customer_detail`' );
        if ( $stmt->execute() ) {
            $stmt->bind_result( $total );
            $stmt->fetch();
            echo json_encode( [ 'status' => 'success', 'total' => $total ] );
        } else {
            echo json_encode( [ 'status' => 'error', 'message' => 'Query execution failed.' ] );
        }
        $stmt->close();
        break;

        case 'load_closedwon':
        if ( isset( $_POST[ 'did' ] ) ) {
            $date = sanitize_input( $_POST[ 'did' ], $connection );

            // Validasi format tanggal
            if ( DateTime::createFromFormat( 'Y-m-d', $date ) !== FALSE ) {
                $startDate = $date . ' 00:00:00';
                $endDate = date( 'Y-m-d' ) . ' 23:59:59';

                $stmt = $connection->prepare( 'SELECT COUNT(*) as count FROM `lead_progress` WHERE `activity_details` LIKE ? AND `log_time` BETWEEN ? AND ?' );
                $like = '%to Closed-Won%';
                $stmt->bind_param( 'sss', $like, $startDate, $endDate );
                $stmt->execute();
                $stmt->bind_result( $count );
                $stmt->fetch();
                echo json_encode( [ 'status' => 'success', 'count' => $count ] );
                $stmt->close();
            } else {
                echo json_encode( [ 'status' => 'error', 'message' => 'Invalid date format.' ] );
            }
        } else {
            echo json_encode( [ 'status' => 'error', 'message' => 'Date parameter missing.' ] );
        }
        break;

        case 'load_allorderb2c':
        if ( isset( $_POST[ 'did' ] ) ) {
            $date = sanitize_input( $_POST[ 'did' ], $connection );

            if ( DateTime::createFromFormat( 'Y-m-d', $date ) !== FALSE || $date == '2020-01-01' ) {
                if ( $date == '2020-01-01' ) {
                    $stmt = $connection->prepare( 'SELECT COUNT(*) as total FROM `order_datab2c`' );
                    $stmt->execute();
                    $stmt->bind_result( $total );
                    $stmt->fetch();
                    echo json_encode( [ 'status' => 'success', 'total' => $total ] );
                    $stmt->close();
                } else {
                    $stmt = $connection->prepare( 'SELECT COUNT(*) as total FROM `order_datab2c` WHERE `TanggalPemesanan` BETWEEN ? AND CURDATE()' );
                    $stmt->bind_param( 's', $date );
                    $stmt->execute();
                    $stmt->bind_result( $total );
                    $stmt->fetch();
                    echo json_encode( [ 'status' => 'success', 'total' => $total ] );
                    $stmt->close();
                }
            } else {
                echo json_encode( [ 'status' => 'error', 'message' => 'Invalid date format.' ] );
            }
        } else {
            echo json_encode( [ 'status' => 'error', 'message' => 'Date parameter missing.' ] );
        }
        break;

        case 'load_allsellb2c':
        if ( isset( $_POST[ 'did' ] ) ) {
            $date = sanitize_input( $_POST[ 'did' ], $connection );

            if ( DateTime::createFromFormat( 'Y-m-d', $date ) !== FALSE || $date == '2020-01-01' ) {
                if ( $date == '2020-01-01' ) {
                    $stmt = $connection->prepare( 'SELECT SUM(`TotalHarga`) AS `sum` FROM `order_datab2c`' );
                    $stmt->execute();
                    $stmt->bind_result( $sum );
                    $stmt->fetch();
                    echo json_encode( [ 'status' => 'success', 'sum' => $sum ? $sum : 0 ] );
                    $stmt->close();
                } else {
                    $stmt = $connection->prepare( 'SELECT SUM(`TotalHarga`) AS `sum` FROM `order_datab2c` WHERE `TanggalPemesanan` BETWEEN ? AND CURDATE()' );
                    $stmt->bind_param( 's', $date );
                    $stmt->execute();
                    $stmt->bind_result( $sum );
                    $stmt->fetch();
                    echo json_encode( [ 'status' => 'success', 'sum' => $sum ? $sum : 0 ] );
                    $stmt->close();
                }
            } else {
                echo json_encode( [ 'status' => 'error', 'message' => 'Invalid date format.' ] );
            }
        } else {
            echo json_encode( [ 'status' => 'error', 'message' => 'Date parameter missing.' ] );
        }
        break;

        case 'load_allcustomerb2c':
        $stmt = $connection->prepare( 'SELECT COUNT(*) as total FROM `cust_b2c`' );
        if ( $stmt->execute() ) {
            $stmt->bind_result( $total );
            $stmt->fetch();
            echo json_encode( [ 'status' => 'success', 'total' => $total ] );
        } else {
            echo json_encode( [ 'status' => 'error', 'message' => 'Query execution failed.' ] );
        }
        $stmt->close();
        break;

        case 'load_allertStock':
        $stmt = $connection->prepare( 'SELECT * FROM `items` WHERE `current_stock` <= `min_stock`' );
        if ( $stmt->execute() ) {
            $result = $stmt->get_result();
            $stockData = array();
            while( $row = $result->fetch_assoc() ) {
                $stockData[] = $row;
            }
            echo json_encode( [ 'status' => 'success', 'data' => $stockData ] );
        } else {
            echo json_encode( [ 'status' => 'error', 'message' => 'Query execution failed.' ] );
        }
        $stmt->close();
        break;

        default:
        echo json_encode( [ 'status' => 'error', 'message' => 'Invalid action.' ] );
        break;
    }
} else {
    echo json_encode( [ 'status' => 'error', 'message' => 'Action not specified.' ] );
}

// Menutup Koneksi
$connection->close();
?>
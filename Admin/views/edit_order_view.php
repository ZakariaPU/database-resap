<?php 
    if(isset($_GET['status'])){
        $order_id = $_GET['id'];
        if($_GET['status']=="orderEdit"){
           $order_info= $obj->show_order_by_id($order_id);
           $order = mysqli_fetch_assoc($order_info);
        }
    }

    
    if(isset($_POST['update_order'])){
       $update_msg =  $obj->update_order($_POST);
        $order_info = $obj->show_order_by_id( $_POST[ 'order_id' ] );
        $order = mysqli_fetch_assoc( $order_info );
    }
?>

<div class="container">
    <h4>Edit Order Information</h4>

    <h6>
        <?php 
            if(isset( $update_msg)){
                echo  $update_msg;
            }
        ?>
    </h6>
    <form action="" method="POST">
        <div class="form-group">
            <h4>Order Date</h4>
            <input type="date" name="order_date" class="form-control" value="<?php echo $order['order_date'] ?>"
                required>
        </div>
        <div class="form-group">
            <h4>Delivery Date</h4>
            <input type="date" name="delivery_date" class="form-control" value="<?php echo $order['delivery_date'] ?>"
                required>
        </div>
        <div class="form-group">
            <h4>Nama Menu</h4>
            <input type="text" name="nama_menu" class="form-control" value="<?php echo $order['nama_menu'] ?>" required>
        </div>
        <div class="form-group">
            <h4>Quantity</h4>
            <input type="number" name="quantity" class="form-control" value="<?php echo $order['quantity'] ?>" required>
        </div>

        <div class="form-group">
            <h4>Price</h4>
            <input type="number" name="price" class="form-control" value="<?php echo $order['price'] ?>" required>
        </div>
        <div class="form-group">
            <h4>Keterangan</h4>
            <input type="text" name="keterangan" class="form-control" value="<?php echo $order['keterangan'] ?>"
                required>
        </div>


        <!-- <input type="hidden" name="order_id" value="<?php echo $order['order_id'] ?>"> -->
        <input type="hidden" name="order_id" value="<?php echo isset($order['order_id']) ? $order['order_id'] : ''; ?>">


        <div class="form-group">
            <input type="submit" name="update_order" class="btn btn-primary" value="Update Order">
        </div>
    </form>
</div>
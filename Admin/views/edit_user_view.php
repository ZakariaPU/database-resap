<?php 
    if(isset($_GET['status'])){
        $user_id = $_GET['id'];
        if($_GET['status']=="userEdit"){
           $user_detail= $obj->show_user_by_id($user_id);
           $user = mysqli_fetch_assoc($user_detail);
        }
    }
    
    if(isset($_POST['update_user'])){
       $update_msg =  $obj->update_admin($_POST);
       $user_detail = $obj->show_user_by_id( $_POST[ 'user_id' ] );
       $user = mysqli_fetch_assoc( $user_detail );
    }
?>

<div class="container">
    <h4>Edit Admin/Modarator Information</h4>

    <h6>
        <?php 
            if(isset( $update_msg)){
                echo  $update_msg;
            }
        ?>
    </h6>
    <form action="" method="POST">
        <div class="form-group">
            <h4>Username</h4>
            <input type="text" name="u-user-name" class="form-control" value="<?php echo $user['username'] ?>" required>
        </div>

        <!-- <div class="form-group">
        <h4>Password</h4>
        <input type="password" name="user_password" class="form-control" required>
    </div> -->

        <input type="hidden" name="user_id" value="<?php echo $user['user_id'] ?>">

        <div class="form-group">
            <h4>Role</h4>
            <select name="u_user_role" class="form-control">
                <!-- <option disabled selected>--Select--</option> -->

                <option value="1" <?php  if($user['user_role']==1){echo "Selected";  } ?>>Moderator</option>
                <option value="2" <?php  if($user['user_role']==2){echo "Selected";  } ?>>Admin Resap</option>
                <option value="3" <?php  if($user['user_role']==3){echo "Selected";  } ?>>Admin Inventory</option>
            </select>
            <option value="4" <?php  if($user['user_role']==4){echo "Selected";  } ?>>Admin B2C</option>
        </div>

        <div class="form-group">
            <input type="submit" name="update_user" class="btn btn-primary">
        </div>
    </form>
</div>
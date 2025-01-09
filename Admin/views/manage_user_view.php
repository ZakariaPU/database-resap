<?php 
$arry = $obj->show_user();
   
  if(isset($_GET['status'])){
      $user_id = $_GET['id'];
      if($_GET['status']=='delete'){
            $del_msg = $obj->delete_user($user_id);
      }
  }
  
?>


<div class="container">
    <h2>Manage user</h2>

    <h4 class="text-success">
        <?php 
            if(isset($del_msg )){
                echo $del_msg;
            }
        ?>
    </h4>

    <table class="table table-bordered">
        <thead>


            <tr>
                <th>User Id</th>
                <th>Nama User</th>
                <th>Nomor Telepon</th>
                <th>User Name</th>
                <th>User Role</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php 
            while($user = mysqli_fetch_assoc($arry)){
        ?>
            <tr>
                <td> <?php echo $user['user_id'] ?> </td>
                <td> <?php echo $user['full_name'] ?> </td>
                <td> <?php echo $user['phone_number'] ?> </td>
                <td> <?php echo $user['username'] ?> </td>
                <td> <?php if($user['user_role'] == 1){
                            echo "Moderator";
                        } elseif($user['user_role'] == 2){
                            echo "Admin Resap";
                        } elseif($user['user_role'] == 3){
                            echo "Admin Inventory";
                        } else{
                            echo "Admin B2C";
                        }
                 ?> </td>

                <td>
                    <a href="edit_user.php?status=userEdit&&id=<?php echo $user['user_id'] ?>"
                        class="btn btn-sm btn-warning">Edit </a>
                    <a href="?status=delete&&id=<?php echo $user['user_id'] ?>" class="btn btn-sm btn-danger">Delete</a>

                </td>
            </tr>



            <?php }?>
        </tbody>
    </table>
</div>
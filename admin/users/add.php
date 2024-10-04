<?php require_once('../header.php');

$userError  = [];
$userName   = '';
$userEmail  = '';
$userFname  = '';
$userLname  = '';
$userPhone  = '';

if (isset($_POST["submit"])) {

    $userName   = (trim($_POST["user_name"]));
    $userEmail  = (trim($_POST["user_email"]));
    $userFname  = (trim($_POST["user_fname"]));
    $userLname  = (trim($_POST["user_lname"]));
    $userPhone  = (trim($_POST["user_phone"]));
    $userRole   = (trim($_POST["role"]));
    $userStatus = (trim($_POST["status"]));
    $userPass   = md5(trim($_POST["user_password"]));
    $createDate = date('Y-m-d H:i:s');
    
    $sqlEmail = "SELECT email FROM `users` where email = '$userEmail'";
    $results = $db->select($sqlEmail, true);
    if (!empty($results)) {
        $userError[] = "Email already used";
    }

    $sqlName = "SELECT username FROM `users`where username = '$userName'";
    $results = $db->select($sqlName, true);
    if (!empty($results)) {
        $userError[] = "Username already exist";
    }

    $sqlPhone = "SELECT phone FROM `users` where phone = '$userPhone'";
    $results = $db->select($sqlPhone, true);
    if (!empty($results)) {
        $userError[] = "Phone already exist";
    } 
     
    if ( empty($userError) ) {
        $sql = "INSERT INTO `users`(`username`, `email`, `password`,`image`, `f_name`, `l_name`, `phone`, `role`,`status`, `created_date`) 
        VALUES ('$userName','$userEmail','$userPass','$target_file','$userFname','$userLname','$userPhone','$userRole','$userStatus','$createDate')";
        $userID = $db->insert($sql);

        //image upload
        if (isset($_FILES["user_image"]["name"])) {
            $target_file = $userID . '-' . $_FILES["user_image"]["name"];
            $tmp = $_FILES['user_image']['tmp_name'];
            move_uploaded_file($tmp, BASE_PATH . "/uploads/users/$target_file");
            $sql = "UPDATE `users` SET image='$target_file' WHERE id = $userID";
            $db->update($sql);
        }
        redirect(ADMIN_URL . "/users/index.php");
    }
}

//Fetch All Roles
$query = "SELECT `name`, `label` FROM `user_roles`";
$userRoles = $db->select($query);

?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add User</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php 
                if ( !empty($userError) ) {
                    foreach($userError as $error){
                        echo "<div class='alert alert-danger' role='alert'>$error</div>"; 
                    }
                }
            ?>
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">User Info</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group" >
                                    <label>Username</label>
                                    <input type="text" required name="user_name" class="form-control"
                                        value="<?php echo $userName; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" required name="user_email" class="form-control"
                                        value="<?php echo $userEmail; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>First name </label>
                                    <input type="text" required name="user_fname" class="form-control"
                                        value="<?php echo $userFname; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Last name</label>
                                    <input type="text" name="user_lname" class="form-control"
                                        value="<?php echo $userLname; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone no</label>
                                    <input type="text" name="user_phone" class="form-control"
                                        value="<?php echo $userPhone; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="image">User Image</label>

                                    <input type="file" name="user_image" class="form-control" id="" value="" >
                                </div>
                                <div class="form-group">
                                    <label>Role</label>
                                    <select class="form-control select2" required name="role" style="width: 100%;">
                                        <?php
                                        foreach ($userRoles as $role) { ?>
                                            <option value="<?php echo $role['name']; ?>"><?php echo $role['label']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control select2" required name="status" style="width: 100%;">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input type="text" required name="user_password" class="form-control" value=""
                                        required>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!--/.col (left) -->
            <!-- right column -->

            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once('../footer.php'); ?>
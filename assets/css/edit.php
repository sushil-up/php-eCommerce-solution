<?php require_once('../header.php');

$userid = $_GET['id'];
$userError = [];

if (isset($_POST['submit'])) {

  $phone     = $_POST['phone'];
  $email     = $_POST['useremail'];
  $firstname = $_POST['firstname'];
  $lastname  = $_POST['lastname'];
  $password  = md5($_POST['userpassword']);
  $role      = $_POST['role'];
  $status    = $_POST['status'];

  $dynamicColumns = '';
  if (isset($_FILES["user_image"]["name"])) {
    // uplode the image
    $target_file = $userid . '-' . $_FILES["user_image"]["name"];
    $tmp = $_FILES['user_image']['tmp_name'];
    if (!empty($tmp)) {
      move_uploaded_file($tmp, BASE_PATH . "/uploads/users/$target_file");
      $dynamicColumns .= ", image='$target_file'";
    }
  }
   // email valdation 
   $emailQuery = "SELECT id FROM `users` WHERE email='$email'";
   $emailUserObj = $db->select($emailQuery, true);
 
   if (isset($emailUserObj['id']) && $emailUserObj['id'] != $userid) {
     $userError[] = "Email Already exists";
   }
 
   // phone valdation 
   $phoneQuery = "SELECT id FROM `users` WHERE phone='$phone'";
   $phoneUserObj = $db->select($phoneQuery, true);
   if (isset($phoneUserObj['id']) && $phoneUserObj['id'] != $userid) {
     $userError[] = "phone no already exist";
   }

  if (!empty($password)) {
    $dynamicColumns .= ", password='$password'";
  }

  if (empty($userError)) {
    $sql = "UPDATE `users` SET `email`='$email',`f_name`='$firstname',`l_name`='$lastname',`phone`='$phone',`role`='$role',`status`='$status' $dynamicColumns WHERE id = '$userid'";
    $update = $db->update($sql);
     redirect(ADMIN_URL . '/users/index.php');
  }
}

//Fetch User Details
$sql = "SELECT * FROM `users` where id = $userid";
$userObj = $db->select($sql, true);
$image = $userObj['image'];

//Fetch All Roles
$query = "SELECT `name`, `label` FROM `user_roles`";
$userRoles = $db->select($query);

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
          <div class="col-sm-6">
              <h1>Edit User</h1>
          </div>
          <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <a class="btn btn-block btn-primary btn-sm" href="<?php echo ADMIN_URL?>/users/add.php">Add New</a>
          </ol>
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
              <h3 class="card-title">Update User Info</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="" method="POST" enctype="multipart/form-data">
              <div class="card-body">
                <div class="form-group">
                  <label for="firstname">First Name</label>
                  <input type="text" required name="firstname" class="form-control" id="username"
                    value="<?php echo $userObj['f_name']; ?>">
                </div>
                <div class="form-group">
                  <label for="lastname">Last Name</label>
                  <input type="text" name="lastname" class="form-control" value="<?php echo $userObj['l_name']; ?>">
                </div>
                <div class="form-group">
                  <label for="useremail">Email</label>
                  <input type="text" required name="useremail" class="form-control"
                    value="<?php echo $userObj['email']; ?>" required>
                </div>
                <div class="form-group">
                  <label for="phone">Phone</label>
                  <input type="text" name="phone" class="form-control" id="username"
                    value="<?php echo $userObj['phone']; ?>">
                </div>
                <div class="form-group">
                  <?php echo "<img class='products-image'style='width:100px;'src='../../uploads/users/$image'><br><br>" ?>
                  <label for="image">Image</label>
                  <input type="file" name="user_image" class="form-control" value="">
                </div>
                <div class="form-group">
                  <label for="role">Role</label>
                  <select class="form-control select2" required name="role" style="width: 100%;">
                    <?php
                    foreach ($userRoles as $role) { ?>
                      <option value="<?php echo $role['name']; ?>"><?php echo $role['label']; ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="exampleInputPassword1" value="">Status</label>
                  <select class="form-control" aria-label="Default select example" name="status">
                    <option <?php if ($userObj['status'] == 'active')
                      echo "selected"; ?> value="active">Active</option>
                    <option <?php if ($userObj['status'] == 'inactive')
                      echo "selected"; ?> value="inactive">Inactive
                    </option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="text" name="userpassword" class="form-control" value="">
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
        <!--/.col (left) -->s
        <!-- right column -->
        <div class="col-md-6">

        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once('../footer.php'); ?>
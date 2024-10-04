<?php require_once('../header.php');
   $userid = $_GET['id'];
   $userError = [];
   
   if (isset($_POST['submit'])) {
       $Products = $_POST['Products'];
       $Users = $_POST['Users'];
       $Message = $_POST['Message'];
       $Rating = $_POST['Rating'];
       $Status = $_POST['status'];
   
       
   
       if (empty($userError)) {
           // Update review data in the 'reviews' table
           $sql = "UPDATE `reviews` SET `product_id`='$Products', `user_id`='$Users', `comment`='$Message', `Rating`='$Rating', `Status`='$Status' WHERE id = '$userid'";
           $update = $db->update($sql);
   
           if ($update) {
               redirect(ADMIN_URL . '/review/index.php');
           } else {
               $userError[] = "Failed to update review.";
           }
       }
   }
   
   // Fetch review details
   $sql = "SELECT * FROM `reviews` WHERE id = $userid";
   $userObj = $db->select($sql, true);
  
   
   // Fetch product information
   $sql = "SELECT * FROM `products` ";
   $products = $db->select($sql);
   
   // Fetch user information
   $sql = "SELECT * FROM `users` ";
   $userInfo = $db->select($sql);
   
   ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Edit Review</h1>
            </div>
            <div class="col-sm-6">
               <!-- <ol class="breadcrumb float-sm-right">
                  <a class="btn btn-block btn-primary btn-sm" href="<?php echo ADMIN_URL?>/users/add.php">Add New</a>
                  </ol> -->
            </div>
         </div>
      </div>
      <!-- /.container-fluid -->
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
                     <h3 class="card-title">Update Review Info</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form action="" method="POST" enctype="multipart/form-data">
                     <div class="card-body">
                        <div class="form-group">
                           <label for="role">Products</label>
                           <select class="form-control" aria-label="Default select example" name="Products">
                              <?php foreach ($products as $product) { ?>
                              <option value="<?php echo $product['id']; ?>" <?php if ($product['id'] == $userObj['product_id']) echo 'selected'; ?>>
                                 <?php echo $product['name']; ?>
                              </option>
                              <?php } ?>
                           </select>
                        </div>
                        <!-- Users dropdown -->
                        <div class="form-group">
                           <label for="exampleInputPassword1">Users</label>
                           <select class="form-control" aria-label="Default select example" name="Users">
                              <?php foreach ($userInfo as $user) {
                                 $fullName = $user['f_name'] . ' ' . $user['l_name'];
                                 $username = $user['username']; ?>
                              <option value="<?php echo $user['id']; ?>" <?php if ($user['id'] == $userObj['user_id']) echo 'selected'; ?>>
                                 <?php echo $fullName . ' (' . $username . ')';?>
                              </option>
                              <?php } ?>
                           </select>
                        </div>
                        <!-- Message textarea -->
                        <div class="form-group">
                           <label for="useremail">Message</label>
                           <textarea class="form-control textEditor" rows="3" col="5" name="Message"><?php echo $userObj['comment']; ?></textarea>
                        </div>
                        <!-- Status dropdown -->
                        <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status">
                           <option value="pending" <?php if ($userObj['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                           <option value="approved" <?php if ($userObj['status'] == 'approved') echo 'selected'; ?>>Approved</option>
                           <option value="banned" <?php if ($userObj['status'] == 'banned') echo 'selected'; ?>>Banned</option>
                        </select>
                           </div>
                        <div class="form-group">
                           <label for="Rating">Rating</label>
                           <select class="form-control" name="Rating" id="Rating">
                           <?php
                              $selectedRating = $userObj['rating']; // Assuming the user object has a 'rating' property
                              
                              for ($i = 1; $i <= 5; $i++) {
                                  $selected = ($selectedRating == $i) ? 'selected' : '';
                                  echo "<option value=\"$i\" $selected>$i</option>";
                              }
                              ?>
                           </select>
                        </div>
                        <div class="form-group">
                           <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        </div>
                  </form>
                  </div>
                  <!-- /.card -->
               </div>
               <!-- right column -->
               <div class="col-md-6">
               </div>
               <!--/.col (right) -->
            </div>
            <!-- /.row -->
         </div>
         <!-- /.container-fluid -->
   </section>
   <!-- /.content -->
   </div>
</div>
<!-- /.content-wrapper -->
<?php require_once('../footer.php'); ?>

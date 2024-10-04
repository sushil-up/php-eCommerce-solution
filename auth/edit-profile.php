<?php
   require_once("header.php");
   // $pageTitle = "Edit Profile";
   // $pageDescription = "";
   // require_once("sub-header.php");
   
   $userID = isUserLoggedIn();
   if ( !$userID ) redirect(BASE_URL.'/login');
   
   $userError = [];
   $email = '';
   $phone = '';
   
   
   // button isset
   if (isset($_POST['submit'])) {
   
     // $username = $_POST['username'];
     $FirstName = trim($_POST['firstname']);
     $lastname = trim($_POST['lastname']);
     $phone = trim($_POST['phone']);
     $email = trim($_POST['email']);
     $insertDate = date('Y-m-d H:i:s');
     $userPass = trim($_POST["user_password"]);
     $confirmPass = trim($_POST["confirmPass"]);
   
     // password check  
     if ($userPass != $confirmPass) {
       $userError[] = "Password and Confirm password does not match";
     }
   
   
     // upload image
     $dynamicColumns = '';
     if (isset($_FILES["image"]["name"])) {
       $target_file = $userID . '-' . $_FILES["image"]["name"];
       $tmp = $_FILES['image']['tmp_name'];
       if (!empty($tmp)) {
         move_uploaded_file($tmp, "uploads/users/$target_file");
         $dynamicColumns .= ", image='$target_file'";
       }
     }
   
     // email valdation 
     $emailQuery = "SELECT id FROM `users` WHERE email ='$email'";
     $emailUserObj = $db->select($emailQuery, true);
   
     if (isset($emailUserObj['id']) && $emailUserObj['id'] != $userID) {
       $userError[] = "Email Already exists";
     }
   
     // phone valdation 
     $phoneQuery = "SELECT id FROM `users` WHERE phone='$phone'";
     $phoneUserObj = $db->select($phoneQuery, true);
     if (isset($phoneUserObj['id']) && $phoneUserObj['id'] != $userID) {
       $userError[] = "phone no already exist";
     }
   
     if( $userPass != ""){
       $userPass = md5($userPass);
       $dynamicColumns .= ", `password`='$userPass'";
     }
   
     if (empty($userError)) {
       $sql = "UPDATE `users` SET `email`='$email', `f_name`='$FirstName', `l_name`=' $lastname ',`phone`='$phone' $dynamicColumns where id= '$userID'";
       $userObj = $db->update($sql);
     }
     if (empty($userError)) {
       $successMessage = "Profile update successfully";
     }
   
   }
   // featch data
   $userdata = getCurrentUser();
   $username = $userdata['username'];
   $firstname = $userdata['f_name'];
   $lastname = $userdata['l_name'];
   $email = $userdata['email'];
   $phone = $userdata['phone'];
   $imagename = $userdata['image'];
   
   
   $activeMenuItem = 'edit-profile'; // Initialize as empty
   
   $requestUri = $_SERVER['REQUEST_URI'];
   if (strpos($requestUri, '/') !== 0) {
       $requestUri = '/' . $requestUri;
   }
   
   $uriSegments = explode('/', $requestUri);
   
   // Make sure the array has enough elements before accessing specific indices
   if (isset($uriSegments[2])) {
       $activeSegment = $uriSegments[2]; // Extract the third segment
       $validSegments = array('edit-profile', 'wishlist', 'address', 'reviews');
   
       if (in_array($activeSegment, $validSegments)) {
           $activeMenuItem = $activeSegment;
       }
   }
   
   ?>
<section style="background-color: #eee;">
   <div class="container py-5">
      <?php
         if (!empty($userError)) {
           foreach ($userError as $error) {
             echo "<div class='alert alert-danger' role='alert'>$error</div>";
           }
         }  
         ?>
      <?php if (isset($successMessage)) : ?>
      <div class="success-message">
         <div class="alert alert-success" role="alert"><?php echo $successMessage; ?></div>
      </div>
      <?php endif; ?>
      <div class="row">
         <div class="col-lg-3">
            <?php include BASE_PATH . "/partials/user/profile-card.php"; ?>
            <div class="card">
               <div class="card-body p-0">
                  <ul class="list-group list-group-flush rounded-3">
                     <!-- Edit Profile Menu Item -->
                     <li class="list-group-item d-flex justify-content-between align-items-center p-0 <?php if ($activeMenuItem == 'edit-profile') echo 'active'; ?>">
                        <a class="dropdown-item p-3" href="<?php echo BASE_URL; ?>/edit-profile">
                        <i class="fa fa-user"></i> Edit Profile
                        </a>
                     </li>
                     <!-- Wishlist Menu Item -->
                     <li class="list-group-item d-flex justify-content-between align-items-center p-0 <?php if ($activeMenuItem == 'wishlist') echo 'active'; ?>">
                        <a class="dropdown-item p-3" href="<?php echo BASE_URL; ?>/wishlist">
                        <i class="fa fa-heart"></i> Wishlist
                        </a>
                     </li>
                     <!-- Address Menu Item -->
                     <li class="list-group-item d-flex justify-content-between align-items-center p-0 <?php if ($activeMenuItem == 'address') echo 'active'; ?>">
                        <a class="dropdown-item p-3" href="<?php echo BASE_URL; ?>/address">
                        <i class="fa fa-map-marker"></i> Address
                        </a>
                     </li>
                     <!-- Reviews Menu Item -->
                     <li class="list-group-item d-flex justify-content-between align-items-center p-0 <?php if ($activeMenuItem == 'reviews') echo 'active'; ?>">
                        <a class="dropdown-item p-3" href="<?php echo BASE_URL; ?>/reviews">
                        <i class="fa fa-comment"></i> Review
                        </a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
         <!-- edit-form -->
         <div class="col-lg-9">
            <div class="card mb-4">
               <div class="card-body">
                  <form action="" method="POST" enctype="multipart/form-data">
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">First name</p>
                        </div>
                        <div class="col-sm-9">
                           <input type="text" class="text-muted mb-0" name="firstname" value="<?php echo $firstname ?>">
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">Last name</p>
                        </div>
                        <div class="col-sm-9">
                           <input type="text" class="text-muted mb-0" name="lastname" value="<?php echo $lastname ?>">
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">Email</p>
                        </div>
                        <div class="col-sm-9">
                           <input type="email" class="text-muted mb-0" name="email" value="<?php echo $email ?>">
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">Phone</p>
                        </div>
                        <div class="col-sm-9">
                           <input type="text" class="text-muted mb-0" name="phone" value="<?php echo $phone ?>">
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">Upload your image </p>
                        </div>
                        <div class="col-sm-9">
                           <input type="file" class="text-muted mb-0" name="image" value="">
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">Password</p>
                        </div>
                        <div class="col-sm-9">
                           <input type="password" class="text-muted mb-0" name="user_password" >
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">Confirm Password</p>
                        </div>
                        <div class="col-sm-9">
                           <input type="password" class="text-muted mb-0" name="confirmPass">
                        </div>
                     </div>
                     <hr>
                     <button type="submit" name="submit" value="submit" class="btn btn-primary " id="save">Save</button>
               </div>
            </div>
         </div>
         <!-- form ending -->
         </form>
      </div>
   </div>
</section>
<?php
   require_once("footer.php");
   
   ?>

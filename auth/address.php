<?php
   require_once("header.php");
   $userID = isUserLoggedIn();
   if ( !$userID ) redirect(BASE_URL.'/login');
   
   // $pageTitle = "Address";
   // $pageDescription = "";
   // require_once("sub-header.php");
   
   // button isset
   if (isset($_POST['submit'])) {
     $firstName = trim($_POST['firstname']);
     $lastname = trim($_POST['lastname']);
     $phone = trim($_POST['phone']);
     $email = trim($_POST['email']);
     $address1 = trim($_POST['address_1']);
     $address_2 = trim($_POST['address_2']);
     $country = trim($_POST['country']);
     $state = trim($_POST['state']);
     $zip = trim($_POST['zip']);
   
     $sql = "INSERT INTO `address`(`user_id`, `f_name`, `l_name`, `u_email`, `u_phone`, `address_1`, `address_2`, `country`, `state`, `zip`) VALUES ('$userID','$firstName','$lastname','$email','$phone','$address1','$address_2','$country','$state','$zip')";   
     $db->insert($sql);  
   }
   // $addressObj= 'address';
   $addressObj = getUserAddress();
   
   $activeMenuItem = 'address'; // Initialize as empty
   
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
         <div class="col-lg-9"id="contentContainer">
            <div class="card mb-4">
               <div class="card-body">
                  <form action="" method="POST" enctype="multipart/form-data">
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">First name</p>
                        </div>
                        <div class="col-sm-9">
                           <input type="text" class="text-muted mb-0" name="firstname" value="<?php echo isset($addressObj['f_name']) ? $addressObj['f_name'] : ''; ?>">
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">Last name</p>
                        </div>
                        <div class="col-sm-9">
                           <input type="text" class="text-muted mb-0" name="lastname" value="<?php echo isset($addressObj['l_name']) ? $addressObj['l_name'] : ''; ?>">
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">Email</p>
                        </div>
                        <div class="col-sm-9">
                           <input type="email" class="text-muted mb-0" name="email" value="<?php echo isset($addressObj['u_email']) ? $addressObj['u_email'] : ''; ?>">
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">Phone</p>
                        </div>
                        <div class="col-sm-9">
                           <input type="text" class="text-muted mb-0" name="phone" value="<?php echo isset($addressObj['u_phone']) ? $addressObj['u_phone'] : ''; ?>">
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">Address 1</p>
                        </div>
                        <div class="col-sm-9">
                           <input type="text" class="text-muted mb-0" name="address_1" value="<?php echo isset($addressObj['address_1']) ? $addressObj['address_1'] : ''; ?>">
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">Address 2</p>
                        </div>
                        <div class="col-sm-9">
                           <input type="text" class="text-muted mb-0" name="address_2" value="<?php echo isset($addressObj['address_2']) ? $addressObj['address_2'] : ''; ?>">
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">Country</p>
                        </div>
                        <div class="col-sm-9">
                           <input type="text" class="text-muted mb-0" name="country" value="<?php echo isset($addressObj['country']) ? $addressObj['country'] : ''; ?>">
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">State</p>
                        </div>
                        <div class="col-sm-9">
                           <input type="text" class="text-muted mb-0" name="state" value="<?php echo isset($addressObj['state']) ? $addressObj['state'] : ''; ?>">
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">Zip</p>
                        </div>
                        <div class="col-sm-9">
                           <input type="text" class="text-muted mb-0" name="zip" value="<?php echo isset($addressObj['zip']) ? $addressObj['zip'] : ''; ?>">
                        </div>
                     </div>
                     <hr>
                     <button type="submit" name="submit" value="submit" class="btn btn-primary">Save</button>
                  </form>
               </div>
            </div>
         </div>
         <!-- form ending -->
      </div>
   </div>
</section>
<?php
   require_once("footer.php");
   
   ?>

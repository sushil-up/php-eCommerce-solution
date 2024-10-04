<?php
   require_once("header.php");
   // $pageTitle = "Profile";
   // $pageDescription = "";
   // require_once("sub-header.php");
   
   if ( !isUserLoggedIn() ) redirect(BASE_URL.'/login');
   
   $userdata = getCurrentUser();
   $username = $userdata['username'];
   $firstname = $userdata['f_name'];
   $lastname = $userdata['l_name'];
   $email = $userdata['email'];
   $phone = $userdata['phone'];
   $imagename =$userdata['image'];
   
   $activeMenuItem = ''; // Initialize as empty
   
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
         <div class="col-lg-9" id="#currectContent">
            <div class="card mb-4">
                <div class="card-body">
                  <div class="row">
                     <div class="col-sm-3">
                        <p class="mb-0">Username</p>
                     </div>
                     <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $username?>
                        </p>
                     </div>
                  </div>
                  <hr>
                  <div class="row">
                     <div class="col-sm-3">
                        <p class="mb-0">First name</p>
                     </div>
                     <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $firstname ?>
                        </p>
                     </div>
                  </div>
                  <hr>
                  <div class="row">
                     <div class="col-sm-3">
                        <p class="mb-0">Last name</p>
                     </div>
                     <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $lastname ?>
                        </p>
                     </div>
                  </div>
                  <hr>
                  <div class="row">
                     <div class="col-sm-3">
                        <p class="mb-0">Email</p>
                     </div>
                     <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $email?></p>
                     </div>
                  </div>
                  <hr>
                  <div class="row">
                     <div class="col-sm-3">
                        <p class="mb-0">Phone</p>
                     </div>
                     <div class="col-sm-9">
                        <p class="text-muted mb-0"><?php echo $phone?></p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<?php
   require_once("footer.php");
   
   ?>

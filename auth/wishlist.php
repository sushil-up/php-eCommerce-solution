<?php require_once("header.php");
//    $pageTitle = "Wishlist";
//    $pageDescription = "";
//    require_once("sub-header.php");
   $activeMenuItem = 'wishlist'; // Initialize as empty
      
   $requestUri = $_SERVER['REQUEST_URI'];
   if (strpos($requestUri, '/') !== 0) {
       $requestUri = '/' . $requestUri;
   }
   
   $uriSegments = explode('/', $requestUri);
   
   // Make sure the array has enough elements before accessing specific indices
   if (isset($uriSegments[2])) {
       if ($uriSegments[2] == 'edit-profile') {
           $activeMenuItem = 'edit-profile';
       } elseif ($uriSegments[2] == 'wishlist') {
           $activeMenuItem = 'wishlist';
       } elseif ($uriSegments[2] == 'address') {
           $activeMenuItem = 'address';
       } elseif ($uriSegments[2] == 'reviews') {
           $activeMenuItem = 'reviews';
       }
   }
   ?>
<section style="background-color: #eee;">
   <div class="container py-5">
      <div class="row">
         <div class="col-lg-3">
            <?php include BASE_PATH."/partials/user/profile-card.php"; ?>
            <div class="card">
               <div class="card-body p-0">
                  <ul class="list-group list-group-flush rounded-3">
                     <li class="list-group-item d-flex justify-content-between align-items-center p-0 <?php if ($activeMenuItem == 'edit-profile') echo 'active'; ?>">
                        <a class="dropdown-item p-3" href="<?php echo BASE_URL; ?>/edit-profile">
                        <i class="fa fa-user"></i> Edit Profile
                        </a>
                     </li>
                     <li class="list-group-item d-flex justify-content-between align-items-center p-0 <?php if ($activeMenuItem =='wishlist') echo 'active'; ?>">
                        <a class="dropdown-item p-3" href="<?php echo BASE_URL; ?>/wishlist">
                        <i class="fa fa-heart"></i> Wishlist
                        </a>
                     </li>
                     <li class="list-group-item d-flex justify-content-between align-items-center p-0 <?php if ($activeMenuItem == 'address') echo 'active'; ?>">
                        <a class="dropdown-item p-3" href="<?php echo BASE_URL; ?>/address">
                        <i class="fa fa-map-marker"></i> Address
                        </a>
                     </li>
                     <li class="list-group-item d-flex justify-content-between align-items-center p-0 <?php if ($activeMenuItem =='reviews') echo 'active'; ?>">
                        <a class="dropdown-item p-3" href="<?php echo BASE_URL; ?>/reviews">
                        <i class="fa fa-comment"></i> Review
                        </a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
         <div class="col-lg-9" id="addressContent">
            <div class="card mb-4 white">
               <div class="card-body">
                  <div class="container px-4 px-lg-5 mt-5">
                     <div class="row">
                        <?php
                           $userdata = getCurrentUser();
                           $userid = $userdata['id'];
                           
                           $sql = "SELECT * FROM `wishlist` WHERE user_id = '$userid'";
                           $wishlist = $db->select($sql);
                           
                           if (!empty($wishlist)) {
                             foreach ($wishlist as $productid) {
                               $id = $productid['product_id'];
                               $sql = "SELECT * FROM `products` WHERE id = $id";
                               $products = $db->select($sql);
                           
                               foreach ($products as $key => $value) {
                                
                                include('partials/product/wishlist_grid.php');
                               
                               }
                             }
                           } else {
                             echo '<h3 class="text-center">No Products Found <a href="' . BASE_URL . '/shop" style="color: blue;">Continue Shopping</a></h3>';
                           }
                           ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<?php require_once("footer.php")?>
<script>
   // Function to set active menu item in local storage
   function setActiveMenuItem(item) {
       localStorage.setItem('activeMenuItem', item);
   }
   
   // Retrieve and set active menu item from local storage on page load
   var activeMenuItem = localStorage.getItem('activeMenuItem');
   if (activeMenuItem) {
       document.cookie = 'activeMenuItem=' + activeMenuItem;
   }
</script>

<?php
   require_once("header.php");
//    $pageTitle = "Reviews";
//    $pageDescription = "";
//    require_once("sub-header.php");
   
   if (!isUserLoggedIn()) {
       redirect(BASE_URL.'/login');
   }
   
   $userdata = getCurrentUser();
   $userid = $userdata['id'];
   $username = $userdata['username']; // Set the username variable
   
   // Fetch products
   $sql_products = "SELECT * FROM `products`";
   $products = $db->select($sql_products);
   
   // Fetch reviews associated with the user
   $sql_reviews = "SELECT * FROM `reviews` WHERE user_id = $userid";
   $userreviews = $db->select($sql_reviews);
   
   $sql = "SELECT * FROM `users` WHERE id = $userid";
   $alluser = $db->select($sql);
   
   $allUsernames = ''; // Initialize an empty string to store usernames
   
   foreach ($alluser as $userData) {
       $fullName = ucwords($userData['f_name']) . ' ' . ucwords($userData['l_name']);
       $allUsernames .= $fullName . ', '; // Concatenate usernames with a comma and space
   }
   
   $allUsernames = rtrim($allUsernames, ', '); // Remove the trailing comma and space
   
   
   $activeMenuItem = 'reviews'; // Initialize as empty
   
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
         <div class="col-lg-9">
            <div class="card mb-9">
               <div class="card-body">
                  <table class="table table-bordered table-hover">
                     <thead class="thead-dark">
                        <tr>
                           <!-- <th>ID</th> -->
                           <th>Products</th>
                           <!-- <th class="fullrname">Full Name</th> -->
                           <th>Rating</th>
                           <th>Comment</th>
                           <th>Status</th>
                           <th>Created Date</th>
                           <th class="action">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($userreviews as $review) {
                           // Check if the status is 'approved'
                                // Find the product associated with the review
                                $product_id = $review['product_id'];
                                $product_name = "Product not found"; // Default value if product not found
                           
                                foreach ($products as $product) {
                                    if ($product['id'] == $product_id) {
                                        $product_name = '<a class="slug name" href="' . BASE_URL . '/product/' . $product['slug'] . '">' . $product['name'] . '</a>';
                                        break;
                                    }
                                }
                           
                                // Display review information
                                $created_date = $review['created_date'];
                                $formatted_date = date('d M, Y', strtotime($created_date));
                           ?>
                        <tr>
                           <!-- <td><?php echo $review['id']; ?></td> -->
                           <td><?php echo $product_name; ?></td>
                           <!-- <td><?php echo $allUsernames; ?></td> -->
                           <td><?php echo $review['rating']; ?></td>
                           <td><?php echo $review['comment']; ?></td>
                           <td><?php echo $review['status']; ?></td>
                           <td><?php echo $formatted_date; ?></td>
                           <td>
                              <?php $deleteURL = BASE_URL . '/review-delete/' . $review['id']; ?>
                              <a href="<?php echo $deleteURL ; ?>" class="btn-sm btn btn-danger deleteRecord">Delete</a>
                           </td>
                        </tr>
                        <?php
                           }
                           
                           ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<?php
   require_once("footer.php");
   ?>

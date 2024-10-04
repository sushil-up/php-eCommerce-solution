<?php require_once("header.php");
   global $uriSegments;
   $productSlug = trim($uriSegments['2']);
   $sql = "SELECT * FROM `products` WHERE slug='$productSlug'";
   $detail       = $db->select($sql,true);
  
   if($detail)
   {
       $name         = $detail['name'];
       $image        = $detail['image'];
       $rating       = $detail['ratings'];
       $description  = $detail['description'];
       $regularprice = $detail['regular_price'];
       $saleprice    = $detail['sales_price'];
       $sku          = $detail['sku'];
       $productId    = $detail['id'];
   } 
   
   if(isset($_POST['submit'])){
       $inputQuantity = $_POST['inputQuantity'];
       $cart->add($productId,$inputQuantity);
       redirect(BASE_URL.'/product/'.$productSlug);
   }
   
   
   if(isset($_POST['reviewsubmit'])){
       $rating = $_POST['rating']; 
       $comment = $_POST['comment']; 
       $createdDate = date('Y-m-d H:i:s');
       $userID = isUserLoggedIn();
   
       // Insert data into the database
     $sql = "INSERT INTO `reviews`(`product_id`, `user_id`, `comment`, `rating`,`created_date`)
       VALUES ('$productId','$userID','$comment','$rating','$createdDate')";
   
      $review =$db->insert($sql);
   
   }
   
   
   
   
   $sql ="SELECT * FROM `reviews` WHERE product_id = $productId";
   $reviews = $db->select($sql);
   // echo"<pre>";
   // print_r($reviews);
   
   ?>
<!-- Product section-->
<section class="py-5" id="single">
   <div class="container px-4 px-lg-5 my-5">
      <div class="row gx-4 gx-lg-5 align-items-start">
         <div class="col-md-6"><img class="card-img-top img-fluid img-thumbnail" src="<?php echo BASE_URL;?>/uploads/products/<?php echo $image;?>" alt="..." /></div>
         <div class="col-md-6">
            <div class="small mb-1">SKU:<?php echo $sku;?> </div>
            <h1 class="display-5 fw-bolder"><?php echo $name?></h1>
            <div class="fs-5 mb-5">
               <span class="text-decoration-line-through"><?php echo displayPrice($regularprice);?></span>
               <span><?php echo displayPrice($saleprice);?></span>
            </div>
            <p class="lead"><?php echo $description;?></p>
            <div class="d-flex">
               <form action="" method="post">
                  <!-- <button class="btn btn-outline-dark flex-shrink-0 "name="submit"><i class="bi-cart-fill me-1  activeAjax"></i> Add to cart</button> -->
                  <div class="text-center">
                     <input id="inputQuantity" type="number" value="1" name="inputQuantity" class="form control text-center p-2 me-3" style="max-width: 5rem">
                     <a href="<?php echo BASE_URL?>/cart-add/<?php echo $detail['slug'] ?>" class="btn btn-outline-dark mt-auto activeAjax" id="">Add to cart</a>
                     <!-- <button name="submit" data-href="<?php echo BASE_URL?>/cart-add/<?php echo $detail['slug'] ?>" class="btn btn-outline-dark mt-auto p-2" id="">Add to cart</button>  -->
                     <?php 
                        $userdata = getCurrentUser();
                        $wishlist = null;
                        if( !empty($userdata) ){
                            $userid = $userdata['id'];
                            $productid = $detail['id'];
                            $sql = "SELECT * FROM `wishlist` WHERE  user_id='$userid' AND product_id='$productid'";
                            $wishlist = $db->select($sql,true);   
                        }
                        $heartClass = (empty($wishlist)) ? "fa-heart-o" : "fa-heart"; ?>
                     <a href="<?php echo BASE_URL?>/wishlist-add/<?php echo $detail['slug'] ?>" class="activeAjax wishlistItem text-danger"><i class="fa <?php echo $heartClass;  ?>"></i></a> 
                  </div>
               </form>
            </div>
            <div>
               <br>
               <div class="d-flex justify-content-between">
                  <span>Regular Price</span><span><?php echo displayPrice($regularprice);?></span>
               </div>
               <div class="d-flex justify-content-between">
                  <span>Sales Price</span><span><?php echo displayPrice($saleprice);?></span>
               </div>
               <div class="d-flex justify-content-between">
                  <span>Ratings</span>
                  <span>
                     <div class="d-flex justify-content-center small text-warning mb-2">
                        <?php 
                           $productRating = productRatingStars($productId);
                           if (!empty($productRating)) {
                               echo $productRating;
                           } else {
                               echo "No ratings available.";
                           }
                           ?>
                     </div>
                  </span>
               </div>
            </div>
            <div class="d-flex justify-content-between total font-weight-bold mt-4">
            </div>
         </div>
      </div>
   </div>
</section>
<div class="container" id="borders">
   <div class="row" id="avgtop">
      <div class="col-lg-4">
         <div class="rating-block">
            <h4>Average user rating</h4>
            <h2 class="bold padding-bottom-7"><?php echo productAvgRatings( $productId ); ?> <small>/ 5</small></h2>
            <?php echo productRatingStars( $productId ); ?>
         </div>
         <div class="row" id="post-review-box" style="display:none;">
            <div class="col-md-12">
               <div class="formbtn">
                  <form action="" method="post" id="review-form">
                     <input id="ratings-hidden" name="rating" type="hidden">
                     <textarea class="form-control animated" cols="50" id="new-review" name="comment" placeholder="Enter your review here..." rows="5"></textarea>
                     <div class="text-right" id="rating">
                        <div class="rating-stars review" data-rating="0">
                           <i class="bi bi-star" data-rating="1"></i>
                           <i class="bi bi-star" data-rating="2"></i>
                           <i class="bi bi-star" data-rating="3"></i>
                           <i class="bi bi-star" data-rating="4"></i>
                           <i class="bi bi-star" data-rating="5"></i>
                        </div>
                        <div class="reviewbutton">
                           <a class="btn btn-danger btn-sm" href="#" id="close-review-box" style="margin-right: 10px;">
                           <span style="font-family: 'Times New Roman';">Cancel</span>
                           </a>
                           <button class="btn btn-primary btn-sm" name="reviewsubmit" type="submit">
                           <span style="font-family: 'Times New Roman';">Save</span>
                           </button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-4 mt">
         <h4>Rating breakdown</h4>
         <?php
            $ratingsCounts = array(0, 0, 0, 0, 0); // Initialize an array to hold counts for each rating
            
            // Fetch the counts of each rating using SQL for approved reviews
            $sql = "SELECT rating, COUNT(*) AS count FROM reviews WHERE product_id = $productId AND status = 'approved' AND rating BETWEEN 1 AND 5 GROUP BY rating";
            $ratingResults = $db->select($sql);
            
            // Fill the ratingsCounts array based on the fetched counts
            foreach ($ratingResults as $ratingResult) {
            $rating = $ratingResult['rating'];
            $count = $ratingResult['count'];
            $ratingsCounts[$rating - 1] = $count;
            }
            
            // Calculate total approved ratings
            $totalApprovedRatings = array_sum($ratingsCounts);
            
            // Calculate average rating and its percentage for approved ratings
            $averageRating = 0;
            for ($i = 1; $i <= 5; $i++) {
            $averageRating += $i * $ratingsCounts[$i - 1];
            }
            
            // Calculate average rating percentage only if totalApprovedRatings is not zero
            $averageRatingPercentage = $totalApprovedRatings > 0 ? ($averageRating / $totalApprovedRatings) * 20 : 0;
            ?>
         <?php if ($totalApprovedRatings > 0) { // Display the rating section only if there are approved ratings ?>
         <?php for ($i = 5; $i >= 1; $i--) { ?>
         <div class="pull-left">
            <div class="pull-left" style="width: 35px; line-height: 1;">
               <div style="height: 9px; margin: 5px 0;"><?php echo $i; ?> <span class="bi bi-star-fill text-warning"></span></div>
            </div>
            <div class="pull-left" style="width: 180px;">
               <div class="progress" style="height: 9px; margin: 8px 0;">
                  <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $i; ?>" aria-valuemin="0" aria-valuemax="5" style="width: <?php echo ($ratingsCounts[$i - 1] / $totalApprovedRatings) * 100; ?>%;">
                     <span class="sr-only"><?php echo ($ratingsCounts[$i - 1] / $totalApprovedRatings) * 100; ?>% Complete</span>
                  </div>
               </div>
            </div>
            <div class="pull-right" style="margin-left: 10px;"><?php echo $ratingsCounts[$i - 1]; ?></div>
         </div>
         <?php } ?>
         <div class="pull-left" style="clear: both; margin-top: 10px;">
            <div class="progress" style="height: 9px;">
               <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $averageRatingPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $averageRatingPercentage; ?>%;">
                  <span class="sr-only"><?php echo $averageRatingPercentage; ?>% Complete</span>
               </div>
            </div>
         </div>
       
         <?php } else { 
            // Display rating dropdown since there are zero approved ratings ?>
                        <div class="pull-left">
               <div class="pull-left" style="width: 35px; line-height: 1;">
                  <div style="height: 9px; margin: 5px 0;">5 <span class="bi bi-star-fill text-warning"></span></div>
               </div>
               <div class="pull-left" style="width: 180px;">
                  <div class="progress" style="height: 9px; margin: 8px 0;">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="5">
                           <span class="sr-only">80% Complete (danger)</span>
                        </div>
                  </div>
               </div>
               <div class="pull-right" style="margin-left: 10px;">0</div>
            </div>
         <div class="pull-left">
            <div class="pull-left" style="width:35px; line-height:1;">
               <div style="height:9px; margin:5px 0;">4 <span class="bi bi-star-fill text-warning"></div>
            </div>
            <div class="pull-left" style="width:180px;">
               <div class="progress" style="height:9px; margin:8px 0;">
                  <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="4" aria-valuemin="0" aria-valuemax="5" style="">
                     <span class="sr-only">80% Complete (danger)</span>
                  </div>
               </div>
            </div>
            <div class="pull-right" style="margin-left:10px;">0</div>
         </div>
         <div class="pull-left">
            <div class="pull-left" style="width:35px; line-height:1;">
               <div style="height:9px; margin:5px 0;">3 <span class="bi bi-star-fill text-warning"></div>
            </div>
            <div class="pull-left" style="width:180px;">
               <div class="progress" style="height:9px; margin:8px 0;">
                  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="3" aria-valuemin="0" aria-valuemax="5" style="">
                     <span class="sr-only">80% Complete (danger)</span>
                  </div>
               </div>
            </div>
            <div class="pull-right" style="margin-left:10px;">0</div>
         </div>
         <div class="pull-left">
            <div class="pull-left" style="width:35px; line-height:1;">
               <div style="height:9px; margin:5px 0;">2 <span class="bi bi-star-fill text-warning"></div>
            </div>
            <div class="pull-left" style="width:180px;">
               <div class="progress" style="height:9px; margin:8px 0;">
                  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="5" style="">
                     <span class="sr-only">80% Complete (danger)</span>
                  </div>
               </div>
            </div>
            <div class="pull-right" style="margin-left:10px;">0</div>
         </div>
         <div class="pull-left">
            <div class="pull-left" style="width:35px; line-height:1;">
               <div style="height:9px; margin:5px 0;">1 <span class="bi bi-star-fill text-warning"> </span></div>
            </div>
            <div class="pull-left" style="width:180px;">
               <div class="progress" style="height:9px; margin:8px 0;">
                  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="5" style="">
                     <span class="sr-only">80% Complete (danger)</span>
                  </div>
               </div>
            </div>
            <div class="pull-right" style="margin-left:10px;">0</div>
         </div>
      </div>
     
<div class="col-lg-4 bt">
   <div class="well well-sm">
      <div id="reviews-anchor"></div>
      <?php
         $userdata = getCurrentUser();
         if ($userdata !== null && isset($userdata['id'])) {
             $userid = $userdata['id'];
         
             if ($userid != $detail['id']) {
                 echo '<div class="text-center mt-25 hidebtn" style="margin-bottom: 25px;">
                         <a class="btn btn-success btn-green" href="javascript:void(0);" id="open-review-box">Leave a Review</a>
                       </div>';
             }
         }
         ?>
   </div>
</div>



   <?php } ?>
</div>

<div class="col-lg-4 bt">
   <div class="well well-sm">
      <div id="reviews-anchor"></div>
      <?php
         $userdata = getCurrentUser();
         if ($userdata !== null && isset($userdata['id'])) {
             $userid = $userdata['id'];
         
             if ($userid != $detail['id'] && $totalApprovedRatings > 0) {
                 echo '<div class="text-center mt-25" style="margin-bottom: 25px;">
                         <a class="btn btn-success btn-green" href="javascript:void(0);" id="open-review-box">Leave a Review</a>
                       </div>';
             }
         }
         ?>
   </div>
</div>


</div>
<section class="reviewcommt" 
<div class="container">
   <div class="row">
      <div class="col-lg-12">
         <div class="review-block">
            <?php foreach ($reviews as $review) {
               if ($review['status'] == 'approved') { // Check if the review is approved
                   $created_date = $review['created_date'];
                   $formatted_date = date('j M, Y', strtotime($created_date));
                   $days_ago = floor((time() - strtotime($created_date)) / (60 * 60 * 24));
                   $userid = $review['user_id'];
                   $sql = "SELECT * FROM `users` WHERE id = $userid";
                   $user = $db->select($sql);
               
                   if ($user) { // Check if user data is retrieved
                       $username = $user[0]['username'];
                       $imagename = $user[0]['image']; // Use the user's image name
                   }
                   ?>
            <div class="row">
               <div class="col-sm-4 mt">
                  <img src="<?php echo BASE_URL; ?>/uploads/users/<?php echo $imagename; ?>"
                     style="max-width: 75px; "
                     class="img-rounded">
                  <div class="review-block-name black"><?php echo $username; ?></div>
                  <div class="review-block-date"><?php echo $formatted_date; ?><br/><?php echo $days_ago; ?> days ago</div>
               </div>
               <div class="col-md-8">
                  <div class="review-block-rate">
                     <?php echo displayRatingStars( $review['rating'] ); ?>
                  </div>
                  <!-- <div class="review-block-title">this was nice in buy</div> -->
                  <div class="review-block-description"><?php echo $review['comment']; ?></div>
               </div><hr>
            </div>
            <?php
               }
               }
               
               ?>
         </div>
      </div>
   </div>
</div>
</div>
 </div>
<!-- -->

<?php require_once("footer.php")?>

<?php  


    $status = $value['status'];        
    if($status=='active')
    
         $productId  = $value['id'];
        $ProductName  = $value['name'];
        $ProductImage = $value['image'];
        $RegularPrice = $value['regular_price'];
        $SalePrice    = $value['sales_price'];
        $productrating = $value['ratings']; 

        // $sql = "SELECT * FROM `products` WHERE slug='$productSlug'";
        // $detail       = $db->select($sql,true);
        // if($detail)
        // {
        //     $name   = $detail['id'];
        // }

    ?>
    <div class="col-md-3 mb-5">
        <div class="card h-100">
            <!-- Product image-->
            <a href="<?php echo BASE_URL?>/product/<?php echo $value['slug'] ?>"> <img class="card-img-top" id="card-img-top"src="<?php echo BASE_URL;?>/uploads/products/<?php echo $ProductImage;?>" alt="..." /></a>
            <!-- Product details-->
            <div class="card-body p-4">
                <div class="text-center">
                    <!-- Product name-->
                <a id="heading" href="<?php echo BASE_URL?>/product/<?php echo $value['slug'] ?>">
                     <h5 class="fw-bolder"></h5><?php echo $ProductName; ?></h5>
                            </a>

                    
                    <!-- Product price-->
                    <div class="d-flex justify-content-center small text-warning mb-2">
                    
                  
                    <div class ="productstar"><?php echo productRatingStars($productId);?> </div>
                  
                    </div>
                    <p id="rate"><?php echo displayPrice($SalePrice);?> </p>
                </div>
            </div>
            <!-- Product actions-->
            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
            <div class="text-center">
                <a href="<?php echo BASE_URL?>/cart-add/<?php echo $value['slug'] ?>" class="btn btn-outline-dark mt-auto activeAjax" id="">Add to cart</a> 
                <?php 
                    $userdata = getCurrentUser();
                    $wishlist = null;
                    if( !empty($userdata) ){
                        $userid = $userdata['id'];
                        $productid = $value['id'];
                        $sql = "SELECT * FROM `wishlist` WHERE  user_id='$userid' AND product_id='$productid'";
                        $wishlist = $db->select($sql,true);
                    }
                    $heartClass = (empty($wishlist)) ? "fa-heart-o" : "fa-heart"; ?>
                    <a href="<?php echo BASE_URL?>/wishlist-add/<?php echo $value['slug'] ?>" class="activeAjax wishlistItem text-danger"><i class="fa <?php echo $heartClass; ?>"></i></a> 
                </div>
            </div>
        </div>
    </div>
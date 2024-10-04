<?php require_once("header.php");
 $price='';
 $shipping='';
 $result='';
 $total = 0;
 $coupon_code = 0;
 $active_coupon = getActiveCoupons();
 $coupon_id = $active_coupon [0]["id"];
$coupon_code = $active_coupon [0]["code"];
$coupon_discount_type = $active_coupon[0]["discount_type"];
$coupon_discount_value = $active_coupon[0]["discount_value"];
$coupon_expiry_date = $active_coupon[0]["expiry_date"];
$coupon_usage_limit = $active_coupon[0]["usage_limit"];
$coupon_used_count = isset($activeCoupons[0]["used_count"]) ? $activeCoupons[0]["used_count"] : 0;
$coupon_status = $active_coupon[0]["status"];
//  echo "<pre>";
//  print_r($active_coupon);
//  echo "</pre>";
?>

<section class="h-100 gradient-custom py-5">
  <div class="container">
    <div class="row d-flex align-items-start my-4">
    <?php
      // Check if the cart is empty
      $cartdetail = $cart->getCart();
      if (empty($cartdetail)) {
       ?>
       <section class="emptycart">
          <div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="card" id="emptycart">
        <!-- <div class="card-header">
          <h5>Cart</h5>
        </div> -->
        <div class="card-body cart">
          <div class="col-sm-12 empty-cart-cls text-center">
            <img src="https://i.imgur.com/dCdflKN.png" width="130" height="130" class="img-fluid mb-4 mr-3">
            <h3><strong>Your Cart is Empty</strong></h3>
            <h4>Add something to make me happy :-</h4>
            <a href="<?php echo BASE_URL.'/shop'; ?>" class="btn btn-primary cart-btn-transform m-3" data-abc="true">Continue Shopping</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
      </section>
<?php
} else {
?>
      <div class="col-md-8">
        <div class="card mb-4">
          <div class="card-header py-3">
            <h5 class="mb-0">Cart Items </h5>
              <div class="cartbutton" >
                <a class="clearData  btn block btn-danger btn-sm"  id="clearall" href="<?php echo BASE_URL."/cart-clear";?>" >Clear All</a></div>
              </div>
              <div class="card-body">
                <form action="<?php echo BASE_URL; ?>/cart-update" method="post">
                <?php 
                $cartdetail  = $cart->getCart();
                foreach ($cartdetail as $cartid)
                {
                // print_r($cartid);
                  $productid   = $cartid['id'];
                  $quantity    = $cartid['quantity'];
                
                  $sql 	= "SELECT * FROM `products` WHERE id='$productid'";
                  $detail      = $db->select($sql,true);
                  $id          = $detail['id'];
                  $name        = $detail['name'];
                  $image       = $detail['image'];
                  $price       = $detail['sales_price'];
                  $rating      = $detail['ratings'];
                  $slug        = $detail['slug'];
                  $shipping    = 40;
                  $total += $price * $quantity;
                  $result = $total + $shipping ;
                ?>
              <!-- Single item -->
              <div class="row row-cols-3 mb-4">

                <div class="col-lg-1 col-md-1 d-flex" style="padding: 0; width: 15px;">
                  <a href="<?php echo BASE_URL.'/cart-delete/'.$id;?>"   class="activeAjax deleteCart" name="delete"><i class="bi bi-x text-danger" style="font-size:25px;"></i></a>
                </div>          

                <div class="col-lg-3 col-md-3">
                  <!-- Image -->
                  <div class="bg-image hover-overlay hover-zoom ripple rounded" data-mdb-ripple-color="light">
                   
                  <a href="<?php echo BASE_URL.'/product/'.$slug;?>"><img src="<?php echo BASE_URL.'/uploads/products/'.$image?>" class="w-100"></a>
                    <a href="#!">
                      <div class="mask" style="background-color: rgba(251, 251, 251, 0.2)"></div>
                    </a>
                  </div>
                  <!-- Image -->
                </div>
                <div class="col-lg-3 col-md-3">
                  <!-- Data -->
                  <p><strong>   <a href='<?php echo BASE_URL.'/product/'.$slug;?>'style='color:black;'><?php echo $name ?></a></strong></p>
                  <p><?php 
                  for($i=0;$i<=$rating;$i++)
                  {
                    echo "<div id='rating' class='bi-star-fill'></div>";
                  };?></p>
                  <!-- Data -->
                </div>
                <div class="col-lg-1 col-md-1">
                  <div class="d-flex mb-4" style="max-width: 100px">
                    <div class="form-outline">
                        <label class="form-label" for="form1" style="margin-left: 0px;">Quantity</label>
                        
                        <input min="0" name="cart[<?php echo $id; ?>][qty]" value="<?php echo $quantity; ?>" type="number" class="form-control">

                        <input type="hidden" name="cart[<?php echo $id; ?>][p_id]" value="<?php echo $id; ?>">

                    </div>
                  </div>
                </div>
                <div class="col-lg-2 mb-2">
                  <label class="form-label text-end d-block">Price</label>
                  <p class="text-end mb-0">
                    <strong><?php echo displayPrice($price); ?></strong>
                  </p>
                  <!-- Price -->
                </div>
                <div class="col-lg-2 mb-2">
                  <label class="form-label text-end d-block">Total Price</label>
                  <p class="text-end mb-0">
                    <strong><?php echo displayPrice($quantity * $price); ?></strong>
                  </p>
                  <!-- Price -->
                </div>
              </div>
                 
                   
              <!-- Single item -->
              <?php }?>
              <div class="buttons"><button name="update"class="btn block btn-primary btn-sm">Update</button></div>
              <!-- <div class="buttons"><button name="update"class="btn block btn-primary btn-sm">Update</button></div> -->
              </form>
            </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-header py-3">
            <h5 class="mb-0">Summary</h5>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                Products
                <span><?php echo  displayPrice($total);
                ?></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                Shipping
                <span><?php echo displayPrice($shipping);?> </span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                <div>
                  <strong>Total amount</strong>
                  <strong>
                    <p class="mb-0">(including VAT)</p>
                  </strong>
                </div>
                <span><strong><?php echo displayPrice($result); ?></strong></span>
              </li>
            </ul>
          </div>
          <?php
// Check if user is logged in
$userID = isUserLoggedIn();
?>

<div class="container">
    <?php if ($userID): ?>
        <!-- User is logged in, show the Continue to Checkout button -->
        <a href="https://shop.web-xperts.xyz/checkout" class="btn btn-primary d-block mt-2">Continue to Checkout</a>
    <?php else: ?>
        <!-- User is not logged in -->
        <div class="alert alert-info mb-4" role="alert">
            <h4 class="alert-heading">Notice</h4>
            <p class="mb-0">Please <a href="/login">sign up</a> or <a href="/login">log in</a> to proceed with the checkout.</p>
        </div>
        <a href="/login" class="btn btn-primary d-block mt-2">Sign Up</a>
    <?php endif; ?>
</div>
        </div>
       
      </div>
    </div>
    <div class="row">
      <div class="col-md-8 mb-4">
          <div class="card">
          <div class="card-body">
            <p><strong>Expected shipping delivery</strong></p>
            <p class="mb-0">12.10.2020 - 14.10.2020</p>
          </div>
          </div>
        </div>
        
        <div class="col-md-8 mb-4 mb-lg-0">
          <div class="card">
          <div class="card-body">
            <p><strong>We accept</strong></p>
            <img class="me-2" width="45px"
              src="https://mdbcdn.b-cdn.net/wp-content/plugins/woocommerce-gateway-stripe/assets/images/visa.svg"
              alt="Visa" />
            <img class="me-2" width="45px"
              src="https://mdbcdn.b-cdn.net/wp-content/plugins/woocommerce-gateway-stripe/assets/images/amex.svg"
              alt="American Express" />
            <img class="me-2" width="45px"
              src="https://mdbcdn.b-cdn.net/wp-content/plugins/woocommerce-gateway-stripe/assets/images/mastercard.svg"
              alt="Mastercard" />
          </div>
          </div>
        </div>
        <?php
        }
        ?>
      </div>
  </div>
</section>
<style>
  .alert.alert-info.mb-4 a {
    color: blue;
}
  </style>
<?php require_once("footer.php") ?>
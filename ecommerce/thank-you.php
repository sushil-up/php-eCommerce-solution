<?php
require_once("header.php");
$pageTitle = "Thank you";
$pageDescription = "";
require_once("sub-header.php");
require_once("plugins/vendor/autoload.php");

if ( !isUserLoggedIn() ) redirect(BASE_URL.'/login');
$userID = isUserLoggedIn();

$userdata = getCurrentUser();
$username = $userdata['username'];
$firstname = $userdata['f_name'];
$lastname = $userdata['l_name'];
$email = $userdata['email'];
$phone = $userdata['phone'];
$imagename =$userdata['image'];

$sql = "SELECT * FROM `orders` ORDER BY `id` DESC LIMIT 1";
$orderObj = $db->select($sql, true);  
$paymentmethod = $orderObj['payment_method'];
$paymentstatus = $orderObj['payment_status'];
$producectname = $orderObj['items'];
$product_price = $orderObj['total'];
$address =$orderObj['address'];
$alladdress = json_decode($address, true);
// echo "<pre>";
// print_r($alladdress);
// die;
$address_1 = $alladdress['address_1'];
$address_2 = $alladdress['address_2'];
$country = $alladdress['country'];
$state = $alladdress['state'];
$zip = $alladdress['zip'];

$itemsdetails =$orderObj['items'];
$itemsdetail = json_decode($itemsdetails, true);

$itemid = reset($itemsdetail)['id'];



$sql = "SELECT * FROM `products` WHERE id = $itemid";
$productdata = $db->select($sql, true);
$item_name = $productdata ['name'];
// echo "<pre>";
// print_r($productdata);
// die;


$sql = "SELECT * FROM `products` WHERE id = $itemid";
$productdata = $db->select($sql, true);
$product_slug = $productdata['slug'];



$productArray = json_decode($producectname, true);
$totalQuantity = 0;
foreach ($productArray as $productID => $product) {
    $quantity = $product['quantity']; 
    $totalQuantity += $quantity;     
}

$total = $orderObj['total'];
$orderid = $orderObj['id'];
$cartTotal = 0;
$total = 0;
$shipping = '';
$productQuantity ='';
// $address ='';
// $address_2 ='';
// $country ='';
// $state = '';
// $zip ='';

$cartdetail = $cart->getCart();

foreach ($cartdetail as $cartid) {
    $productid = $cartid['id'];
    $productQuantity = $cartid['quantity'];
    $productprice = $cartid['price'];
    $shipping = 40;
    $promocode = 5;
    $total += $productprice * $productQuantity;
    $cartTotal = $total + $shipping - $promocode;
}  


?>

<section class="bg-light py-5">
  <div class="container">
    <div class="row">
    </div>
    <div class="row">
      <!-- Order Summary -->
      <div class="col-lg-8 mx-auto">
        <div class="card border-light shadow-sm">
          <div class="card-body">
            <h5 class="card-title mb-4 text-center">Order Summary</h5>
            <p class="mb-0 oredr_pl">Your order has been placed successfully. We will send you an email confirmation shortly.</p>
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <th scope="row">Order ID:</th>
                  <td><?php echo htmlspecialchars($orderid, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                  <th scope="row">User Name:</th>
                  <td><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                  <th scope="row">First Name:</th>
                  <td><?php echo htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                  <th scope="row">Last Name:</th>
                  <td><?php echo htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                  <th scope="row">Email:</th>
                  <td><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                  <th scope="row">Phone No.:</th>
                  <td><?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                  <th scope="row">Shipping Address:</th>
                  <td>
                    
                     <?php echo htmlspecialchars($address_1, ENT_QUOTES, 'UTF-8'); ?><br>
                      <?php echo htmlspecialchars($address_2, ENT_QUOTES, 'UTF-8'); ?><br>
                      <?php echo htmlspecialchars($country, ENT_QUOTES, 'UTF-8'); ?><br>
                      <?php echo htmlspecialchars($state, ENT_QUOTES, 'UTF-8'); ?><br>
                      <?php echo htmlspecialchars($zip, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <tr>
                  <th scope="row">Payment Method:</th>
                  <td><?php echo htmlspecialchars($paymentmethod, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <?php if (strcasecmp($paymentmethod, 'COD') !== 0): ?>
                <tr>
                  <th scope="row">Payment Status:</th>
                  <td><?php echo htmlspecialchars($paymentstatus, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                  <th scope="row">Order Total:</th>
                  <td><?php echo displayPrice($product_price); ?></td>
                </tr>
              </tbody>
            </table>
            <hr>
            <h5 class="mb-4 text-center">Order Items</h5>
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="https://shop.web-xperts.xyz/product/<?php echo htmlspecialchars($product_slug, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($item_name, ENT_QUOTES, 'UTF-8'); ?>
                 </a>
                  <span><?php echo htmlspecialchars($totalQuantity, ENT_QUOTES, 'UTF-8'); ?> </span>
                </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<style>
  p.mb-0.oredr_pl {
    text-align: center;
    padding: 15px;
}
li.list-group-item.d-flex.justify-content-between.align-items-center a {
    color: #000;
}
</style>
<?php
require_once("footer.php");
?>

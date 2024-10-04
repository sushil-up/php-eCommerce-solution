<?php require_once('../header.php');
 ?>
<?php

$orderid = $_GET['id'];
$user = getCurrentUser();
$current_user = $user['id'];
$sql = "SELECT * FROM `orders` WHERE id = $orderid";
$orderObj = $db->select($sql, true);


$paymentid = $orderObj['payment_id'];

$address = json_decode($orderObj['address'], true);
$numsql = "SELECT  `u_phone` FROM `address` WHERE id = $current_user";
$phone == $db->select($numsql, true);
// echo"<pre>";
// print_r($phone);
$date = new DateTime($orderObj['created_date']);
$newDateFormat = $date->format('Y-m-d');



if (isset($_POST['add_note'])) {
    $note = $_POST['order_note'];
    $noteType = $_POST['note_type'];
    $orderid = $_GET['id'];
    $userid = $user['id'];
    $sql = "INSERT INTO `order_notes` (user_id,order_id, note, note_type) VALUES ('$userid','$orderid','$note', '$noteType')";
    $insertNote = $db->insert($sql);

}

$sql = "SELECT * FROM `order_notes` WHERE order_id = $orderid";
$notes = $db->select($sql);



require_once('../../plugins/vendor/autoload.php');

\Stripe\Stripe::setApiKey('sk_test_51NWIleH6iAIa5QEFww2DQhzhCA5KDzA4WbevFtLxZl30cS5Wev4zVq8GbDkppF0zpVnhAzmqtdxZ3fZqbznpVdNx00RyXzhFZF');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['refund_button'])) {
    $orderid = $_GET['id']; // Define $orderid here
    $paymentid = $orderObj['payment_id'];
    $orderid  = $orderObj['id'];
    $id = trim($paymentid);
    $created_date = date('Y-m-d H:i:s');

    try {
        $refund = \Stripe\Refund::create([
            'charge' => $id,
        ]);

        $refund_id = $refund->id;
        $amunt = $orderObj['total'];
        $amount = trim($amunt);

        

        $sql = "INSERT INTO `refunds`(`refund_id`, `amount`, `payment_id`, `current_user_id`, `created_date`) 
                VALUES ('$refund_id','$amount','$payment_id','$current_user','$created_date')";
        $db->insert($sql);

        $update = "UPDATE `orders` SET `status`='refund' WHERE  id  = $orderid";
        $status_update = $db->update($update);

        echo "Refund successful! Refund ID: " . $refund->id;
    } catch (\Stripe\Exception\ApiErrorException $e) {
      
        echo "Refund failed: " . $e->getMessage();
    }
}

$sql = "SELECT * FROM `cards` where order_id  = $orderid";
$card_details = $db->select($sql);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order Preview
                        <?php echo '#' . $orderObj['id'] ?>
                    </h1>
                </div>
            </div>
            <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-9">
                    <div class="card">
                        <div class="card-header py-3">
                            <h5 class="mb-0">Order Detail </h5>
                            <h6 class="mb-0">
                                <?php echo 'Payment by: ' . $orderObj['payment_method'] ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <h4>General</h4>
                                    <div class="form-group">
                                        <label for="created_date">Creation Date</label>
                                        <input type="date" class="form-control" name="created_date" id="created_date"
                                            value="<?php echo $newDateFormat ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="order_status">Status</label>
                                        <input type="text" class="form-control" name="order_status" id="order_status"
                                            value="<?php echo $orderObj['status']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="f_name">Customer</label>
                                        <input type="text" class="form-control" name="f_name" id="f_name"
                                            value="<?php echo $address['f_name']; ?>">
                                    </div>
                                </div>
                                <div class="col-4 offset-1">
                                    <h4>Billing</h4>
                                    <div class="form-group">
                                        <?php echo implode(",</br>", [$address['f_name'], $address['l_name'], $address['address_1'], $address['address_2']]); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Email: </label>
                                        <?php echo $address['u_email']; ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone: </label>
                                        <?php echo $phone; ?>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <h4>Shipping</h4>
                                    <?php echo implode(",</br>", [$address['f_name'], $address['l_name'], $address['address_1'], $address['address_2']]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $subtotal = 0;
                                    $delivery = 0;
                                    $shipping = 40;
                                    $promocode = 5;

                                    $items = json_decode($orderObj['items']);
                                    foreach ($items as $object) {
                                        $subtotal += $object->price * $object->quantity; ?>
                                        <tr>
                                            <td><a
                                                    href="https://shop.web-xperts.xyz/admin/product/edit.php?id=<?php echo $object->id; ?>">#<?php $data = getProductByID($object->id);
                                                       echo $data['id']; ?>     <?php echo $data['name']; ?> </a></td>
                                            <td>
                                                <?php echo displayPrice($object->price); ?>
                                            </td>
                                            <td>
                                                <?php echo $object->quantity; ?>
                                            </td>
                                            <td>
                                                <?php echo displayPrice($object->price * $object->quantity); ?>
                                            </td>
                                        </tr>
                                    <?php }
                                    $total = decimalValues($subtotal + $delivery + $shipping - $promocode); ?>
                                    <tr>
                                        <td colspan='4'>
                                            <p></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='3' class="text-right">Sub Total:</td>
                                        <td>
                                            <?php echo displayPrice($subtotal); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='3' class="text-right">Promocode:</td>
                                        <td>
                                            <?php echo displayPrice($promocode); ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan='3' class="text-right">Shipping Cost:</td>
                                        <td>
                                            <?php echo displayPrice($shipping); ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan='3' class="text-right">Total:</td>
                                        <td>
                                            <?php echo displayPrice($total); ?>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Card Details</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Card Name</th>
                                            <th scope="col">Card Number</th>
                                            <th scope="col">Card Date</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($card_details as $card) { ?>
                                            <tr>
                                                <td>
                                                    <?php echo $card['card_name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $card['card_number']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $card['card_date']; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div>
                    <form method="POST" action="" id="refundForm">
    <?php 
    if ($orderObj['status'] != 'refund' && $orderObj['payment_method'] != 'cod') { 
        echo '<a class="btn btn-success d-block my-3"name="refund_button" data-url="' . ADMIN_URL . '/order/refund.php?id=' . $orderid . '" id="refund_button" href="">Refund Payment</a>';
        
        // echo '<button type="submit" class="btn btn-success d-block my-3" name="refund_button" data-url="' . $userid . '" id="refund_button">Refund Payment</button>';
    }
    ?>
</form>
                </div>
      
                
                <div class="col-3">
                    <div class="card">
                        <div class="card-header py-3">
                            <h5 class="mb-0">Order Notes</h5>
                        </div>
                        <div class="card-body">

                            <div class="comment-timeline mb-4">
                                <?php
                                foreach ($notes as $note) {
                                 

                                    $date = new DateTime($note['created_date']);
                                    $dates = $date->format('d');
                                    $year = $date->format('Y');
                                    $monthNum = $date->format('m');
                                    $dateObj = DateTime::createFromFormat('!m', $monthNum);
                                    $monthName = $dateObj->format('F'); ?>
                                    <div class="comment-row">
                                        <div class="comment-body">
                                            <?php echo $note['note']; ?>
                                        </div>
                                        <div class="comment-meta">
                                            <?php echo $notes['note'];?>
                                            </span> |
                                            <a href="<?php echo ADMIN_URL ?>/order/note/delete.php?id=<?php echo $note['id']; ?>&o_id=<?php echo $orderid; ?>"
                                                class="text-danger deleteRecord">Delete</a>

                                        </div>
                                    </div>

                                <?php } ?>
                            </div>
                            <hr>
                            <form action="edit.php?id=<?php echo $orderid; ?>" method="post">
                                <div class="form-group">
                                    <textarea rows="4" class="form-control" required name="order_note"></textarea>
                                </div>
                                <div class="form-group d-flex">
                                    <select class="form-control mr-3" required name="note_type">
                                        <option value="">Note Type</option>
                                        <option value="public">Public</option>
                                        <option value="private">Private</option>
                                    </select>
                                    <input type="submit" class="btn btn-outline-primary btn-sm" name="add_note"
                                        value="Add Note" />
                                </div>
                            </form>
                        </div>
                    </div>
                    <button class="btn btn-success d-block my-3" id="cc-name" name="submit" type="submit"> Update
                        Order</button>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.0.0/dist/sweetalert2.min.js"></script>
<script>
    // JavaScript code to trigger the SweetAlert confirmation
    var refundButton = document.getElementById('refund_button');
    if (refundButton) {
      refundButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior

        // Show the SweetAlert confirmation dialog
        Swal.fire({
          title: 'Are you sure?',
          text: 'refund the amount',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, refund it!'
        }).then((result) => {
          if (result.isConfirmed) {
            // If the user confirms, proceed with the link redirection
            window.location.href = event.target.getAttribute('data-url');
          }
        });
      });
    }
  </script>

<?php require_once('../footer.php'); ?>
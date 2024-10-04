<?php
require_once('../header.php');
require_once('../../plugins/vendor/autoload.php');

use Stripe\Stripe;
use Stripe\Refund;

Stripe::setApiKey('sk_test_51NWIleH6iAIa5QEFww2DQhzhCA5KDzA4WbevFtLxZl30cS5Wev4zVq8GbDkppF0zpVnhAzmqtdxZ3fZqbznpVdNx00RyXzhFZF');

$orderid = $_GET['id'];
$user = getCurrentUser();
$current_user_id = $user['id'];

// Assuming $db is your database connection object
$sql = "SELECT * FROM `orders` WHERE id = $orderid";
$orderObj = $db->select($sql, true);

$paymentid = $orderObj['payment_id'];
$id = trim($paymentid);
$created_date = date('Y-m-d H:i:s');

try {
    $refund = Refund::create([
        'charge' => $id,
    ]);

    $refund_id = $refund->id;
    $amount = $orderObj['total'];

    $sql = "INSERT INTO `refunds`(`refund_id`, `amount`, `payment_id`, `current_user_id`, `created_date`) 
                    VALUES ('$refund_id','$amount','$id','$current_user_id','$created_date')";
    $db->insert($sql);

    $update = "UPDATE `orders` SET `status`='refund' WHERE id = $orderid";
    $status_update = $db->update($update);

   
    $success_message = "Refund successful! Refund ID: " . $refund->id;
    $noteType = 'success'; 
    $sql = "INSERT INTO `order_notes` (user_id, order_id, note, note_type) 
            VALUES ('$current_user_id', '$orderid', '$success_message', '$noteType')";
    $statusNote = $db->insert($sql);

    
    redirect(ADMIN_URL . '/order/index.php');

} catch (\Stripe\Exception\InvalidRequestException $e) {
    
    $error_message = $e->getMessage();
    
    echo "Refund failed: " . $error_message;

    
    $error_note = "Refund failed: " . $error_message;
    $noteType = 'error'; 
    $sql = "INSERT INTO `order_notes` (user_id, order_id, note, note_type) 
            VALUES ('$current_user_id', '$orderid', '$error_note', '$noteType')";
    $statusNote = $db->insert($sql);
   
}

?>

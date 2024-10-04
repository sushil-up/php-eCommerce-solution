<?php 
    require('../../header.php');
    $noteId = $_GET['id'];
    $orderId = $_GET['o_id'];
    $delete="DELETE FROM `order_notes` WHERE `order_notes`.`id` = $noteId";
    $db->delete($delete);
    redirect(ADMIN_URL .'/order/edit.php?id='.$orderId);
?>

<?php require_once('../header.php')?>
<?php
        $userid=$_GET['id'];
        // delete order
        $delete="DELETE FROM `orders` WHERE `orders`.`id` = $userid";
        $deleteorder = $db->delete($delete);
        redirect(ADMIN_URL .'/order/index.php');
?>
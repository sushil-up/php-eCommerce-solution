<?php require_once('../header.php')?>
<?php
        $userid=$_GET['id'];
        $deleteco="DELETE FROM `coupons` WHERE `coupons`.`id` = $userid";
        $deletecoupon = $db->delete($deleteco, true);
        redirect(ADMIN_URL .'/coupon/index.php');
?>
<?php require_once('../footer.php')?>
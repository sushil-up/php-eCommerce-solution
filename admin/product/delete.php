<?php require_once('../header.php')?>
<?php
        $userid=$_GET['id'];
        // delete product
        $delete="DELETE FROM `products` WHERE `products`.`id` = $userid";
        $deleteproduct = $db->delete($delete);
        redirect(ADMIN_URL .'/product/index.php');
?>




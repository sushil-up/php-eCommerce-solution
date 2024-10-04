<?php require_once('../header.php')?>
<?php
$userid     = $_GET['id'];
 $sql       = "DELETE FROM `users` WHERE id=$userid";
$userObj    = $db->delete($sql);
redirect(ADMIN_URL .'/users/index.php');
?>

 
  






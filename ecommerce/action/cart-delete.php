<?php
 require_once(BASE_PATH . '/autoload.php');
global $uriSegments;
$deleteId = trim($uriSegments['2']);
$cart->remove($deleteId);
echo sendSuccessResponse('Delete From cart');
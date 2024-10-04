<?php
require_once(BASE_PATH . '/autoload.php');
$cart->destroy();
echo sendSuccessResponse('Clear all  cart');
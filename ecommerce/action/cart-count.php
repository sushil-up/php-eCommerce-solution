<?php 
    require_once(BASE_PATH . '/autoload.php');
    $cartItems = $cart->getCartQuantity();
    echo sendSuccessResponse($cartItems);

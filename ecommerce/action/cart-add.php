<?php 
    require_once(BASE_PATH . '/autoload.php');
    global $uriSegments;
    $productSlug = trim($uriSegments['2']);
    $productDetail = getProductBySlug($productSlug);
    if($productDetail)
    {
        $productId = $productDetail['id'];
        $cart->add($productId ,1);
        echo sendSuccessResponse('Add to cart Success');
    }else{
        echo sendErrorResponse('Add to cart Fail');
    }

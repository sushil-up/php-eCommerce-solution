<?php 
	require_once(BASE_PATH . '/autoload.php');
	global $uriSegments;
	
	$productSlug = $uriSegments['2'];
    $productDetail = getProductBySlug($productSlug);
    $productID = $productDetail['id'];

	$insertDate = date('Y-m-d H:i:s');

	$userdata = getCurrentUser();
	
	if( empty($userdata) ){
		echo sendErrorResponse('Please login!');
		exit;
	}

	$userid = $userdata['id'];

	if(empty($userid)){
		echo sendErrorResponse('Please Login');
		exit;
	}

	if (empty($productID)){
		echo sendErrorResponse('Product does not exists');
		exit;
	}


	$sql	= "SELECT * FROM `wishlist` WHERE user_id=$userid AND product_id=$productID";
	$wishlist = $db->select($sql,true);

	if( $wishlist ){
		$deleteid = $wishlist['id'];
       	$sql = "DELETE FROM `wishlist` WHERE `wishlist`.`id` = $deleteid";
	   	$delete =$db->delete($sql);
		echo sendSuccessResponse('Remove from wishlist');
	}else{
		$wislistinsert="INSERT INTO `wishlist`(`user_id`, `product_id`, `created_date`) VALUES (' $userid','$productID','$insertDate')";
		$insert =$db->insert($wislistinsert);
		echo sendSuccessResponse('Added to wishlist');
	}
	exit;
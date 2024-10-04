<?php 
	require_once(BASE_PATH . '/autoload.php');
	global $uriSegments;
	
	$userdata = getCurrentUser();
	
	if( empty($userdata) ){
		echo sendErrorResponse('Please login!');
		exit;
	}

    $reviewID  = trim($uriSegments['2']);
    $reviewSql = "DELETE FROM `reviews` WHERE id = $reviewID";
    $db->delete($reviewSql);
	echo sendSuccessResponse('Review Removed');
	exit;
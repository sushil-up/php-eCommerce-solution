<?php

function pr($arr, $die = false)
{
    echo "<pre>";
        print_r($arr);
    echo "</pre>";
    if( $die ) die;
}


function redirect($url = '')
{
    $redirectURL = ($url !='') ? $url : BASE_URL;
    ?><script>window.location = "<?php echo $redirectURL;?>";</script><?php
}

function slugify($text)
{
    if (empty($text)) return false;
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return $text;
}

function createUniqueSlug($slug, $slugColumn, $table)
{
    global $db;
    $sql ="SELECT * FROM $table WHERE $slugColumn = '$slug'";
    $productInfo = $db->select($sql,true);
   
    if(empty($productInfo) ) return $slug;
   
    $slugArr = explode('-',$slug); // reece-pearson > ['reece','pearson']
    $lastNode = end($slugArr); // 'pearson'
    
    if( is_numeric($lastNode) ) :
        $slugArr[ count($slugArr)-1 ] = ++$lastNode;
    else:
        $slugArr[] = 1; // reece-pearson > ['reece','pearson', 1]
    endif;

    $slug = implode('-', $slugArr); // reece-pearson-1
    return createUniqueSlug($slug, $slugColumn, $table);
}

function getProductBySlug($productSlug)
{
    global $db;
    $sql = "SELECT * FROM `products` WHERE slug='$productSlug'";
    return $db->select($sql,true);
}

function isUserLoggedIn()
{
    if ( 
        isset($_SESSION["loggedin_user"]) && 
        !empty($_SESSION["loggedin_user"]) && 
        $_SESSION["loggedin_user"]['id'] != ''
    ) {
        return $_SESSION["loggedin_user"]['id'];
    }
    return false;
}

function getCurrentUser($userID = null)
{
    global $db;
    if( $userID == null ){
        $userID = isset( $_SESSION["loggedin_user"]['id'] ) ? $_SESSION["loggedin_user"]['id'] : null;
    }

    if ($userID == null) return false;

    $sql = "SELECT * FROM `users` WHERE id =$userID";
    $userObj = $db->select($sql, true);
    return $userObj;
}

function getAllUserStatus()
{
    global $db;
    $query = "SELECT `name`, `label` FROM `user_roles`";
    return $db->select($query);
}

function getUserAddress($userID = null)
{
    global $db;
    if( $userID == null ) $userID = isUserLoggedIn();
    $addressObj = ['f_name' => '', 'l_name' => '', 'u_email' => '', 'u_phone' => '', 'address_1' => '', 'address_2' => '', 'country' => '', 'state' => '', 'zip' => ''];
    if( !empty($userID) ){
        $addressQuery = "SELECT * FROM `address` WHERE `user_id`=$userID";
        $addressObj = $db->select($addressQuery,true);
    }
    return $addressObj;
}

function sendSuccessResponse($msg='Success')
{
    $arr['status'] = 'success';
    $arr['message'] = $msg;
    return json_encode($arr);
}

function sendErrorResponse($msg='Fail')
{
    $arr['status'] = 'error';
    $arr['message'] = $msg;
    return json_encode($arr);
}

function decimalValues( $amount ){
    if (!empty($amount)){
    $trimmedAmount = trim($amount);   
    }else{
        return 0;
    }
    $amountWithDecimal = number_format($trimmedAmount, 2, '.', '');
    return $amountWithDecimal;
}

function displayPrice($amount){
    $amount = decimalValues($amount);
    return '$' . $amount;
}

function getProductByID( $productID,$attr = ['id','name']  ){
    global $db;  
    $attrs =implode(',',$attr);
    $sql = "SELECT $attrs FROM `products` WHERE id='$productID'";
    return $db->select($sql,true);
   
}

function getProductRatings( $productID ){
    global $db;  
    $sql = "SELECT *  FROM `reviews` WHERE product_id = $productID AND status='approved' ";
    return $db->select($sql);
}

// function productAvgRatings( $productID ){
//     $ratings = getProductRatings( $productID );
//     $totalUsersRate = count($ratings);

//     $totalRatings = [];
//     foreach ($ratings as $rating) {
//         $totalRatings[] = $rating['rating'];
//     }

//     return array_sum($totalRatings) / $totalUsersRate;
// }
// function productAvgRatings($productID) {
//     $ratings = getProductRatings($productID);
//     $totalUsersRate = count($ratings);

//     $totalRatings = [];
//     foreach ($ratings as $rating) {
//         $totalRatings[] = $rating['rating'];
//     }

//     $averageRating = array_sum($totalRatings) / $totalUsersRate;
//     $formattedAverage = number_format($averageRating, 1);

//     return $formattedAverage;
// }
function productAvgRatings($productID) {
    $ratings = getProductRatings($productID);
    $totalUsersRate = count($ratings);

    if ($totalUsersRate === 0) {
        return 0; // or any other default value you prefer
    }

    $totalRatings = [];
    foreach ($ratings as $rating) {
        $totalRatings[] = $rating['rating'];
    }

    $averageRating = array_sum($totalRatings) / $totalUsersRate;
    $formattedAverage = number_format($averageRating, 1);
    
      return $formattedAverage;
}



function productRatingStars( $productID ){
    $averageRatings = productAvgRatings( $productID );
    return displayRatingStars( $averageRatings );
}

// function displayRatingStars( $stars ){

//     $starString = "<ul class='starsList'>";
//     for ($i = 0; $i < 5; $i++) {
//         if( $i < $stars){
//             $starClass = "bi-star-fill";
//         }else{
//             $starClass = "bi-star";
//         }

//         $starString .= '<li class="starItem"><button type="button" class="btn btn-sm">
//             <span class="bi '.$starClass.' text-yellow" aria-hidden="true"></span>
//         </button></li>';
//     }
//     $starString .= "</ul>";
//     return $starString;

// }
function displayRatingStars($stars) {
    $starString = "<ul class='starsList'>";

    for ($i = 0; $i < 5; $i++) {
        if ($i < floor($stars)) {
            $starClass = "bi-star-fill";
        } else {
            if ($i < ceil($stars)) {
                $starClass = "bi-star-half";
            } else {
                $starClass = "bi-star";
            }
        }

        $starString .= '<li class="starItem"><button type="button" class="btn btn-sm">
            <span class="bi ' . $starClass . ' text-yellow" aria-hidden="true"></span>
        </button></li>';
    }

    $starString .= "</ul>";
    return $starString;
}


function getActiveCoupons() {
    global $db;

    $query = "SELECT * FROM coupons WHERE status = 'active'";

    $coupons = $db->select($query);

    return $coupons;
}

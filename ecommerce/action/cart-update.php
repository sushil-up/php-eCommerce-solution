<?php
require_once("header.php");
if( isset($_POST['cart']) ){
    foreach( $_POST['cart'] as $pID => $pVal){
        if( $pID == $pVal['p_id']){
            $cart->update($pID, $pVal['qty']);
        }
    }
}
redirect(BASE_URL.'/cart');
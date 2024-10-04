<?php
    
    require_once('header.php');
    
    if( isset($_SESSION["loggedin_user"]) && $_SESSION["loggedin_user"] != '') session_destroy();
    redirect( BASE_URL."/login" );
?>
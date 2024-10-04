<?php if (!defined('DIRECT_ACCESS')) die('Access Denied!'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cretive Print</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/jquery.slick/1.5.0/slick-theme.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/assets/css/cretive.css?v=<?php echo time(); ?>"> 
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>


    <!-- Main JS Liberary -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script> let BASE_URL = "<?php echo BASE_URL; ?>"</script>
 

</head>

<!-- Include require classes -->

<?php
// Config
$pagetype='home';
$pageid='';
$pageSlug='';
require_once(BASE_PATH . '/autoload.php');
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriSegments = explode('/', $request);

$userdata = getCurrentUser();
if (!empty($userdata))
    {
        $role=$userdata['role'];
        $loginstatus=$role."logged-in";
    }
else{
        $loginstatus="guest-logged-in";
    }
 
if(!empty($uriSegments[1])){
        $pagetype=$uriSegments[1];
}
if(!empty($uriSegments[2])){
        $pageSlug=$pagetype.'-'.$uriSegments[2];
}

?>
<!-- END Include require classes -->

    <body class="<?php echo $pagetype .' '.$pageid.''.$pageSlug.' '.$loginstatus;?>">   
        <!-- top-bar -->
        <header class="site-header">
            <!-- admin top-bar -->
            <?php if ( $userdata = getCurrentUser() ) {
                $name = $userdata['username'];
                if ($userdata['role'] == 'admin') { ?>
                    <div class="admin-bar">  
                    <div class="container-fluid">   
                        <div class="row">
                            <div class="col-lg-10 col-md-6 col-12">
                                <ul class="left-menu">                                    
                                    <?php echo '<li><a class="bi bi-speedometer" href="' . ADMIN_URL . '/index.php">Dashboard</a></li>'; ?>
                                    <?php echo '<li><a class="bi bi-people" href="' . ADMIN_URL . '/users/index.php">Users</a></li>'; ?>
                                    
                                    <?php echo '<li><a class="bi bi-box" href="' . ADMIN_URL . '/product/index.php"> Products</a></li>'; ?>
                                    
                                    <?php if($uriSegments[1]=='product'){
                                        $slugname=$uriSegments[2];
                                        $sql = "SELECT * FROM `products` WHERE slug ='$slugname'";
                                        $productObj = $db->select($sql,true);
                                        $ProductID=$productObj['id'];
                                        
                                        echo '<li><a class="bi bi-pencil" href="' . ADMIN_URL . '/product/edit.php?id='.$ProductID.'"> Edit Product</a></li>'; }?>
                                </ul>
                            </div>
                            <div class="col-lg-2 d-flex justify-content-end">
                                <ul class="right-menu">
                                    <?php echo '<li class="admin-user"><a class="bi bi-person-circle" href="' . ADMIN_URL . '"><span class="name">'.$name.'</span></a></li>'; ?> 
                                </ul>
                            </div>
                        </div>
                    </div>
                    </div>
                    <?php 
                    } 
                }?>
                <div class="top-bar">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-10 d-flex align-items-center ">
                                <div class="top-text-box">
                                    <a href=""> <i class="fa fa-map"></i> Phase 8b,Industrial Area </a>
                                    <a href=""><i class="fa fa-phone"></i>(+1)541421540</a>
                                </div>
                            </div>
                            <div class="col-lg-2 d-flex justify-content-end">
                                <div class="top-icon-box">
                                    <a class="btn btn-light btn-sm text-dark cart-ico" href="<?php echo BASE_URL; ?>/cart"><i class="fa fa-shopping-cart" style="font-size:21px" aria-hidden="true"></i> Cart<span class="cart-counter bg-danger text-light">1</span></a> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <!-- top-bar-end -->

                <!-- Navbar -->
                <section class="nav-bar ">
                    <nav class="navbar navbar-expand-lg ">
                        <div class="container">
                            <div class="nav-img">
                                <a href="<?php echo BASE_URL; ?>"><img class="img-fluid brand" src="<?php echo BASE_URL; ?>/assets/image/logo.png"></a>
                            </div>
                            <ul class="nav navbar-nav navbar-right">

                                <li class="nav-item">
                                    <a class="nav-link" href="#">GIFT</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">DESIGN SERVICES</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">STORES</a>
                                </li>
                                <li class="nav-item">
                                    <?php echo '<a class="nav-link" href="' . BASE_URL . '/shop">shop</a>'; ?>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="#">SUPPORT</a>
                                </li>
                                <li class="nav-item">
                                    <?php if (isset($_SESSION["loggedin_user"]) && $_SESSION["loggedin_user"] != '') { ?>
                                        <div class="dropdown">
                                            <a class="nav-link" role="button" id="dropdownMenuLink"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i></a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <ul class="sub-menu profile">
                                                    <li class="nav-item"><a class="dropdown-item" href="<?php echo BASE_URL; ?>/profile"> My Profile</a></li>
                                                
                                                    <li class="nav-item"><a class="dropdown-item" href="<?php echo BASE_URL; ?>/logout">Log out</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php } else {
                                        echo '<a class="nav-link " href="' . BASE_URL . '/login">SIGN IN</a>';
                                    } ?>
                                </li>                                
                            </ul>

                        </div>
                    </nav>
                </section>
            </header>
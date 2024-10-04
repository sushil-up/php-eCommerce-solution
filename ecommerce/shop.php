<?php

require_once("header.php");
$pageTitle = "Shop";
$pageDescription = "";
require_once("sub-header.php");
?>
<!-- Section-->
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row">
            <?php
                $sql = "SELECT * FROM `products` where status = 'active' order by id DESC";
                $products = $db->select($sql);
                foreach($products as $key => $value){
                    include('partials/product/grid.php');
                }
            ?>              
        </div>
    </div>
</section>
<?php require_once("footer.php"); ?>

<?php
    require_once("header.php");
    $pageTitle = "Search";
    $keyword = $_GET['s'];
    $sql = "SELECT * FROM `products` where name LIKE '%$keyword%' AND status = 'active' order by id DESC";
    $products = $db->select($sql);
    $pageDescription = "<h4>Total results for <strong>\"$keyword\"</strong> : ".count($products)."</h4>";
    require_once("sub-header.php");
?>

<!-- Section-->
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row">
            <?php
                foreach($products as $key => $value){
                    include('partials/product/single.php');
                }
            ?>              
        </div>
    </div>
</section>
<?php require_once("footer.php"); ?>

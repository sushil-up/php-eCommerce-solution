
<?php require_once('../header.php');

    if(isset($_POST['submit'])){
    $productname   = $_POST['product_name'];
    $description   = $_POST['description'];

    $productSlug   = createUniqueSlug( slugify($productname), 'slug', 'products');
   
    $regularprice  = $_POST['regular_price'];
    $salesprice    = $_POST['sales_price'];
    $ratings       = $_POST['ratings'];
    $status        = $_POST['status'];
    $createdate    = date('Y-m-d H:i:s');  
    $sku           = $_POST['s_k_u'];
    if(isset($_FILES["product_image"]["name"]) ){
    $target_file =$_FILES["product_image"]["name"];
    $tmp=$_FILES['product_image']['tmp_name'];
    if(!empty($tmp))
    {
        move_uploaded_file($tmp ,BASE_PATH."/uploads/products/$target_file");
    }
    $sql ="SELECT * FROM `products` WHERE `slug`= '$productSlug'";
    $productInfo = $db->select($sql,true);

    if(!empty($productInfo) ){
        $productSlug = $productSlug.'-1'; 
    }  
     $insertproduct="INSERT INTO `products`(`name`, `slug`, `description`, `image`, `regular_price`, `sales_price`, `ratings`, `status`, `created_date`,`sku`) VALUES 
    ('$productname','$productSlug','$description','$target_file','$regularprice','$salesprice','$ratings','$status','$createdate','$sku')";
    $select = $db->insert($insertproduct);
    redirect(ADMIN_URL .'/product/index.php');
    }
    if ($salesprice >= $regularprice) {
        echo "<script>alert('Sales Price should be lower than Regular Price');</script>";
    }
    } 
    ?>
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
    <h1> Add Product</h1>
    </div>
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item active"></li>
    </ol>
    </div>
    </div>
    </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
    <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- jquery validation -->
    <div class="card card-primary">
    <div class="card-header">
    <h3 class="card-title">Add Product</h3>
    </div>
     <!-- /.card-header -->
     <!-- form start -->
    <form id="quickForm" action=""method="POST"enctype="multipart/form-data">
    <div class="card-body">
    <div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text" name="product_name" class="form-control" id="" value=""required>
    </div>
    <div class="form-group">
    <label for="exampleFormControlTextarea1" id=""class="form-label">Description</label>
    <textarea class="form-control textEditor" rows="3"name="description"></textarea>
    </div>
    <div class="form-group"><div class="form-group">
    <label for="exampleInputPassword1">SKU</label>
    <input type="text" name="s_k_u" class="form-control" id="" value="">
    </div>
    <label for="exampleInputEmail1">Image</label>
    <input type="file" name="product_image" class="form-control" id="" value="">
    </div>
    <div class="form-group">
    <label for="exampleInputPassword1">Regular Price</label>
    <input type="text" name="regular_price" class="form-control" id="" value="" required>
    </div>
    <div class="form-group">
    <label for="exampleInputPassword1">Sales Price</label>
    <input type="text" name="sales_price" class="form-control" id="" value="" rquired>
    </div>
    <div class="form-group">
    <label for="exampleInputPassword1">Ratings</label>
    <input type="text" name="ratings" class="form-control" id="" value="" >
    </div>
    <div class="form-group">
    <label for="exampleInputPassword1" value=""required>Status</label>
    <select class="form-control" aria-label="Default select example"name="status">
    <option value="active">Active</option>
    <option value="inactive">Inactive</option>
    </select>
    </div>
    <!--/.card-body -->
    <button type="submit" class="btn btn-primary"name="submit">Submit</button>
    </form>
    </div>
    <!-- /.card -->
    </div>
    <!--/.col (left) -->
    <!-- right column -->
    <div class="col-md-6">
    </div>
    <!--/.col (right) -->
    </div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </div>
<?php require_once('../footer.php')?>
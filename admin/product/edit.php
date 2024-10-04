<?php require_once('../header.php')?>
<?php
    // Fetch product by ID
    $userid = $_GET['id']; 
    $sql = "SELECT * FROM `products` WHERE id='$userid'";
    $userobj = $db->select($sql, true);
    $row = $userobj;
    $image = $row['image'];
    $description = $row['description'];

    if (isset($_POST['submit'])) {   
        // Form data 
        $productname  = $_POST['product_name'];
        $description  = $_POST['description'];
        $regularprice = $_POST['regular_price'];
        $salesprice   = $_POST['sales_price'];
        $ratings      = $_POST['ratings'];
        $status       = $_POST['status'];
        $updatedate   = date('Y-m-d H:i:s');     
        $sku          = $_POST['s_k_u'];
        $dynamicColumns = '';
        if ($salesprice >= $regularprice) {
            echo "<script>alert('Sales Price should be lower than Regular Price');</script>";
        }
        // Image upload logic
        // if (isset($_FILES["product_image"]["name"])) {  
        //     $target_file = $userid . '-' . $_FILES["product_image"]["name"];            
        //     $tmp = $_FILES['product_image']['tmp_name'];
        //     if (!empty($tmp)) {
        //         move_uploaded_file($tmp, BASE_PATH . "/uploads/products/$target_file");                 
        //         $dynamicColumns .= ", image='$target_file'";
        //     }
        // }

        // Update SKU if it's not empty
        if (!empty($sku)) {
            $dynamicColumns .= ", sku='$sku'";
        }

        // Update product details
        $update = "UPDATE `products` 
                   SET `name`='$productname', `description`='$description', 
                       `regular_price`='$regularprice', `sales_price`='$salesprice', 
                       `ratings`='$ratings', `status`='$status', 
                       `updated_date`='$updatedate' $dynamicColumns 
                   WHERE id='$userid'";
        $productupdate = $db->update($update);
        
        // Redirect after update
        redirect(ADMIN_URL . '/product/index.php');
    }
?>

<!-- HTML Form for Updating Product -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> Update Product</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Product</h3>
                        </div>

                        <!-- Form start -->
                        <form id="productForm" action="" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                <!-- Product Name -->
                                <div class="form-group">
                                    <label for="product_name"> Name</label>
                                    <input type="text" name="product_name" class="form-control" id="product_name" value="<?php echo $row['name']; ?>" required>
                                    <span class="badge badge-light">
                                        <a class="text-dark" href="<?php echo BASE_URL . '/product/' . $row['slug']; ?>" target="_blank">
                                            <?php echo BASE_URL . '/product/' . $row['slug']; ?>
                                        </a>
                                    </span>
                                </div>

                                <!-- Product Description -->
                                <div class="form-group">
                                    <label for="textEditor">Description</label>
                                    <textarea class="form-control textEditor" rows="3" name="description"><?php echo $description; ?></textarea>
                                </div>

                                <!-- SKU -->
                                <div class="form-group">
                                    <label for="s_k_u">SKU</label>
                                    <input type="text" name="s_k_u" class="form-control" id="s_k_u" value="<?php echo $row['sku']; ?>">
                                </div>

                                <!-- Product Image -->
                                <div class="form-group">
                                    <label for="product_image">Product Image</label><br>
                                    <img class="products-image" style="width:100px;" src="../../uploads/products/<?php echo $image; ?>" alt="Product Image"><br><br>
                                    <input type="file" name="product_image" class="form-control" id="product_image">
                                </div>

                                <!-- Regular Price -->
                                <div class="form-group">
                                    <label for="regular_price">Regular Price</label>
                                    <input type="text" name="regular_price" class="form-control" id="regular_price" value="<?php echo $row['regular_price']; ?>" required>
                                </div>

                                <!-- Sales Price -->
                                <div class="form-group">
                                    <label for="sales_price">Sales Price</label>
                                    <input type="text" name="sales_price" class="form-control" id="sales_price" value="<?php echo $row['sales_price']; ?>">
                                </div>

                                <!-- Ratings -->
                                <div class="form-group">
                                    <label for="ratings">Ratings</label>
                                    <input type="text" name="ratings" class="form-control" id="ratings" value="<?php echo $row['ratings']; ?>">
                                </div>

                                <!-- Status -->
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="active" <?php if ($row['status'] == 'active') echo "selected"; ?>>Active</option>
                                        <option value="inactive" <?php if ($row['status'] == 'inactive') echo "selected"; ?>>Inactive</option>
                                    </select>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- JavaScript Validation for Pricing -->
<script>
    document.getElementById('productForm').addEventListener('submit', function(event) {
        var regularPrice = parseFloat(document.getElementById('regular_price').value);
        var salesPrice = parseFloat(document.getElementById('sales_price').value);

        if (salesPrice >= regularPrice) {
            alert("Sales Price must be lower than Regular Price.");
            event.preventDefault(); // Prevent form submission
        }
    });
</script>

<?php require_once('../footer.php') ?>

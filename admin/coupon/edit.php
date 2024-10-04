<?php require_once('../header.php'); ?>

<?php
    // Fetch coupon by ID
    $couponId = $_GET['id']; 
    $sql = "SELECT * FROM `coupons` WHERE id='$couponId'";
    $couponObj = $db->select($sql, true);
    $row = $couponObj;
    $code = $row['code'];
    $discount_type = $row['discount_type'];
    $discount_value = $row['discount_value'];
    $expiry_date = $row['expiry_date'];
    $usage_limit = $row['usage_limit'];
    $status = $row['status'];
    
    $errors = [];

    if (isset($_POST['submit'])) {   
        // Form data 
        $code = trim($_POST['code']);
        $discount_type = $_POST['discount_type'];
        $discount_value = (float)$_POST['discount_value'];
        $expiry_date = $_POST['expiry_date'];
        $usage_limit = (int)$_POST['usage_limit'];
        $status = $_POST['status'];
        $updated_at = date('Y-m-d H:i:s');

        // PHP Validation
        if (empty($code)) {
            $errors[] = "Coupon code is required.";
        }

        if ($discount_value <= 0) {
            $errors[] = "Discount value must be greater than zero.";
        }

        $current_date = date('Y-m-d');
        if ($expiry_date < $current_date) {
            $errors[] = "Expiry date must be today or a future date.";
        }

        if ($usage_limit <= 0) {
            $errors[] = "Usage limit must be greater than zero.";
        }

        // If there are no errors, update coupon details
        if (empty($errors)) {
            $update = "UPDATE `coupons` 
                       SET `code`='$code', `discount_type`='$discount_type', 
                           `discount_value`='$discount_value', `expiry_date`='$expiry_date', 
                           `usage_limit`='$usage_limit', `status`='$status' 
                       WHERE id='$couponId'";

            try {
                $couponUpdate = $db->update($update);

                if ($couponUpdate === false) {
                    throw new Exception("Failed to update coupon. Please check the query.");
                }

                // Redirect after update
                redirect(ADMIN_URL . '/coupon/index.php');
            } catch (Exception $e) {
                // Log error
                error_log($e->getMessage());

                // Display error message
                echo "<div class='alert alert-danger'>An error occurred: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        } else {
            // Display validation errors
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }
    }
?>

<!-- HTML Form for Updating Coupon -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> Update Coupon</h1>
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
                            <h3 class="card-title">Edit Coupon</h3>
                        </div>

                        <!-- Form start -->
                        <form id="couponForm" action="" method="POST">
                            <div class="card-body">
                                <!-- Coupon Code -->
                                <div class="form-group">
                                    <label for="code">Code</label>
                                    <input type="text" name="code" class="form-control" id="code" value="<?php echo htmlspecialchars($row['code']); ?>" required>
                                </div>

                                <!-- Discount Type -->
                                <div class="form-group">
                                    <label for="discount_type">Discount Type</label>
                                    <select class="form-control" name="discount_type" id="discount_type">
                                        <option value="fixed" <?php if ($row['discount_type'] == 'fixed') echo "selected"; ?>>Fixed</option>
                                        <option value="percentage" <?php if ($row['discount_type'] == 'percentage') echo "selected"; ?>>Percentage</option>
                                    </select>
                                </div>

                                <!-- Discount Value -->
                                <div class="form-group">
                                    <label for="discount_value">Discount Value</label>
                                    <input type="number" name="discount_value" class="form-control" id="discount_value" value="<?php echo htmlspecialchars($row['discount_value']); ?>" required>
                                </div>

                                <!-- Expiry Date -->
                                <div class="form-group">
                                    <label for="expiry_date">Expiry Date</label>
                                    <input type="date" name="expiry_date" class="form-control" id="expiry_date" value="<?php echo isset($_POST['expiry_date']) ? $_POST['expiry_date'] : date('Y-m-d', strtotime($expiry_date)); ?>" required>
                                </div>

                                <!-- Usage Limit -->
                                <div class="form-group">
                                    <label for="usage_limit">Usage Limit</label>
                                    <input type="number" name="usage_limit" class="form-control" id="usage_limit" value="<?php echo htmlspecialchars($row['usage_limit']); ?>" required>
                                </div>

                                <!-- Status -->
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="active" <?php if ($row['status'] == 'active') echo "selected"; ?>>Active</option>
                                        <option value="expired" <?php if ($row['status'] == 'expired') echo "selected"; ?>>Expired</option>
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

<!-- Style for Alert and Form -->
<style>
.alert.alert-danger {
    width: 25%;
    text-align: center;
    justify-content: center;
    float: right;
    margin-right: 18px;
    margin-top: 6px;
    color: #fff;
    font-weight: 800;
    font-size: 15px;
}
</style>

<!-- JavaScript Validation for Discount Value -->
<script>
    document.getElementById('couponForm').addEventListener('submit', function(event) {
        var discountValue = parseFloat(document.getElementById('discount_value').value);

        if (discountValue <= 0) {
            alert("Discount Value must be greater than zero.");
            event.preventDefault(); // Prevent form submission
        }
    });
</script>

<?php require_once('../footer.php') ?>

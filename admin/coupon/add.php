<?php
    require_once('../header.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $code = $_POST['code'];
        $discount_type = $_POST['discount_type'];
        $discount_value = $_POST['discount_value'];
        $expiry_date = $_POST['expiry_date'];
        $usage_limit = $_POST['usage_limit'];
        $status = $_POST['status'];

        $errors = [];

        // PHP Validation for expiry date
        $current_date = date('Y-m-d');
        if ($expiry_date < $current_date) {
            $errors[] = "Expiry date must be today or a future date.";
        }

        if (empty($code)) {
            $errors[] = "Coupon code cannot be empty.";
        }
        if ($discount_value <= 0) {
            $errors[] = "Discount value must be greater than 0.";
        }
        if ($usage_limit <= 0) {
            $errors[] = "Usage limit must be greater than 0.";
        }

        // If no errors, proceed with coupon insertion
        if (empty($errors)) {
            $created_at = date('Y-m-d H:i:s');

            $insertQuery = "INSERT INTO `coupons` (code, discount_type, discount_value, expiry_date, usage_limit, status, created_at) 
                            VALUES ('$code', '$discount_type', '$discount_value', '$expiry_date', '$usage_limit', '$status', '$created_at')";

            $insertResult = $db->insert($insertQuery);

            if ($insertResult) {
                redirect(ADMIN_URL . '/coupon/index.php');
            } else {
                echo "<div class='alert alert-danger'>Failed to add coupon. Please try again.</div>";
            }
        } else {
            // Display validation errors
            foreach ($errors as $error) {
                echo "<div class='alert alert-warning alert-dismissible fade show small' role='alert'>
                        $error
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                      </div>";
            }
        }
    }
?>

<!-- HTML Form -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Coupon</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Add Coupon</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Add Coupon</h3>
                        </div>
                        <form class="pad" method="POST" action="">
                            <div class="form-group mb-4">
                                <label for="code" class="h5">Coupon Code:</label>
                                <input type="text" id="code" name="code" class="form-control " readonly>
                            </div>

                            <div class="form-group mb-4">
                                <label for="discount_type" class="h5">Discount Type:</label>
                                <select id="discount_type" name="discount_type" class="form-control " required>
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed">Fixed Amount</option>
                                </select>
                            </div>

                            <div class="form-group mb-4">
                                <label for="discount_value" class="h5">Discount Value:</label>
                                <input type="number" id="discount_value" name="discount_value" step="0.01" class="form-control " required>
                            </div>

                            <div class="form-group mb-4">
                                <label for="expiry_date" class="h5">Expiry Date:</label>
                                <input type="date" id="expiry_date" name="expiry_date" class="form-control " required>
                            </div>

                            <div class="form-group mb-4">
                                <label for="usage_limit" class="h5">Usage Limit:</label>
                                <input type="number" id="usage_limit" name="usage_limit" class="form-control " required>
                            </div>

                            <div class="form-group mb-4">
                                <label for="status" class="h5">Status:</label>
                                <select id="status" name="status" class="form-control " required>
                                    <option value="active">Active</option>
                                    <option value="expired">Expired</option>
                                </select>
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Add Coupon</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .h5, h5 {
        font-size: 1rem;
    }
    form.pad {
        padding: 1.25rem;
    }
    .alert.alert-warning.alert-dismissible.fade.show.small {
        width: 25%;
        text-align: center;
        justify-content: center;
        float: right;
        margin-right: 18px;
        margin-top: 6px;
        background-color: #dc3545;
        color: #fff;
        font-weight: 800;
        font-size: 15px;
    }
    .btn-block {
        width: auto !important;
    }
</style>

<script>
    function generateCode() {
        const code = Math.random().toString(36).substring(2, 14).toUpperCase();
        document.getElementById('code').value = code;
    }

    window.onload = function() {
        generateCode(); // Automatically generate coupon code on page load

        <?php if (isset($_SESSION['coupon_added']) && $_SESSION['coupon_added'] === true): ?>
            alert('Coupon added successfully!');
            <?php unset($_SESSION['coupon_added']); ?>
        <?php endif; ?>
    }
</script>

<?php require_once('../footer.php'); ?>

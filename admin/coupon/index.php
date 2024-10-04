<?php require_once('../header.php');
$sql = "SELECT * FROM `coupons` ORDER BY id DESC";
$productObjs = $db->select($sql);
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>All Coupons</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a href="<?php echo ADMIN_URL; ?>/coupon/add.php" class="btn btn-block btn-primary btn-sm">Add New</a>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="couponsTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Code</th>
                                        <th>Discount Type</th>
                                        <th>Discount Value</th>
                                        <th>Expiry Date</th>
                                        <th>Usage Limit</th>
                                        <th>Status</th>
                                        <th class="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($productObjs as $product) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                                        <td><?php echo htmlspecialchars($product['code']); ?></td>
                                        <td><?php echo htmlspecialchars($product['discount_type']); ?></td>
                                        <td><?php echo htmlspecialchars($product['discount_value']); ?></td>
                                        <td><?php echo htmlspecialchars($product['expiry_date']); ?></td>
                                        <td><?php echo htmlspecialchars($product['usage_limit']); ?></td>
                                        <td><?php echo htmlspecialchars($product['status']); ?></td>
                                        <td>
                                            <a href="<?php echo ADMIN_URL; ?>/coupon/edit.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="<?php echo ADMIN_URL; ?>/coupon/delete.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#couponsTable').DataTable();
    });
</script>
<?php require_once('../footer.php'); ?>

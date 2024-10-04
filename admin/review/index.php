<?php require_once('../header.php');
 $sql = "SELECT * FROM `users`";
 $userInfo = $db->select($sql);

 
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>All Reviews</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                       
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row mb-3">
                                
                                <div class="col-md-3">
                                    <label for="searchBystatus">Status</label>
                                    <br>
                                    <select id="searchBystatus" name="searchByrole" class="form-control">
                                        <option value="">All</option>
                                        <option value="approved">Approved</option>
                                        <option value="banned">Banned</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="searchByUser">User</label>
                                    
                                    <br>
                                  
                                    
                                    <select id="searchByUser" name="searchByUser" class="form-control">
                                        <option value="">All</option>
                                        <?php foreach ($userInfo as $user) {
                                            $fullName = $user['f_name'] . ' ' . $user['l_name'];
                                            $username = $user['username'];
                                        ?>
                                            <option value="<?php echo $username; ?>"><?php echo $fullName . ' (' . $username . ')'; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                                <?php 
                                $sql = "SELECT * FROM `products`";
                                $productInfo = $db->select($sql);
                                ?>

                                <div class="col-md-3">
                                    <label for="searchByProduct">Product</label>
                                    <br>
                                    <select id="searchByProduct" name="searchByProduct" class="form-control">
                                        <option value="">All</option>
                                        <?php foreach ($productInfo as $product) { ?>
                                            <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                               
                                <div class="col-md-2">
                                    <button id="clear-dates-btn" class="btn btn-danger mt-6 d-inline-block" style="padding: .25rem .5rem; line-height: 1.5; border-radius: .2rem; margin-top: 33px; margin-left: 17px;">Clear Filters</button>
                                </div>
                            </div>

                            <table class="table sortableTable table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Product Name</th>
                                        <th>Full Name </th>
                                        <th>Rating</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th class="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Table body content here -->
                                    <!-- ... -->

                                    <!-- <tr>
                                        <td>1</td>
                                        <td><a href="https://shop.web-xperts.xyz/product/maile-jacobson" target="_blank">Maile Jacobson</a></td>
                                        <td>Admin User</td>
                                        <td>2</td>
                                        <td>Pending</td>
                                        <td>8 Aug, 2023</td>
                                        <td>
                                            <a href="#" class="btn block btn-primary btn-sm">View</a>
                                            <a href="#" class="btn-sm btn btn-danger deleteRecord">Delete</a>
                                        </td>
                                    </tr> -->

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once('../footer.php'); ?>
<script>
    $(document).ready(function() {
        const table = $('.sortableTable').DataTable({
            //hide sorting 
            columnDefs: [{
                targets: 'action',
                orderable: false
            }],
            "processing": true,
            "serverSide": true,
            "dataType": 'json',
            "destroy": true,
           
            
            "ajax": {
                "url": "<?php echo ADMIN_URL . '/review/ajax-review-data'; ?>",
                "type": "POST",
                data: function (d) {
                    d.statusFilter = $('#searchBystatus').val();
                    d.userFilter = $('#searchByUser').val();
                    d.productFilter = $('#searchByProduct').val();
                    
                }
            },
            "columns": [
                { "data": "id" },
                { "data": "product_id" },
                { "data": "user_id" },
                // { "data": "comment" },
                { "data": "rating" },
                { "data": "status" },
                { "data": "created_date" },
                { "data": "action" }
            ],
        });

        // Add event listeners to trigger DataTable search on input change
        $('#searchBystatus, #searchByUser, #searchByProduct').on('change', function () {
            table.draw();
        });

        // Attach the click event to the "Clear Filters" button
        $("#clear-dates-btn").on('click', function () {
            // Clear the filter values and redraw the DataTable
            $('#searchBystatus').val('');
            $('#searchByUser').val('');
            $('#searchByProduct').val('');
            table.draw();
        });
    });
</script>

<?php require_once('../header.php'); ?>
<?php
    $sql = "SELECT * FROM `products` order by id DESC";
    $productObj = $db->select($sql);
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
    <h1>All Product</h1>
    </div>
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <a href="<?php echo ADMIN_URL?>/product/add.php" class="btn btn-block btn-primary btn-sm">Add New</a>
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
    <table   class="table table-bordered table-hover sortableTable">
    <thead>
    <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Image</th>
    <th>price</th>
    <th>Sales</th>
    <th class= "action">Action</th>
    </tr>
    </thead>
    <tbody>
   
    </tfoot>
    </table>
    </div>
    <!-- /.card-body -->
    </div>
    <!-- /.card -->
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
    $(document).ready(function () {
        
        $('.sortableTable').DataTable({
            columnDefs: [{
                targets: 'action',
                orderable: false
            }],
            "processing": true,
            "serverSide": true,
            "dataType": 'json',
            "destroy": true,
            "ajax": {
                "url": "<?php echo ADMIN_URL . '/product/ajax-product-data'; ?>",
                "type": "POST",
            },
            "columns": [
                { "data": "ID" },
                { "data": "Name" },
                {
                    "data": "Image",
                    "render": function (data, type, row) {
                        // Insert the HTML image tag with the product image URL
                        return '<img class="products-image" style="width:100px;height:50px;" src="../../uploads/products/' + data + '">';
                    }
                },
                { "data": "Price" },
                { "data": "Sales" },
                { "data": "Action" }
            ],            
        });
    });
</script>



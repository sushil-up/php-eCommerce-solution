<?php require_once('../header.php'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a class="btn btn-block btn-primary btn-sm" href="<?php echo ADMIN_URL ?>/users/add.php">Add
                            New</a>
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
                                    <label for="searchByrole">Role</label>
                                    <br>
                                    <select id="searchByrole" name="searchByrole" class="form-control">
                                        <option value="">All</option>
                                        <option value="admin">Admin</option>
                                        <option value="customer">Customer</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="searchBystatus">Status</label>
                                    <br>
                                    <select id="searchBystatus" name="searchBystatus" class="form-control">
                                        <option value="">All</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
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
                                        <th>Username</th>
                                        <th class="fullrname">Full Name</th>
                                        <th>Image</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th class="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Table body content here -->
                                    <!-- ... -->
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
    $(document).ready(function () {
        function clearDates() {
            $('#start-date').val('');
            $('#end-date').val('');
        }
        
        var dataTable = $('.sortableTable').DataTable({
            "columnDefs": [
                {
                    "targets": 'action',
                    "orderable": false
                },
                // Add more columnDefs if needed...
            ],
            "processing": true,
            "serverSide": true,
            "dataType": 'json',
            "destroy": true,
            "ajax": {
                "url": "<?php echo ADMIN_URL . '/users/ajax-users-data'; ?>",
                "type": "POST",
                "data": function (d) {
                    // Add custom filters to the DataTable request as query parameters
                    d.searchByrole = $('#searchByrole').val();
                    d.searchBystatus = $('#searchBystatus').val();
                }
            },
            "columns": [
                { "data": "ID" },
                { "data": "username" },
                { "data": "fullname" },
                {
                    "data": "Image",
                    "render": function (data, type, row) {
                        // Insert the HTML image tag with the product image URL
                        return '<img class="users-image" style="width:100px;height:50px;" src="../../uploads/users/' + data + '">';
                    }
                },
                { "data": "role" },
                { "data": "status" },
                { "data": "Action" }
            ],
            "drawCallback": function (settings) {
                var api = this.api();
                var paginationWrapper = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                var searchByrole = $("#searchByrole").val();

                if (searchByrole) {
                    paginationWrapper.hide();
                } else {
                    paginationWrapper.show();
                }
            }
        });

        // Add event listener for role dropdown change
        $('#searchByrole').on('change', function () {
            dataTable.ajax.reload();
        });

        // Add event listener for status dropdown change
        $('#searchBystatus').on('change', function () {
            dataTable.ajax.reload();
        });

        // Attach the click event to the "Clear Dates" button using .on('click', ...)
        $("#clear-dates-btn").on('click', function () {
            // Clear the dates using the custom method from DataTable
            clearDates();
            $('#searchByrole').val('');
            $('#searchBystatus').val('');
            
            // Reload the DataTable to clear all custom filters
            dataTable.ajax.reload();
        });
    });
</script>

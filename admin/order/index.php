<?php require_once('../header.php'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>All Orders</h1>
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
                            <label for="paymentstatus">Payment Status</label>
                            <br>
                            <select id="paymentstatus" class="form-control">
                                <option value="">All</option>
                                <option value="Payment complete.">Payment complete</option>
                                <option value="Pending">Pending</option>
                                <option value="Failed">Failed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="searchByStatus">Status</label>
                            <br>
                            <select id="searchByStatus" class="form-control">
                                <option value="">All</option>
                                <option value="refund">Refund</option>
                                <option value="succeeded">Succeeded</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="start-date">Start Date:</label>
                            <input type="date" id="start-date" name="start-date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="end-date">End Date:</label>
                            <input type="date" id="end-date" name="end-date" class="form-control">
                        </div>
                        <div class="col-md-2">
                        <button id="clear-dates-btn" class="btn btn-danger mt-6 d-inline-block" style="padding: .25rem .5rem; sline-height: 1.5; border-radius: .2rem; margin-top: 33px; margin-left: 17px;">Clear Filters</button>
                    </div>

                    </div>

                            <table class="table table-bordered table-hover sortableTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer Name</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                        <th>Total</th>
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
    $(document).ready(function() {
        // Define the clearDates function inside the document ready function
        function clearDates() {
            $('#start-date').val('');
            $('#end-date').val('');
        }

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
                "url": "<?php echo ADMIN_URL . '/orders/ajax-data'; ?>",
                "type": "POST",
                //custoum filters 
                "data": function (d) {
                    // Add custom filters to the DataTable request
                    d.paymentstatus = $('#paymentstatus').val();
                    //status
                    d.searchByStatus = $('#searchByStatus').val();
                    //date 
                    d.start_date = $("#start-date").val();
                    d.end_date = $("#end-date").val();
                }
            },
            "columns": [
                { "data": "id" },
                { "data": "customer_name" },
                { "data": "date" },
                { "data": "status" },
                { "data": "payment_status" },
                { "data": "total" },
                { "data": "action" }
            ],
            // hiding the pagination on custoum filters
            "drawCallback": function (settings) {
                var api = this.api();
                var paginationWrapper = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                var recordsFiltered = api.page.info().recordsFiltered;

                // Check if the start and end date fields have values
                var startDate = $("#start-date").val();
                var endDate = $("#end-date").val();

                if (startDate && endDate) {
                    // If both start date and end date are provided, hide pagination
                    paginationWrapper.hide();
                } else {
                    // Show pagination if either start date or end date is empty
                    paginationWrapper.show();
                }
                var paymentstatus = $('#paymentstatus').val();
                if (paymentstatus) {
                    // If both start date and end date are provided, hide pagination
                    paginationWrapper.hide();
                }
            }
        });

        // Add event listeners to trigger DataTable search on input change
        $('#paymentstatus, #searchByStatus, #start-date, #end-date').on('change', function () {
            table.draw();
        });

        
        // Attach the click event to the "Clear Dates" button using .on('click', ...)
        $("#clear-dates-btn").on('click', function () {
            // Clear the dates using the custom method from DataTable
            clearDates();
            $('#paymentstatus').val('');
            $('#searchByStatus').val('');
            
            table.draw();
        });
    });
</script>


   
   


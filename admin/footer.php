
<footer class="main-footer">
  <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
  All rights reserved.
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 3.2.0
  </div>
</footer>
<!-- jQuery -->
<script src="<?php echo ADMIN_URL; ?>/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo ADMIN_URL; ?>/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo ADMIN_URL; ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- sweetalert2JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.all.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo ADMIN_URL; ?>/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo ADMIN_URL; ?>/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?php echo ADMIN_URL; ?>/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo ADMIN_URL; ?>/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo ADMIN_URL; ?>/plugins/moment/moment.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo ADMIN_URL; ?>/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?php echo ADMIN_URL; ?>/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo ADMIN_URL; ?>/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo ADMIN_URL; ?>/dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo ADMIN_URL; ?>/dist/js/pages/dashboard.js"></script>
<!-- pagination -->
<script src="<?php echo ADMIN_URL; ?>/plugins/datatables/jquery.dataTables.min.js"></script>


<!-- Bootstrap 4 -->
<!-- DataTables  & Plugins -->
<script src="<?php echo ADMIN_URL; ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo ADMIN_URL; ?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- <script>
  $(function () {

    $(".sortableTable").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    
    $('.textEditor').summernote({height: 200})

    $('.deleteRecord').click(function(e){
      e.preventDefault();

      let deletePath = $(this).attr('href');
      if( typeof deletePath === 'undefined' || deletePath == "") deletePath = $(this).attr('data-url');
      
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire(
            'Deleted!',
            'Your file has been deleted.',
            'success'
          )
          setTimeout(function(){
            window.location = deletePath;
          }, 1000);
        }
      })
    });
  });
  

</script> -->

<script>
$('.textEditor').summernote({height: 200})
  $(document).ready(function() {
    $('.sortableTable').on('click', '.deleteRecord', function(e) {
      e.preventDefault();

      // Get the URL from the "href" attribute of the link
      var deleteUrl = $(this).attr('href');

      // Show the SweetAlert confirmation popup
      Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          // If the user confirms the deletion, proceed to the delete action
          window.location.href = deleteUrl;
        }
      });
    });
  });
</script>


<!-- <script>

  $(document).ready(function() {
    $('#ajaxTableData').on('click', '.deleteRecord', function(e) {
      e.preventDefault();

      // Get the URL from the "href" attribute of the link
      var deleteUrl = $(this).attr('href');

      // Show the SweetAlert confirmation popup
      Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          // If the user confirms the deletion, proceed to the delete action
          window.location.href = deleteUrl;
        }
      });
    });
  });
</script> -->


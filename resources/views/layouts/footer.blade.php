                
                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <script>document.write(new Date().getFullYear())</script> © Accounting Software. 
                                Supported By <a href="tel:03331736316">Arsha Consultants</a>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <!-- Vendor js -->
        <script src="{{ asset('assets/js/vendor.min.js')}}"></script>
        <script src="{{ asset('assets/libs/select2/js/select2.min.js')}}"></script>
        <script src="{{ asset('assets/libs/multiselect/js/jquery.multi-select.js')}}"></script>
        <!-- optional plugins -->
        <script src="{{ asset('assets/libs/moment/min/moment.min.js')}}"></script>
        
        <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
        <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
        <script src="{{ asset('assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
        <script src="{{ asset('assets/libs/datatables.net-select/js/dataTables.select.min.js')}}"></script>
        
        <!-- Datatables init -->
        <script src="{{ asset('assets/js/pages/datatables.init.js')}}"></script>
        <!-- page js -->
        
        <!-- App js -->
        <script src="{{ asset('assets/js/app.min.js')}}"></script>
        <script src="{{ asset('assets/js/bootstrap-notify.js')}}"></script>
        <script src="{{ asset('assets/js/bootstrap-notify.min.js')}}"></script>
        <script src="{{ asset('assets/js/jquery.table2excel.js')}}"></script>
        <script>
            $(document).ready(function () {
                $(".select2").select2();
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });
                $('#select-all').click(function(event) {   
                    if(this.checked) {
                        // Iterate each checkbox
                        $(':checkbox').each(function() {
                            this.checked = true;                        
                        });
                    } else {
                        $(':checkbox').each(function() {
                            this.checked = false;                       
                        });
                    }
                }); 
            });
            function exportReport() {

                var table = $('#ExportTable');

                $(table).table2excel({

                    // exclude CSS class

                    exclude: ".noExl",

                    name: "Student_List",

                    filename: "NTC_Exported_Report_" + $.now(),//do not include extension

                    fileext: ".xls", // file extension

                    preserveColors: true,

                    sheetName: "NTC_Exported_Report_"

                });

            }
        </script>
        @if(Session::has('success'))
            <script>
                $.notify({
                title: '<strong>SUCCESS!</strong>',
                message: '<?= Session::get('success')?>'
                },{
                type: 'success'
                });
            </script>
        @endif
        @if(Session::has('error'))
            <script>
                $.notify({
                title: '<strong>Error!</strong>',
                message: '<?= Session::get('error')?>'
                },{
                type: 'danger'
                });
            </script>
        @endif
    </body>
</html>
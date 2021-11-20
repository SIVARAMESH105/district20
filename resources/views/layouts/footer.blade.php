<script src="{{ asset('js/app.js') }}"></script>

<script src="{{ asset('custom/adminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script src="{{ asset('custom/adminLTE/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('custom/adminLTE/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('custom/adminLTE/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('custom/adminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="{{ asset('custom/adminLTE/dist/js/adminlte.js') }}"></script>
<script type="text/javascript" src="{{ asset('custom/sweetalert2/js/sweetalert2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('custom/sweetalert.js') }}"></script>
<script src="{{ asset('custom/adminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('custom/adminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script src="{{ asset('custom/adminLTE/plugins/toastr/toastr.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
  bsCustomFileInput.init();
});
</script>
@yield('script')
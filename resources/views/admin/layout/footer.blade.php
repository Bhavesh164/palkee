  <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
  <script src="{{asset('resources/admin_template/bootstrap/js/popper.min.js')}}"></script>
  <script src="{{asset('resources/admin_template/bootstrap/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('resources/admin_template/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
  <script src="{{asset('resources/admin_template/assets/js/app.js')}}"></script>
  <script>
      $(document).ready(function() {
          App.init();
      });
  </script>
  <script src="{{asset('resources/admin_template/assets/js/custom.js')}}"></script>
  <script src="https://cdn.tiny.cloud/1/g23s97dat9iodqx6mgr8rwdz0nzdotpalq20nbuv7g2lbq13/tinymce/5/tinymce.min.js"></script>
  <!-- END GLOBAL MANDATORY SCRIPTS -->



  <script src="{{asset('resources/admin_template/assets/js/scrollspyNav.js')}}"></script>

  <script src="{{asset('resources/admin_template/plugins/file-upload/file-upload-with-preview.min.js?'.time())}}"></script>

  <script src="{{asset('resources/admin_template/plugins/sweetalerts/sweetalert2.min.js')}}"></script>

  <script src="{{asset('resources/admin_template/plugins/sweetalerts/custom-sweetalert.js')}}"></script>

  <script src="{{asset('resources/admin_template/plugins/select2/select2.min.js')}}"></script>

  <script src="{{asset('resources/admin_template/plugins/flatpickr/flatpickr.js')}}"></script>

  <!-- BEGIN PAGE LEVEL SCRIPTS -->

  <!-- google maps api -->
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCZFyddTgJtYkx0W77jL1dV7-tYBXxa9FI&libraries=places">
  </script>

  @yield('page_script')

  <!-- BEGIN CUSTOM SCRIPTS ( END OF PAGE ) -->
  <script src="{{asset('resources/assets/js/custom.js')}}"></script>
  <!-- END CUSTOM SCRIPTS ( END OF PAGE ) -->
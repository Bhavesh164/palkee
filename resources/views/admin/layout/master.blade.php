<!DOCTYPE html>
<html lang="en">
<script type="text/javascript">
    var BASE_URL = '<?php echo url('/'); ?>';
</script>
<!-- Mirrored from designreset.com/cork/ltr/demo6/index2.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 02 Jun 2021 07:53:31 GMT -->
@include('admin.layout.header')

<body>
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    @include('admin.layout.navbar')

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        @include('admin.layout.sidebar')

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">

            @yield('content')

            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <!--                    <p class="">Copyright © 2021 <a target="_blank" href="https://designreset.com/">DesignReset</a>, All rights reserved.</p>-->
                </div>
                <div class="footer-section f-section-2">
                    <p class="">Copyright © 2021 <a target="_blank" href="https://designreset.com/">Palkee </a>, All rights reserved.</p>
                    <!--                    <p class="">Coded with <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></p>-->
                </div>
            </div>

        </div>
        <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->

    @include('admin.layout.footer')

</body>

<!-- Mirrored from designreset.com/cork/ltr/demo6/index2.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 02 Jun 2021 07:53:32 GMT -->

</html>
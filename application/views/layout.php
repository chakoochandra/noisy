<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title : APP_NAME ?></title>

    <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.ico') ?>" type="image/png">

    <!-- Google Font: Source Sans Pro -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> -->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome/css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/AdminLTE-3.2.0/plugins/fontawesome-free/css/all-custom.css') ?>">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/AdminLTE-3.2.0/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/AdminLTE-3.2.0/dist/css/adminlte.min.css') ?>">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/toastr.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/jquery-ui.min.css') ?>">

    <link rel="stylesheet" href="<?php echo base_url('assets/css/light.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/dark.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/antrian.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/glow.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/busy-load.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url((!isset($isPrivate) || $isPrivate ? 'assets/particles/particles.css' : 'assets/particles/particles-gray.css')) ?>">

    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-datepicker/bootstrap-datepicker.min.css') ?>">

    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/AdminLTE-3.2.0/plugins/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/AdminLTE-3.2.0/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">

    <!-- jQuery -->
    <script src="<?php echo base_url('assets/vendor/AdminLTE-3.2.0/plugins/jquery/jquery.min.js') ?>"></script>
    <script type="text/javascript">
        var $ = jQuery.noConflict();
    </script>

    <script src="<?php echo base_url('assets/js/jquery-ui.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/busy-load.min.js') ?>"></script>

    <!-- moment -->
    <script src="<?php echo base_url('assets/vendor/moment/moment.js') ?>"></script>
    <script src="<?php echo base_url('assets/vendor/moment/locale/id.js') ?>"></script>
</head>

<body id="my-layout" class="sidebar-mini layout-footer-fixed layout-navbar-fixed layout-fixed dark-mode">
    <div class="wrapper">
        <!-- Navbar -->
        <?php if (!isset($hasNavigation) || $hasNavigation) {
            $this->load->view('_navigation');
        } ?>

        <div class="container-fluid text-right"><span style="font-size: x-small;"><?php echo get_client_ip() ?></span></div>

        <?php $showParticles = !(isset($hideParticles) && $hideParticles) ?>

        <?php if ($showParticles) : ?>
            <div id="particles-js"></div>
        <?php endif ?>

        <div class="content-wrapper" style="min-height: 393px;">
            <section class="content container-main">
                <?php $this->load->view($main_body) ?>
            </section>
        </div>

        <?php $this->load->view('_footer') ?>
    </div>

    <script src="<?php echo base_url('assets/vendor/AdminLTE-3.2.0/plugins/select2/js/select2.full.min.js') ?>"></script>

    <script type="text/javascript">
        <?php if ($this->session->flashdata('welcome')) : ?>
            openModal('<?php echo base_url('site/view_profile/' . $this->user->id) ?>', {
                title: 'Selamat Datang <?php echo $this->user->nama_lengkap ?>!',
            });
        <?php endif ?>

        $(document).ready(function() {
            var showParticles = '<?php echo $showParticles ? 1 : 0 ?>' == 1;
            if (showParticles) {
                particlesJS.load('particles-js', '<?php echo base_url((!isset($isPrivate) || $isPrivate ? 'assets/particles/particles.json' : 'assets/particles/particles-gray.json')) ?>', function() {});
            }
        });
    </script>
    <script src="<?php echo base_url('assets/particles/particles.min.js') ?>"></script>
</body>

</html>
<link rel="stylesheet" href="<?php echo base_url('assets/css/waviy.css') ?>">

<footer class="main-footer p-1 <?php echo (is_development() ? 'bg-danger' : '') ?>" style="margin-left: <?php echo isset($hasNavigation) && $hasNavigation ? '250px' : '0 !important' ?>;">
    <div class="d-sm-none d-xs-flex flex-column text-xs">
        <div class="text-center"><strong><a class="btn-glowing" target="_blank" href="<?php echo base_url("site/about") ?>" target="_blank">CHAKO</a> &copy; <?php echo date('Y') ?></strong></div>
        <div class="text-center"><span>IP Anda: <?php echo get_client_ip() ?></span> | <b>Versi</b> <?php echo APP_VERSION ?></div>
    </div>

    <div class="d-none d-sm-flex justify-content-center">
        <div class="mx-2"><strong><a class="btn-glowing" target="_blank" href="<?php echo base_url("site/about") ?>" target="_blank">CHAKO</a> &copy; <?php echo date('Y') ?></strong></div>
        <div class="mx-2"><span>IP Anda: <?php echo get_client_ip() ?></span> | <b>Versi</b> <?php echo APP_VERSION ?><?php echo (is_development() ? ' | <b>DEVELOPMENT MODE</b>' : '') ?></div>
    </div>
</footer>

<div class="modal fade" id="modal-input" role="dialog" style="z-index: 1040;">
    <div id="modal-input-dialog" class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="color: darkturquoise; text-align: center; font-weight: bold; ">KETIKKAN NOMOR PERKARA</h4>
            </div>
            <div class="modal-body"></div>
            <big>
                <h1 id="counter" class="mb-3" style="color: red; text-align: center; margin-top: 0; display: none;"></h1>
            </big>
        </div>
    </div>
</div>

<!-- Bootstrap 4 -->
<script src="<?php echo base_url('assets/vendor/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/vendor/AdminLTE-3.2.0/dist/js/adminlte.min.js') ?>"></script>

<!-- overlayScrollbars -->
<script src="<?php echo base_url('assets/vendor/AdminLTE-3.2.0/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>

<!-- bootstrap datepicker -->
<script src="<?php echo base_url('assets/bootstrap-datepicker/bootstrap-datepicker.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/datepicker.js') ?>"></script>

<script src="<?php echo base_url('assets/vendor/AdminLTE-3.2.0/plugins/jquery-knob/jquery.knob.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/toastr.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.history.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/webcam-easy/webcam-easy.js') ?>"></script>
<script src="<?php echo base_url('assets/js/main.js') ?>"></script>

<script type='text/javascript'>
    var csrf_token_name = '<?php echo $this->security->get_csrf_token_name() ?>';
    var csrf_hash = '<?php echo $this->security->get_csrf_hash() ?>';

    toastr.options = {
        "debug": false,
        "positionClass": "toast-custom-top-full-width",
        "progressBar": true,
        "onclick": null,
        "fadeIn": 300,
        "fadeOut": 1000,
        "timeOut": 5000,
        "extendedTimeOut": 1000
    }

    <?php if ($this->session->flashdata('success')) { ?>
        toastr.success("<?php echo $this->session->flashdata('success') ?>");
    <?php } else if ($this->session->flashdata('error')) { ?>
        toastr.error("<?php echo $this->session->flashdata('error') ?>");
    <?php } else if ($this->session->flashdata('warning')) { ?>
        toastr.warning("<?php echo $this->session->flashdata('warning') ?>");
    <?php } else if ($this->session->flashdata('info')) { ?>
        toastr.info("<?php echo $this->session->flashdata('info') ?>");
    <?php } ?>
</script>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">

    <title><?= esc($title) ?></title>
	<meta name="description" content="<?= esc($description) ?>">

    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    <meta name="theme-color" content="#2261FF">

    <link rel="shortcut icon" href="<?= base_url("favicon.ico") ?>">
    <!--<link rel="apple-touch-icon" sizes="57x57" href="<?php //echo base_url("favicon-57.png") ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php //echo base_url("favicon-180.png") ?>">
    <link rel="icon" type="image/png" href="<?php //echo base_url("favicon.png") ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php //echo base_url("favicon-32.png") ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php //echo base_url("favicon-16.png") ?>">-->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap-reboot.min.css" integrity="sha512-HJaQ4y3YcUGCWikWDn8bFeGTy3Z/3IbxFYQ9G3UAWx16PyTL6Nu5P/BDDV9s0WhK3Sq27Wtbk/6IcwGmGSMXYg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="<?= base_url("css/login-main.css") ?>" rel="stylesheet">
    <link href="<?= base_url("css/login-auth.css") ?>" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="<?= base_url("js/login-auth.js") ?>"></script>

  </head>
  <body>
    <div id="main" class="section section_main">
        <?= $this->renderSection('content') ?>
    </div><!-- #main -->
</body>
</html>
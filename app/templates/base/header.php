<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="System obiadowy PLOPŁ">
    <meta name="author" content="Adam Zambrzycki @ zambrzycki.net">

    <link rel="icon" type="image/png" href="<?= Assets::get('img/favicons/favicon-32x32.png') ?>" sizes="32x32">
    <link rel="icon" type="image/png" href="<?= Assets::get('img/favicons/favicon-16x16.png') ?>" sizes="16x16">
    <link rel="manifest" href="<?= Assets::get('img/favicons/manifest.json') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= Assets::get('img/favicons/apple-touch-icon.png') ?>">
    <link rel="mask-icon" href="<?= Assets::get('img/favicons/safari-pinned-tab.svg') ?>" color="#5bbad5">
    <link rel="shortcut icon" href="<?= Assets::get('img/favicons/favicon.ico') ?>">
    <meta name="apple-mobile-web-app-title" content="<?= config('general.siteTitle') ?>">
    <meta name="application-name" content="<?= config('general.siteTitle') ?>">
    <meta name="msapplication-config" content="<?= Assets::get('img/favicons/browserconfig.xml') ?>">
    <meta name="theme-color" content="#1b1b1b">

    <title><?= (!is_null($title) ? $this->e($title) : config('general.siteTitle')) ?></title>

    <link href="<?= Assets::get('css/vendor/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= Assets::get('css/style.css') ?>" rel="stylesheet">

    <link href="<?= Assets::get('css/vendor/datepicker.css') ?>" rel="stylesheet">
    <link href="<?= Assets::get('css/vendor/jquery.bootstrap-touchspin.css') ?>" rel="stylesheet">
    <link href="<?= Assets::get('css/vendor/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?= Assets::get('css/vendor/social-buttons.css') ?>" rel="stylesheet">

    <?= insertIf($styles, isset($styles)) ?>

    <script src="<?= Assets::get('js/vendor/jquery.js') ?>"></script>
    <script src="<?= Assets::get('js/vendor/bootstrap.min.js') ?>"></script>
    <script src="<?= Assets::get('js/vendor/jquery.bootstrap-touchspin.min.js') ?>"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

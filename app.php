<?php
$config = require('config/config.php');

require 'app/managers/internal/Dependencies.php';
Dependencies::setup();

// It have to be placed after loading dependencies,
// otherwise there will be an error during loading `data` array from session
session_start();

SiteAccessControl::verify();
Debug::setup();

Auth::autoLogin();

Pages::setup();
Pages::load();
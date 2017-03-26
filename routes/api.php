<?php
Router::add(new Route('cron::send::raport', '/cron/raport/{level}/{target}', [
    "GET" => "CronController::send"
], Roles::Guest));

Router::add(new Route('cron::send::raport', '/cron/raport/{level}', [
    "GET" => "CronController::send"
], Roles::Guest));
<?php
Router::add(new Route('cron::send::raport', '/cron/raport/{level}', [
    "GET" => "CronController::send"
], Roles::Guest));
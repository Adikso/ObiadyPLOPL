<?php

$config = [

    "general" => [
        "enabled" => true,
        "debug" => false,

        "baseURL" => "//obiady.plopl.ml/",
        "siteTitle" => "Obiady PLOPŁ"
    ],

    "db" => [
        "host" => "localhost",
        "dbname" => "",
        "username" => "",
        "password" => ""
    ],

    "orders" => [
        "cost" => 9.0,
        "pizza" => [
            "ingredients" => [
                "szynka", "salami", "bekon", "kabanos",
                "oliwki", "papryka", "pomidor", "groszek",
                "kukurydza", "papryczki jalapeno",
                "papryczki pepperoni", "ananas",
                "pieczarka", "kurczak wędzony", "kebab drobiowy",
                "ogórek konserwowy", "szczypiorek", "szparagi",
                "czosnek", "banan", "brzoskwinie", "cebula",
                "brokuły", "kiełbasa"
            ],

            "ingredients_amount" => 2
        ]
    ],

    "access" => [
        "whitelist" => [
            "entries" => [
                '127.0.0.1',
                '::1'
            ],

            "enable" => false
        ],

        "blacklist" => [
            "entries" => [],

            "enable" => false
        ],

        "block-iframe" => true
    ],

    "login" => [
        "facebook" => [
            "app_id" => '',
            "app_secret" => '',
            "default_graph_version" => 'v2.2'
        ]
    ],

    "mail" => [
        "smtp" => "",
        "username" => "",
        "password" => "",
        "displayName" => ''
    ],

    "email_orders" => [
        "recipient" => ""
    ],

    "other" => [
        "timezone" => "Europe/Warsaw",
        "locale" => "pl_PL.UTF-8",
        "google-analytics" => [
            "enable" => true,
            "tracking-code" => ""
        ]
    ]

];

setlocale(LC_ALL, $config["other"]["locale"]);

// Definitions
define('ROOT_DIR', dirname($_SERVER['DOCUMENT_ROOT']) . "/");
define("INCLUDES_PATH", ROOT_DIR . 'app');
define("LIBRARIES_PATH", 'vendor');
define("ELEMENTS_PATH", INCLUDES_PATH . '/elements');
define("BASE_URL", $config["general"]["baseURL"]);
define("CONFIG_DIR", ROOT_DIR . 'config');

return $config;
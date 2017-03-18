<?php

class Menu
{

    public static function getHeader()
    {
        $icon = (isset(user()->icon)
            ? '<i class="fa fa-' . user()->icon . '"></i> '
            : '');

        return [
            Roles::Admin => [
                "_name" => "Admin",

                'Dodaj użytkownika' => [
                    'url' => route('profile::add'),
                    'icon' => 'plus-sign'],

                'Znajdź użytkownika' => [
                    'url' => route('profile::search'),
                    'icon' => 'search'],

                'Klasy' => "_divider",
                'Zarządzanie klasami' => [
                    'url' => '/classes/list',
                    'icon' => 'list-alt'],
                'Dodaj klasę' => [
                    'url' => route('class::create'),
                    'icon' => 'plus'],

                'Inne' => "_divider",
                'Logi' => [
                    'url' => '/admin/logs',
                    'icon' => 'time']
            ],

            Roles::Manager => [
                "_name" => "Globalne",

                'Zarządzaj menu' => [
                    'url' => route('manage::menu'),
                    'icon' => 'apple'],

                'Klasy' => "_divider",
                'Wyślij wiadomość' => [
                    'url' => route('manage::messenger'),
                    'icon' => 'envelope'],

                'Zamówienia klas' => [
                    'url' => '/classes/orders',
                    'icon' => 'shopping-cart'],

                'Zarządzanie klasami' => [
                    'url' => '/classes/list',
                    'icon' => 'list-alt']
            ],

            Roles::ClassCollector => [
                "_name" => "Klasowe",

                'Zamówienia klasy' => [
                    'url' => route('user::class::orders'),
                    'icon' => 'shopping-cart'],

                'Zarządzaj finansami' => [
                    'url' => route('user::class::money'),
                    'icon' => 'euro'],
            ],

            Roles::User => [
                "_name" => $icon . user()->getFullName() . ' (' . user()->balance . ' zł)',
                "_collapsed" => false,
                'Ustawienia' => route('user::settings'),
                'Kontakt' => [
                    'url' => route('contact'),
                    'if' => 'mobile',
                    'icon' => 'question-sign'
                ],
                'Wyloguj' => [
                    'url' => route('logout'),
                    'icon' => 'log-out'
                ],
                'Informacje' => '_divider',
                'Stan konta: ' . user()->balance . ' zł' => '#',
                'Rola: ' . getRoleName(user()->role) => '#'
            ]
        ];

    }

    public static function getMenu()
    {
        return [
            'Informacje' => '/',
            'Złóż zamówienie' => route('order'),
            'Historia zamówień' => route('history'),
            'Kontakt' => route('contact'),
            '<span class="glyphicon glyphicon-log-out"></span> Wyloguj' => route('logout'),
        ];
    }

    public static function toHideOnMobile()
    {
        return ['Kontakt', '<span class="glyphicon glyphicon-log-out"></span> Wyloguj'];
    }

    public static $app_menu = [
        'Informacje' => '/',
        'Zamów' => '/zamow',
        'Historia' => '/help',
    ];

}
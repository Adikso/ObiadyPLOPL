<?php
// Static

Router::add(new Route('info', '/', [
    "GET" => "InfoPageController::show"
], Roles::Guest));

Router::add(new Route('contact', '/help', [
    "GET" => "InfoPageController::showHelp"
], Roles::Guest));

Router::add(new Route('systemhistory', '/archives', [
    "GET" => "InfoPageController::showHistory"
], Roles::Guest));

Router::add(new Route('admin::logs', '/admin/logs', [
    "GET" => "LogsController::display"
], Roles::Admin));


// Authorization

Router::add(new Route('logout', '/logout', [
    "GET" => "AuthController::logout"
], Roles::User));

Router::add(new Route('login::facebook::connect', '/login/facebook/connect', [
    "GET" => "FacebookController::connect",
    "POST" => "FacebookController::connect"
], Roles::User));

Router::add(new Route('login::facebook', '/login/facebook', [
    "GET" => "FacebookController::callback",
    "POST" => "FacebookController::callback"
], Roles::Guest));

Router::add(new Route('login', '/login', [
    "GET" => "InfoPageController::show",
    "POST" => "AuthController::login"
], Roles::Guest));


Router::add(new Route('order', '/order', [
    "GET" => "OrderingController::show",
    "POST" => "OrderingController::action"
], Roles::User));

Router::add(new Route('history', '/history', [
    "GET" => "OrdersHistoryController::show",
    "POST" => "OrdersHistoryController::show"
], Roles::User));


Router::add(new Route('class::create', '/class/create', [
    "GET" => "ClassCreatorController::show",
    "POST" => "ClassCreatorController::create"
], Roles::Admin));

Router::add(new Route('user::class::orders', '/class/orders', [
    "GET" => "ClassOrdersController::showOrders",
    "POST" => "ClassOrdersController::showOrders"
], Roles::ClassCollector));

Router::add(new Route('user::class::money', '/class/money', [
    "GET" => "ClassOrdersController::showLiabilities",
    "POST" => "ClassOrdersController::updateLiabilities"
], Roles::ClassCollector));

Router::add(new Route('class::manage', '/class/{id}', [
    "GET" => "ClassController::showClassInformation",
    "POST" => "ClassController::update"
], Roles::Manager));

Router::add(new Route('class::orders', '/class/{id}/orders', [
    "GET" => "ClassOrdersController::showOrders",
    "POST" => "ClassOrdersController::showOrders"
], Roles::Manager));

Router::add(new Route('class::money', '/class/{id}/money', [
    "GET" => "ClassOrdersController::showLiabilities",
    "POST" => "ClassOrdersController::updateLiabilities"
], Roles::Manager));

Router::add(new Route('user::class::orders::format', '/class/orders/format/{format}/{from}', [
    "GET" => "ClassOrdersController::showOrdersInFormat"
], Roles::ClassCollector));

Router::add(new Route('class::orders::format', '/class/{id}/orders/format/{format}/{from}', [
    "GET" => "ClassOrdersController::showOrdersInFormat"
], Roles::Manager));

// Profile

Router::add(new Route('user::settings::password::change', '/user/settings/password/change', [
    "GET" => "UserProfileController::showChangePassword",
    "POST" => "UserProfileController::updatePassword"
], Roles::User));

Router::add(new Route('user::password::recovery', '/user/password/recovery', [
    "GET" => "UserProfileController::showPasswordRecovery",
    "POST" => "UserProfileController::actionPasswordRecovery"
], Roles::Guest));

Router::add(new Route('user::password::recovery::key', '/user/password/recovery/{key}', [
    "GET" => "UserProfileController::showPasswordRecovery",
    "POST" => "UserProfileController::actionPasswordRecovery"
], Roles::Guest));

Router::add(new Route('user::settings', '/user/settings', [
    "GET" => "UserProfileController::showSettings",
    "POST" => "UserProfileController::updateSettings"
], Roles::User));

Router::add(new Route('profile::add', '/user/add', [
    "GET" => "UserEditorController::show",
    "POST" => "UserEditorController::update"
], Roles::Admin));

Router::add(new Route('profile::search', '/user/search', [
    "GET" => "UserSearchController::show",
    "POST" => "UserSearchController::search"
], Roles::Admin));

Router::add(new Route('profile', '/user/{id}', [
    "GET" => "UserProfileController::show",
    "POST" => "UserProfileController::update"
], Roles::Admin));

Router::add(new Route('user::generatepasswordchange', '/user/{id}/resetpassword', [
    "GET" => "UserProfileController::resetPassword"
], Roles::Admin));

Router::add(new Route('profile::edit', '/user/{id}/edit', [
    "GET" => "UserEditorController::show",
    "POST" => "UserEditorController::update"
], Roles::Admin));


// Manager

Router::add(new Route('manage::menu', '/manage/menu', [
    "GET" => "MenuController::show",
    "POST" => "MenuController::update"
], Roles::Manager));

Router::add(new Route('manage::messenger', '/manage/messenger', [
    "GET" => "MessengerController::show",
    "POST" => "MessengerController::update"
], Roles::Manager));

Router::add(new Route('user::manage::messenger', '/manage/messenger/user/{id}', [
    "GET" => "MessengerController::show",
    "POST" => "MessengerController::update"
], Roles::Manager));

Router::add(new Route('class::manage::messenger', '/manage/messenger/class/{id}', [
    "GET" => "MessengerController::show",
    "POST" => "MessengerController::update"
], Roles::Manager));

Router::add(new Route('classes::list', '/classes/list', [
    "GET" => "ClassController::showClassesList"
], Roles::Manager));

Router::add(new Route('classes::orders', '/classes/orders', [
    "GET" => "ClassOrdersController::showAllClassesOrders",
    "POST" => "ClassOrdersController::showAllClassesOrders"
], Roles::Manager));

Router::add(new Route('classes::orders::export', '/classes/orders/export/{from}', [
    "GET" => "ClassOrdersController::showAsPlaintext"
], Roles::Manager));

Router::add(new Route('classes::orders::export', '/classes/orders/export', [
    "GET" => "ClassOrdersController::showAsPlaintext"
], Roles::Manager));

Router::add(new Route('classes::orders::export::pizza', '/classes/orders/export/pizza/{from}', [
    "GET" => "ClassOrdersController::showAsPlaintext"
], Roles::Manager));

Router::add(new Route('classes::orders::export::pizza', '/classes/orders/export/pizza', [
    "GET" => "ClassOrdersController::showAsPlaintext"
], Roles::Manager));
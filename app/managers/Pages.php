<?php


class Pages
{

    public static function setup()
    {
        Router::setup();
        require 'routes/web.php';
        require 'routes/api.php';
    }

    /**
     * Returns current request path
     *
     * @return null|RequestPath
     */
    public static function getCurrent()
    {
        return Router::getCollection()->getByURL(Input::get('p'));
    }

    /**
     * Returns current route id
     *
     * @return mixed
     */
    public static function getCurrentId()
    {
        return self::getCurrent()->getRoute()->getId();
    }

    /**
     * Loads page
     */
    public static function load()
    {
        $requestPath = Router::getCollection()->getByURL(Input::get('p'));

        if (is_null($requestPath)) {
            Debug::info('Controller: RequestPath is null');
            self::display404();
            return;
        }

        $minAccessLevel = array_search($requestPath->getRoute()->getMinRole(), Users::getRoles());
        $userAccessLevel = Users::getAccessLevel(user());

        if ($userAccessLevel > $minAccessLevel) {
            if (Users::getRoles()[$userAccessLevel] == Roles::Guest) {
                Alerts::show(new Alert(AlertType::Danger, null, "Zaloguj się, aby odwiedzić tą stronę"));
            } else {
                Alerts::show(new Alert(AlertType::Danger, null, "Nie masz wymaganych uprawnień do przeglądania tej strony"));
            }

            $controller = new InfoPageController();
            $controller->show();
            return;
        }

        foreach ($requestPath->getParameters() as $key => $value) {
            $_GET[$key] = $value;
            $_REQUEST[$key] = $value;
        }

        $targetMethod = Router::getMethod();

        if (is_null($targetMethod)) {
            Debug::info('Controller: Route Target method is null');
            self::display404();
            return;
        }

        $parts = explode('::', $targetMethod);

        $className = $parts[0];
        $methodName = $parts[1];

        if (!class_exists($className)) {
            Debug::info(sprintf('Controller: Class %s does not exist (%s)', $className, $targetMethod));
            self::display404();
            return;
        }

        $controller = new $className;

        if (!method_exists($controller, $methodName)) {
            Debug::info('Controller: Route Target method does not exist in Controller');
            self::display404();
            return;
        }

        if (!Input::has('csrf_token') && $_SERVER['REQUEST_METHOD'] === 'POST') {
            Debug::info('Anty CSRF Token was not present during that POST request');
        }

        Debug::info(sprintf('Controller: %s (%s)', $targetMethod, $requestPath->getRoute()->getId()));
        call_user_func([$controller, $methodName]);

        if (isset($_SESSION['data'])){
            Debug::info('Received redirect data:');
            Debug::info($_SESSION['data']);
            unset($_SESSION['data']);
        }
    }

    /**
     * Display error 404 page
     */
    public static function display404()
    {
        $controller = new InfoPageController();
        $controller->error404();
    }

}
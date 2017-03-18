<?php


class ClassOrdersController extends Controller
{

    public function showOrders()
    {
        $from = date('Y-m-d', strtotime('Today', time()));

        $classId = user()->classId;

        if (!empty(Input::getDate('from'))) {
            $from = Input::getDate('from');
        }

        if (Input::has('id') && is_numeric(Input::get('id'))) {
            $classId = Input::get('id');
        }

        $orders = Orders::getClassOrders($classId, $from);

        if (empty($orders)){
            Alerts::show(new Alert(AlertType::Warning, null, 'Brak zamówień na ten tydzień'));
        }

        echo $this->getTemplates()->render("pages/class/orders",
            ["title" => "Zamówienia klasy - Obiady PLOPŁ",
                "orders" => $orders,
                "from" => $from,
                "classId" => $classId]);
    }

    public function showAllClassesOrders()
    {
        $school_level = user()->getPermission("school_level");
        $classesList = Classes::getClasses($school_level);

        if (Pages::getCurrentId() === 'classes::orders::export') {
            echo OrdersService::generateReport($school_level);
            die();
        }

        // W poniedziałek wyświetlanie zacznie się od aktualnego dnia,
        // w inny dzień roboczy jako ostatni poniedziałek, w weekend jako następny poniedziałek

        $from = 'Last Monday';
        $weekday = date("N", time());

        if ($weekday == 1) {
            $from = 'Today';
        } else if ($weekday == 6 || $weekday == 7) {
            $from = 'Next Monday';
        }

        $from = date('Y-m-d', strtotime($from, time()));

        if ($date = Input::getDate('from')) {
            $from = urlencode($date);
        }

        $to = date('Y-m-d', strtotime($from . " + 5 days", time()));

        $classesOrders = [];

        foreach ($classesList as $class) {
            $orders = ClassService::countClassOrders($class, $from, $to);
            $classesOrders[$class->id] = $orders;
        }

        echo $this->getTemplates()->render("pages/classes/orders",
            ["classesOrders" => $classesOrders, "from" => $from, "to" => $to]);

    }

    public function showLiabilities()
    {
        if (Input::has('id')) {
            $classId = Input::get('id');
        } else {
            $classId = user()->classId;
        }

        $students = Classes::getStudents($classId);
        echo $this->getTemplates()->render("pages/class/money",
            ["title" => "Finanse klasy - Obiady PLOPŁ",
                "students" => $students]);

    }

    public function updateLiabilities()
    {
        if (Input::has('save')) {
            if (!checkCSRF()) {
                Alerts::show(new Alert(AlertType::Danger, "Formularz wygasł", "Stan konta nie został zmieniony. Spróbuj ponownie."));
                return;
            }

            foreach ($_POST as $key => $value) {
                $parts = explode("#", $key);

                if (sizeof($parts) != 2 || $parts[0] != "cost" || empty($value)) {
                    continue;
                }

                $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);

                // Should we make less queries?
                /** @var User $user */
                $user = User::find($parts[1]);
                $user->balance += $value;
                $user->update();
            }

            Alerts::show(new Alert(AlertType::Success, null, 'Zmiany zostały zapisane'));
        }

        $this->showLiabilities();
    }

    public function showAsPlaintext()
    {
        $from = date('Y-m-d', strtotime('Today', time()));

        if (!empty($date = Input::getDate('from'))) {
            $from = $date;
        }

        echo OrdersService::generateReport(user()->getPermission('school_level'), $from);
        die();
    }

    public function showOrdersInFormat()
    {
        $classId = user()->classId;

        if (Input::has('id') && is_numeric(Input::get('id'))) {
            $classId = Input::get('id');
        }

        switch (Input::get('format')) {
            case 'csv':
                header('Content-type: application/json; charset=UTF-8');
                echo ClassService::getCSV($classId, Input::getDate('from'));
                break;

            default:
                echo "Nieznany format";
                break;
        }

    }

}
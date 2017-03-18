<?php

class ClassController extends Controller
{

    public function showClassesList()
    {
        $school_level = user()->getPermission("school_level");
        $classes = Classes::getClasses($school_level);

        // Statistics
        $activatedExp = new Expression();
        $activatedExp->isNotNull('password')->different('password', '');

        $activeExp = new Expression();
        $monthBefore = date("Ymd", strtotime("-1 months"));
        $activeExp->greaterEqual('lastlogin', $monthBefore);

        $activeWeekExp = new Expression();
        $monthBefore = date("Ymd", strtotime("-1 week"));
        $activeWeekExp->greaterEqual('lastlogin', $monthBefore);

        $ordersAmount = Models::count(new Order());

        $allUsers = Models::count(new User());
        $activatedUsers = Models::countByExpression(new User(), $activatedExp);

        $activeUsers = Models::countByExpression(new User(), $activeExp);
        $activeWeekExp = Models::countByExpression(new User(), $activeWeekExp);

        echo $this->getTemplates()->render("pages/classes/list",
            ["classes" => $classes,
                "ordersAmount" => $ordersAmount,
                "allUsers" => $allUsers,
                "activatedUsers" => $activatedUsers,
                "activeUsers" => $activeUsers,
                "activeWeek" => $activeWeekExp]);
    }

    public function showClassInformation()
    {
        $students = [];
        $class = SchoolClass::find(Input::get('id'));

        if (user()->checkPermission('school_level', $class->owner)
            && !user()->checkPermission('school_level', SchoolLevel::ALL)
        ) {
            Alerts::show(new Alert(AlertType::Danger, "Brak uprawnień", "Nie możesz przeglądać klas z innego poziomu"));
        } else {
            $students = Classes::getStudents($class);
        }

        $ordersAmount = ClassService::countOrders($class);

        echo $this->getTemplates()->render("pages/class/information",
            ["class" => $class,
                "students" => $students,
                "ordersAmount" => $ordersAmount]);

    }

}
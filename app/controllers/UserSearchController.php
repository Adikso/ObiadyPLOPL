<?php


class UserSearchController extends Controller
{

    public function show()
    {
        $classes = Classes::getClasses(SchoolLevel::ALL);

        echo $this->getTemplates()->render("pages/user/search", [
            "classes" => $classes
        ]);
    }

    public function search()
    {
        $classes = Classes::getClasses(SchoolLevel::ALL);

        if (Input::has('search')) {

            $expression = new Expression();

            if (!Input::isEmpty('inputLogin')) {
                $expression->equals('login', Input::get('inputLogin'));
            }

            if (!Input::isEmpty('inputFirstname')) {
                $expression->equals('firstname', Input::get('inputFirstname'));
            }

            if (!Input::isEmpty('inputSecondname')) {
                $expression->equals('secondname', Input::get('inputSecondname'));
            }

            if (!Input::isEmpty('classesList')) {
                $expression->equals('classId', Input::get('classesList'));
            }

            if (!Input::isEmpty('rolesList')) {
                $expression->equals('role', Input::get('rolesList'));
            }

            if (!Input::isEmpty('inputBalance')) {
                $expression->equals('balance', Input::get('inputBalance'));
            }

            $results = [];

            if (count($expression->getExpressions()) > 0) {
                $expression->orderBy('secondname ASC')
                    ->orderBy('firstname ASC')
                    ->orderBy('role DESC');

                $results = User::findByExpression($expression);
            }

            if (empty($results)){
                Alerts::show(new Alert(AlertType::Warning, null, 'Nie znaleziono użytkowników spełniających podane kryteria'));
            }

            echo $this->getTemplates()->render("pages/user/search", [
                "classes" => $classes,
                "results" => $results
            ]);
        }

    }

}
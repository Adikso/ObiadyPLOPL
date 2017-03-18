<?php


class UserEditorController extends Controller
{

    public function show()
    {
        $userId = Input::get('id');

        if (!is_null(Input::get('update'))) {
            $userId = Input::get('update');
        }

        if (!is_null($userId)) {
            $user = User::find($userId);
        } else {
            $user = new User();
        }

        $classes = Classes::getClasses(SchoolLevel::ALL);

        echo $this->getTemplates()->render("pages/user/edit", ['user' => $user, 'classes' => $classes]);
    }

    public function update()
    {
        if (Input::has('update') || Input::has('add')) {
            if (!checkCSRF()) {
                Alerts::show(new Alert(AlertType::Danger, "Zablokowano niebezpieczną operacje", "Operacja została anulowana [CSRF]"));
                $this->show();
                return;
            }
        }

        $user = null;
        if (Input::has('update')) {
            $user = User::find(Input::get('id'));
        } else if (Input::has('add')) {
            $user = new User();
        }

        if (Input::has('update') || Input::has('add')) {
            $user->login = Input::get('inputLogin');

            if (!empty(Input::get('inputPassword'))){
                $user->password = Passwords::create(Input::get('inputPassword'));
            }

            $user->email = Input::get('inputEmail');
            $user->firstname = Input::get('inputFirstname');
            $user->secondname = Input::get('inputSecondname');
            $user->classId = Input::get('classesList');
            $user->role = Input::get('rolesList');
            $user->balance = Input::get('inputBalance');
            $user->icon = Input::get('inputIcon');
        }

        if (Input::has('update')) {
            $user->update();
            Alerts::show(new Alert(AlertType::Success, null, 'Użytkownik został zaktualizowany'));
        }

        if (Input::has('add')) {
            if (Input::isEmpty('inputLogin', 'inputFirstname', 'inputSecondname', 'classesList', 'rolesList')) {
                Alerts::show(new Alert(AlertType::Danger, null, "Tworzenie konta nieudane. Nie podano wymaganych danych"));
            } else {
                $id = $user->insert();
                $this->redirect(route('profile', ['id' => $id]));
            }
        }

        $this->show();
    }

}
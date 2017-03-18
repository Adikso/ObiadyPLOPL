<?php

class AuthController extends Controller
{

    public function login()
    {
        $result = Auth::handleLogin();

        if ($result) {
            $this->redirect("/");
        }

        Alerts::show(new Alert(AlertType::Danger, 'Logowanie nie powiodło się', 'Podane hasło lub login jest nieprawidłowy'));
        $controller = new InfoPageController();
        $controller->show();
    }

    public function logout()
    {
        session_destroy();

        if (Cookies::has('remember_me')) {
            $details = Auth::getAuthKey(Cookies::get('remember_me'));

            if (!empty($details)){
                Cookies::remove('remember_me');

                if ($details->type !== TokenType::Facebook) {
                    Auth::removeAuthKey(Cookies::get('remember_me'));
                }
            }


        }

        $this->redirect(route('info'));
    }

}
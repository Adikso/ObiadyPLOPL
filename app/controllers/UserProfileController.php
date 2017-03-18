<?php

class UserProfileController extends Controller
{

    public function show()
    {
        if (!is_numeric(Input::get('id'))) {
            $this->error('Nieprawidłowe id', 'Spróbuj ponownie');
            return;
        }

        $profile = UserProfileService::getUserProfile(Input::get('id'));

        if (is_null($profile)){
            $this->error('Nieprawidłowe id', 'Spróbuj ponownie');
            return;
        }

        /** @var User $user */
        $user = $profile['user'];

        $from = date('Y-m-d', strtotime('Last Monday', time()));
        $to = date('Y-m-d', strtotime('Next Sunday +1 week', time()));

        if (Input::getDate('from') AND Input::getDate('to')) {
            $from = Input::getDate('from');
            $to = Input::getDate('to');
        }

        $orders = Orders::getUserOrders($user, $from, $to);

        echo $this->getTemplates()->render("pages/user/profile",
            ["title" => "Profil " . $user->getFullName(),
                "profile" => $profile,
                "orders" => $orders,
                "from" => $from,
                "to" => $to]);

    }

    public function update()
    {
        if (Input::has('removeToken')) {
            if (!checkCSRF()) {
                Alerts::show(new Alert(AlertType::Danger, "Formularz wygasł", "Spróbuj ponownie"));
                return;
            }

            if (Input::has('tokenId')) {
                Token::deleteById(Input::get('tokenId'));
            }
        }

        if (Input::has('loginas')) {
            $_SESSION['id'] = Input::get('id');
            $this->redirect(config('general.baseURL'));
        }

        $this->show();
    }

    public function resetPassword()
    {
        $token = new Token();
        $token->userId = Input::get('id');
        $token->type = TokenType::PasswordReminder;
        $token->devicename = 'Email Access';
        $token->additional = 'HIDDEN';

        $authKey = Auth::addAuthKey($token);
        Alerts::show(new Alert(AlertType::Success, 'Wygenerowano', (SiteAccessControl::isHttps() ? "https" : "http") . ':' . config('general.baseURL') . substr(route('user::password::recovery::key', ['key' => $authKey]), 1)));

        $this->show();
    }

    public function showSettings()
    {
        echo $this->getTemplates()->render("pages/user/settings",
            ["title" => "Ustawienia - Obiady PLOPŁ",
                "profile" => UserProfileService::getUserProfile()]);
    }

    public function updateSettings()
    {
        if (Input::has('changeemail')) {

            if (!checkCSRF()) {
                Alerts::show(new Alert(AlertType::Danger, "Formularz wygasł", "Spróbuj ponownie"));
                return;
            }

            if (!Input::has('newemail')) {
                Alerts::show(new Alert(AlertType::Danger, "Nie można zmienić adresu Email", "Nie podano adresu email"));
            } else {
                $email = filter_var(Input::get('newemail'), FILTER_VALIDATE_EMAIL);

                if (!preg_match("/^[^@]*@[^@]*\.[^@]*$/", $email)) {
                    Alerts::show(new Alert(AlertType::Danger, "Nie można zmienić adresu Email", "Adres jest nieprawidłowy"));
                } else {
                    user()->email = $email;
                    user()->update();

                    Alerts::show(new Alert(AlertType::Success, null, "Adres email został zmieniony"));
                }
            }

        }

        if (Input::has('removeToken')) {
            if (!checkCSRF()) {
                Alerts::show(new Alert(AlertType::Danger, "Formularz wygasł", "Spróbuj ponownie"));
                return;
            }

            if (Input::has('tokenId')) {
                Token::deleteById(Input::get('tokenId'));
            }
        }

        $this->showSettings();
    }

    public function showChangePassword()
    {
        echo $this->getTemplates()->render("pages/user/changepassword");
    }

    public function updatePassword()
    {
        if (Input::has('change')) {
            if (!checkCSRF()) {
                Alerts::show(new Alert(AlertType::Danger, "Zablokowano niebezpieczną operacje", "Hasło nie zostało zmienione"));
                $this->showChangePassword();
                return;
            }

            if (!Input::isEmpty('old')) {
                if (!Input::isEmpty('new', 'new_confirm') && Input::get('new') === Input::get('new_confirm')) {

                    if (strlen(Input::get('new')) < 5) {
                        Alerts::show(new Alert(AlertType::Danger, 'Zmiana hasła nie powiodła się',
                            'Minimalna długość hasła to 5 znaków. Najlepsze hasło to takie,
                             które łatwo zapamiętać a jednocześnie jest długie. 
                             Pomyśl nad stworzeniem zdania, które będzie twoim hasłem. 
                             Łatwo je zapamiętać, a mimo to jego długość będzie ogromną zaletą. 
                             Nie, żeby system obiadowy to był serious business, ale może ci się ta wiedza przyda. 
                             <b>Przykład: </b> NieJemObiadówBoSąSłabe'));
                    } else {
                        if (Auth::login(user()->login, Input::get('old'))) {
                            user()->password = Passwords::create(Input::get('new'));
                            user()->update();

                            Alerts::show(new Alert(AlertType::Success, null, 'Zmiana hasła powiodła się.'));
                            Log::info('CHANGE_PASSWORD', 'SUCCESS ' . user()->login);
                        } else {
                            Alerts::show(new Alert(AlertType::Danger, 'Zmiana hasła nie powiodła się', 'Stare hasło jest nieprawidłowe'));
                            Log::info('CHANGE_PASSWORD', 'INVALID OLD PASSWORD ' . user()->login);
                        }
                    }

                } else {
                    Alerts::show(new Alert(AlertType::Danger, 'Zmiana hasła nie powiodła się', 'Nie podano nowego hasła lub jego potwierdzenia'));
                }
            } else {
                Alerts::show(new Alert(AlertType::Danger, 'Zmiana hasła nie powiodła się', 'Nie podano starego hasła'));
            }
        }

        $this->showChangePassword();
    }

    public function showPasswordRecovery($nextStep = false)
    {
        if (Input::has('key') && !Input::has('change')) {
            $token = Auth::getAuthKey(Input::get('key'));
            if ($token != null && $token->type === TokenType::PasswordReminder) {
                $nextStep = true;
            } else {
                Alerts::show(new Alert(AlertType::Danger, null, 'Podany klucz jest niepoprawny lub został już użyty'));
            }
        }

        echo $this->getTemplates()->render("pages/user/passwordrecovery", [
            'nextStep' => $nextStep,
            'key' => Input::get('key')
        ]);
    }

    public function actionPasswordRecovery()
    {
        if (Input::has('key')) {
            $token = Auth::getAuthKey(Input::get('key'));

            if ($token != null && $token->type === TokenType::PasswordReminder) {
                if (strlen(Input::get('password')) < 5) {
                    Alerts::show(new Alert(AlertType::Danger, 'Zmiana hasła nie powiodła się',
                        'Minimalna długość hasła to 5 znaków. Najlepsze hasło to takie,
                             które łatwo zapamiętać a jednocześnie jest długie. 
                             Pomyśl nad stworzeniem zdania, które będzie twoim hasłem. 
                             Łatwo je zapamiętać, a mimo to jego długość będzie ogromną zaletą. 
                             Nie, żeby system obiadowy to był serious business, ale może ci się ta wiedza przyda. 
                             <b>Przykład: </b> NieJemObiadówBoSąSłabe'));
                } else {
                    $user = User::find($token->userId);

                    if ($user == null) {
                        die();
                    }

                    $user->password = Passwords::create(Input::get('password'));
                    $user->update();

                    Alerts::show(new Alert(AlertType::Success, null, 'Zmiana hasła powiodła się.'));
                    Log::info('CHANGE_PASSWORD', 'SUCCESS ' . $user->login);

                    $token->delete();
                    Auth::loginWithoutCredentials($user->id);
                    $this->redirect(route('info'));
                }
            } else {
                Alerts::show(new Alert(AlertType::Danger, null, 'Podany klucz jest niepoprawny lub został już użyty'));
            }
        } elseif (Input::has('recover')) {

            if (!Input::isEmpty('username')) {
                $expression = new Expression();
                $expression->equals('login', Input::get('username'))
                    ->_or()->equals('email', Input::get('username'));

                $user = User::findByExpression($expression, false);

                if ($user != null) {
                    $token = new Token();
                    $token->type = TokenType::PasswordReminder;
                    $token->devicename = "Przypomnienie hasła";
                    $token->userId = $user->id;
                    $authKey = Auth::addAuthKey($token);

                    $message = new EmailMessage();
                    $message->to($user->email);
                    $message->from(config('mail.username'), config('general.siteTitle') . ' - Automat');

                    $message->title(config('general.siteTitle') . ' - Przypomnienie hasła');
                    $message->text(sprintf(
                        "Ktoś chce zresetować hasło do twojego konta\n
                        Jeżeli to nie ty to zignoruj tą wiadomość\n
                        Odwiedź poniższy link aby zresetować hasło:\n
                        https:%s%s\n\n
                        Twoja nazwa użytkownika: %s\n\n
                        W razie problemów wyślij odpowiedź na tą wiadomość",

                        config('general.baseURL'),
                        substr(route('user::password::recovery::key',
                        ['key' => $authKey]), 1), $user->login));

                    Log::info("PASSWORD_CHANGE_REQUEST", $user->login);

                    $status = Email::sendEmail($message);
                    if (!$status) {
                        Alerts::show(new Alert(AlertType::Danger, 'Nie udało się przypomnieć hasła', 'Email nie mógł zostać wysłany. Spróbuj ponownie!'));
                    } else {
                        Alerts::show(new Alert(AlertType::Success, 'Sukces', 'Przypomnienie zostało wysłane na twój adres email'));
                    }

                } else {
                    Alerts::show(new Alert(AlertType::Danger, null, 'Użytkownik o podanym loginie lub emailu nie istnieje'));
                }
            } else {
                Alerts::show(new Alert(AlertType::Danger, null, 'Nie podano nazwy użytkownika'));
            }

        }

        $this->showPasswordRecovery();
    }

}
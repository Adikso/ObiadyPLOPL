<?php


class MessengerController extends Controller
{

    private function getRecipient()
    {
        $to = null;
        $pageId = Pages::getCurrentId();

        if ($pageId === 'user::manage::messenger') {
            $to = Input::get('id');
        } elseif ($pageId === 'class::manage::messenger') {
            $to = "class_" . Input::get('id');
        } elseif (Input::has('to')) {
            $to = Input::get('to');
        }

        return $to;
    }

    public function show()
    {
        $messages = Messenger::get(false);

        echo $this->getTemplates()->render("pages/manage/messenger",
            ['messages' => $messages, 'to' => $this->getRecipient()]);
    }

    public function update()
    {
        if (Input::has('sendmsg') || Input::has('remove')) {
            if (!checkCSRF()) {
                Alerts::show(new Alert(AlertType::Danger, "Formularz wygasł", "Spróbuj ponownie"));
                $this->show();
                return;
            }
        }

        if (Input::has('sendmsg')) {
            $logins = explode(",", $this->getRecipient());

            if (is_array($logins)) {
                foreach ($logins as $login) {
                    Messenger::send(new Message($login, Input::get('message'), Input::get('url')));
                }
            } else {
                Messenger::send(new Message($this->getRecipient(), Input::get('message'), Input::get('url')));
            }

            Alerts::show(new Alert(AlertType::Success, null, "Wysłano wiadomość"));
        }

        if (Input::has('remove')) {
            foreach (Input::get('remove') as $value) {
                Messenger::remove($value);
            }
        }
        $this->show();
    }

}
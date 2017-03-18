<?php


class FacebookController extends Controller
{

    public function callback()
    {
        $fb = Facebook::getFacebook();
        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            Alerts::show(new Alert(AlertType::Danger, 'Facebook Callback', $e->getMessage()));
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            Alerts::show(new Alert(AlertType::Danger, 'Facebook Callback', $e->getMessage()));
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                Alerts::show(new Alert(AlertType::Danger, 'Facebook Callback', 'Musisz zezwolić aplikacji na dostęp do podstawowych informacji odnośnie twojego konta'));
            } else {
                header('HTTP/1.0 400 Bad Request');
                Alerts::show(new Alert(AlertType::Danger, 'Facebook Callback', 'Bad request'));
            }
        } else {
            $fb->setDefaultAccessToken((string)$accessToken);

            try {
                $response = $fb->get('/me');
                $userNode = $response->getGraphUser();
            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                Debug::$debugBar['exceptions']->addException($e);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                Debug::$debugBar['exceptions']->addException($e);
            }

            $token = Token::findByValue('additional', $userNode->getId());

            if (!empty($token)) {
                $token->last = date("Y-m-d H:i:s", time());
                $token->update();

                $_SESSION['obiady_fb_token'] = (string)$accessToken;

                Auth::loginWithoutCredentials($token->userId);
            } else {
                if (Users::isLoggedIn()) {
                    Facebook::addFacebookAuthKey(user()->id, $userNode->getId(), $accessToken->getValue());
                    $_SESSION['obiady_fb_token'] = (string)$accessToken;

                    Alerts::show(new Alert(AlertType::Success, null, 'Twoje konto FB zostało powiązane z kontem obiadowym'));
                } else {
                    Alerts::show(new Alert(AlertType::Danger, null, 'Nie posiadasz konta obiadowego powiązanego do konta FB. Po zalogowaniu możesz powiązać je w ustawieniach'));
                }
            }
        }

        $this->redirect(route('info'), ['alerts' => Alerts::getAlerts()]);
    }

    public function connect()
    {
        $this->redirect(Facebook::getLoginURL());
    }

}
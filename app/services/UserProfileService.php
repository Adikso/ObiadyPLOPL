<?php

class UserProfileService
{

    public static function getUserProfile($userId = null)
    {
        $user = user();

        if (!is_null($userId) && user()->id !== $userId) {
            $user = User::find($userId);
        }

        if (!$user){
            return null;
        }

        $class = SchoolClass::find($user->classId);
        $tokens = Tokens::getUserTokens($user);

        $userProfile = [
            'user' => $user,
            'class' => $class,
            'tokens' => $tokens,
            'facebook' => self::getFacebookProfile()
        ];

        return $userProfile;
    }

    public static function getFacebookProfile()
    {
        $fb = Facebook::getFacebook();

        if (isset($_SESSION['obiady_fb_token'])) {
            $fb->setDefaultAccessToken($_SESSION['obiady_fb_token']);

            try {
                $response = $fb->get('/me');
                $userNode = $response->getGraphUser();

                return $userNode;
            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                Debug::$debugBar['exceptions']->addException($e);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                Debug::$debugBar['exceptions']->addException($e);
            }
        }

        return null;
    }

}
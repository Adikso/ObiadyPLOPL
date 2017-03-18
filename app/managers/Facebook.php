<?php

class Facebook
{

    /**
     * Creates new Facebook instance
     *
     * @return \Facebook\Facebook
     */
    public static function getFacebook()
    {
        return new Facebook\Facebook([
            'app_id' => config('login.facebook.app_id'),
            'app_secret' => config('login.facebook.app_secret'),
            'default_graph_version' => config('login.facebook.default_graph_version'),
        ]);
    }

    /**
     * Adds Facebook related authorization token to database
     *
     * @param $user
     * @param $fb_user
     * @param $authKey
     */
    public static function addFacebookAuthKey($user, $fb_user, $authKey)
    {
        $token = new Token();
        $token->userId = $user;
        $token->authKey = $authKey;
        $token->devicename = 'Facebook';
        $token->type = 'FACEBOOK';
        $token->additional = $fb_user;

        Auth::addAuthKey($token);
    }

    /**
     * Returns Facebook token
     *
     * @param $userId
     * @return mixed
     */
    public static function getFacebookAuthKey($userId)
    {
        $token = Token::findByValue('additional', $userId);
        return $token->authKey;
    }

    /**
     * Returns facebook url for login purposes
     *
     * @return string
     */
    public static function getLoginURL()
    {
        $fb = self::getFacebook();

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email'];

        // TODO: Use route() ?
        $loginUrl = $helper->getLoginUrl((SiteAccessControl::isHttps() ? "https" : "http") . ':' . config('general.baseURL') . 'login/facebook', $permissions);

        return $loginUrl;
    }

}
<?php

class Auth
{

    /**
     * Handles login validation. Sets remember me key if requested
     *
     * Checks for authkey in password field
     *
     * @return bool
     */
    public static function handleLogin()
    {
        if (!Input::has('login', 'password')) {
            return false;
        }

        $login = Input::get('login');
        $password = Input::get('password');

        Tokens::cleanExpired();

        if (!self::login($login, $password)) {
            Log::info(LogType::Login, "User $login tried to login with invalid password. IP: " . getClientIp());
            return false;
        }

        if (Input::has('remember_me')) {
            self::setRememberMe();
            Log::info(LogType::Login, "User $login logged in successful and set rememberme cookie. IP: " . getClientIp());
        } else {
            Log::info(LogType::Login, "User $login logged in successful. IP: " . getClientIp());
        }

        return true;
    }

    /**
     * Makes login attempt
     *
     * It updates password to better algorithm
     * If recovery/activation token is provided as password
     * it redirects user to activation page
     *
     * @param $login
     * @param $password
     * @return bool
     */
    public static function login($login, $password)
    {
        if (empty($login) || empty($password)) {
            return false;
        }

        // Redirect to password change if authkey is given as password
        if (!is_null($userId = self::verifyKey($password, TokenType::PasswordReminder))){
            redirect(route('user::password::recovery::key', ['key' => $password]));
        }

        $loginExpression = new Expression();
        $loginExpression->equals('login', $login)
            ->_or()
            ->equals('email', $login);

        $expression = new Expression();
        $expression->add($loginExpression)
            ->different('role', Roles::Removed);

        /* @var User $user */
        $user = User::findByExpression($expression, false);

        // User does not exist
        if (!$user) {
            return false;
        }

        $db_hash = $user->password;

        // Safe password comparison and update
        if (Legacy::verifyAndUpdate($password, $db_hash, $user)
            || Passwords::verify($password, $db_hash)
        ) {

            $_SESSION['id'] = $user->id;
            self::updateLastLogin($user);

            return true;
        }

        return false;
    }


    /**
     * Login user without providing credentials
     *
     * Last login will be updated
     *
     * @param $id
     */
    public static function loginWithoutCredentials($id){
        $_SESSION['id'] = $id;

        $user = User::find($id);
        self::updateLastLogin($user);
    }

    /**
     * Handle automatic login using 'remember_me' cookie
     *
     * @return bool
     */
    public static function autoLogin()
    {
        if (!Cookies::has('remember_me') || !is_null(user())) {
            return false;
        }

        $expirationExpression = new Expression();
        $expirationExpression->greaterEqual('expire', time());

        $additionalExpression = new Expression();
        $additionalExpression->different('additional', 'HIDDEN')
            ->_or()
            ->isNull('additional');

        $expression = new Expression();
        $expression->equals('authKey', Cookies::get('remember_me'))
            ->add($expirationExpression)
            ->add($additionalExpression);

        /* @var Token $token */
        $token = Token::findByExpression($expression, false);

        if ($token) {
            $_SESSION['id'] = $token->userId;
            self::updateLastLogin(user());

            $token->last = date("Y-m-d H:i:s", time());
            $token->update();

            return true;
        }

        return false;
    }

    /**
     * Sets remember me key for automatic login
     * Valid for 30 days
     */
    public static function setRememberMe()
    {
        $tokenData = [
            'userId' => user()->id,
            'type' => TokenType::RememberMe,
            'devicename' => Device::getBrowser()['name']
        ];

        $authKey = self::addAuthKey($tokenData);
        Cookies::create('remember_me', $authKey, 30);
    }

    /**
     * Adds authentication key to database
     *
     * Default expiration time is 1 month
     *
     * @param $tokenData - see Token class
     * @return string - key of added authtoken
     */
    public static function addAuthKey($tokenData)
    {
        if ($tokenData instanceof Token){
            $token = $tokenData;
        }else{
            $token = new Token($tokenData);
        }


        if (is_null($token->authKey)) {
            $token->authKey = generateHash();
        }

        if (is_null($token->expire)) {
            $token->expire = date('Y-m-d H:i:s', strtotime("+1 months", time()));
        }

        $token->useragent = $_SERVER['HTTP_USER_AGENT'];
        $token->last = date("Y-m-d H:i:s", time());

        $token->insert();

        return $token->authKey;
    }

    /**
     * Verifies authentication key
     *
     * Returns user id if key is valid
     *
     * @param string $key
     * @param TokenType|string $type
     * @return integer - owner (user) id
     */
    public static function verifyKey($key, $type = TokenType::PasswordReminder)
    {
        $expireExpression = new Expression();
        $expireExpression->greaterEqual('expire', time())->_or()->isNull('expire');

        $expression = new Expression();
        $expression->equals('authKey', $key)
            ->add($expireExpression)
            ->equals('type', $type);

        /* @var Token $token */
        $token = Token::findByExpression($expression, false);

        if ($token != null) {
            return $token->userId;
        }

        return null;
    }

    /**
     * Returns Token with provided key
     *
     * @param String $authKey
     * @return DatabaseModel|Token
     */
    public static function getAuthKey($authKey)
    {
        return Token::findByValue('authKey', $authKey);
    }

    /**
     * Removes Token with provided key
     *
     * @param String $authKey
     */
    public static function removeAuthKey($authKey)
    {
        $token = self::getAuthKey($authKey);
        $token->delete();
    }

    /**
     * Update lastlogin field in database to current time
     *
     * @param User $user
     */
    private static function updateLastLogin($user)
    {
        $user->lastlogin = date("Y-m-d H:i:s", time());
        $user->update();
    }

    /**
     * Save new user to database
     *
     * @param $userData
     */
    public static function register($userData)
    {
        $user = new User($userData);
        $user->insert();
    }

    /**
     * Removes user
     *
     * User is not permanently removed from the database but hidden
     *
     * @param User $user
     */
    public static function safeRemove($user)
    {
        $user->role = Roles::Removed;
        $user->update();
    }

}

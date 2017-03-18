<?php

class UserService
{

    public function logout()
    {
        session_destroy();

        if (Input::has('remember_me')) {
            /* @var Token $token */
            $token = Token::findByValue('authKey', Cookies::get('remember_me'));

            if ($token->type !== TokenType::Facebook) {
                $token->delete();
            }

            Cookies::remove('remember_me');
        }

        redirect('/');
    }

}
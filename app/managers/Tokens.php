<?php

class Tokens
{

    /**
     * Returns all user tokens
     *
     * @param $user
     * @return DatabaseModel[]
     */
    public static function getUserTokens($user)
    {
        return Token::findAllByValue('userId', $user->id);
    }

    /**
     * Removes all expired tokens from database
     */
    public static function cleanExpired()
    {
        $expression = new Expression();
        $expression->lesserEqual('expire', time());

        Models::deleteByExpression(new Token(), $expression);
    }

    /**
     * Extends the period of token validity
     *
     * @param $token
     * @param $toDate
     */
    public static function extend($token, $toDate)
    {
        $token->expire = $toDate;
        $token->update();
    }

}
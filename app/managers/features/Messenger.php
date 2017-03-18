<?php

class Messenger
{

    /**
     * Sends message
     *
     * @param Message $message
     */
    public static function send(Message $message)
    {
        $message->insert();
    }

    /**
     * Permanently remove message
     *
     * @param $id
     */
    public static function remove($id)
    {
        Message::deleteById($id);
    }

    /**
     * Retrives messages destined for specified user
     *
     * @param bool|User $user
     * @return DatabaseModel[]
     */
    public static function get($user)
    {
        $expression = null;

        if (is_null($user)) {
            $expression = new Expression();
            $expression->in('target',
                [RecipientType::Everybody, RecipientType::Guest]);

        } else if ($user !== false) {
            $expression = new Expression();
            $expression->in('target',
                [RecipientType::LoggedIn,
                    RecipientType::Everybody,
                    'class_' . $user->classId,
                    $user->id, $user->login, $user->email, $user->role]);
        }

        if (is_null($expression)) {
            return Message::all();
        }

        return Message::findByExpression($expression);
    }

}

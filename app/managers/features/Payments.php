<?php

class Payments
{

    /**
     * Charges user with a cost of dinner
     *
     * If $amount is not given it by default charges user
     * with cost from configuration file
     *
     * @param $user
     * @param null $amount
     */
    public static function charge($user, $amount = null)
    {
        $amount = is_null($amount) ? config('orders.cost') : $amount;

        $user->balance -= $amount;
        $user->update();
    }

    /**
     * Refunds cost of dinner
     *
     * If $amount is not given it by default refund user
     * with cost from configuration file
     *
     * @param $user
     * @param null $amount
     */
    public static function refund($user, $amount = null)
    {
        $amount = is_null($amount) ? config('orders.cost') : $amount;
        self::charge($user, -abs($amount));
    }

    /**
     * Shortcut for config('orders.cost')
     * @return int
     */
    public static function getCost(){
        return config('orders.cost');
    }

}
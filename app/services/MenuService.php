<?php


class MenuService
{

    public static function deleteDish($date)
    {

        $dishes = Dish::findAllByValue('date', $date);

        foreach ($dishes as $dish) {

            // TODO: This can be optimized
            $orders = Order::findAllByValue('dish', $dish->id);
            foreach ($orders as $order) {
                $user = User::find($order->userId);
                Payments::refund($user);
            }

            Order::deleteByValue('dish', $dish->id);
            $dish->delete();

        }
    }

    public static function disableDay($date)
    {
        Database::query('UPDATE menu SET status = 
        (CASE 
            WHEN status IS NULL THEN "LOCKED"
            WHEN status = "UNLOCKED" THEN NULL
        END) WHERE date = :date', [":date" => $date]);
    }

    public static function enableDay($date)
    {
        Database::query('UPDATE menu SET status = 
        (CASE 
            WHEN status IS NULL THEN "UNLOCKED" 
            WHEN status = "LOCKED" THEN NULL
        END) WHERE date = :date', [":date" => $date]);
    }

}
<?php

class ClassService
{

    /**
     * Counts amount of all placed orders
     *
     * @param $class
     * @return mixed
     */
    public static function countOrders($class)
    {
        if ($class instanceof SchoolClass) {
            $class = $class->id;
        }

        $expression = new Expression();
        $expression->setColumns([['COUNT(*)' => 'amount']])
            ->leftJoin('menu', 'orders.dish', 'menu.id')
            ->leftJoin('users', 'orders.userId', 'users.id')
            ->equals('users.classId', $class);

        $orders = Order::findByExpression($expression, false);

        return $orders['amount'];
    }

//    public static function countClassesOrders($from, $to){
//        $expression = new Expression();
//        $expression->setColumns(['users.classId', 'classes.year', 'classes.class', 'menu.date', ['SUM(menu.type="MEAT")' => 'MEAT'], ['SUM(menu.type="VEGE")' => 'VEGE'], ['SUM(menu.type="PIZZA")' => 'PIZZA']])
//            ->leftJoin('users', 'orders.userId', 'users.id')
//            ->leftJoin('menu', 'orders.dish', 'menu.id')
//            ->leftJoin('classes', 'users.classId', 'classes.id')
//            ->between('menu.date', [':from', $from], [':to', $to])
//        ->groupBy('menu.date')->groupBy('classId');
//
//        $orders = Order::findByExpression($expression);
//
//        return group($orders, 'classId');
//    }

    // TODO: Replace this. Too many queries
    public static function countClassOrders($class, $from, $to)
    {
        $bind = [
            ':from' => $from,
            ':to' => $to,
            ':class' => $class->id
        ];

        $stmt = Database::query('SELECT menu.date,SUM(menu.type="MEAT") AS MEAT, SUM(menu.type="VEGE") AS VEGE, SUM(menu.type="PIZZA") AS PIZZA FROM orders LEFT JOIN users ON orders.userId = users.id LEFT JOIN menu ON orders.dish = menu.id WHERE (DATE(menu.date) >= :from AND DATE(menu.date) < :to) AND users.classId = :class GROUP BY menu.date', $bind);
        $stmt_pizza = Database::query('SELECT pizza.ingredients, COUNT(*) AS amount FROM pizza LEFT JOIN orders ON orders.pizza = pizza.id LEFT JOIN users ON orders.userId = users.id LEFT JOIN classes ON users.classId = classes.id LEFT JOIN menu ON menu.id = orders.dish WHERE classes.id = :class AND (DATE(menu.date) >= :from AND DATE(menu.date) < :to) GROUP BY pizza.id', $bind);
        $amount = [];

        foreach ($stmt as $value) {
            $amount[$value['date']]["MEAT"] = intval($value['MEAT']);
            $amount[$value['date']]["VEGE"] = intval($value['VEGE']);
            $amount[$value['date']]["PIZZA"] = intval($value['PIZZA']);
        }

        $classOrders = [
            "class" => $class,
            "amounts" => $amount
        ];

        foreach ($stmt_pizza as $pizza) {
            $classOrders['pizza'][] = ["ingredients" => $pizza['ingredients'], "amount" => $pizza['amount']];
        }

        return $classOrders;
    }

    /**
     * Returns all class orders in CSV format
     *
     * @param $classId
     * @param $from
     * @return string
     */
    public static function getCSV($classId, $from)
    {
        $orders = Orders::getClassOrders($classId, $from);
        $csv = "";

        foreach ($orders as $date => $group) {
            $dtime = new DateTime($date);
            $dayname = getTranslatedDayName($dtime->format("l"));

            $csv .= $dayname . " " . $date . PHP_EOL;

            foreach ($group as $order) {
                $description = $order['type'] == 'PIZZA' ? $order['ingredients'] : $order['description'];

                if ($order['type'] == "PIZZA") {
                    $csv .= sprintf("%s %s;%s;%s" . PHP_EOL, $order['firstname'], $order['secondname'], $description, getTypeName($order['type']));
                } else {
                    $csv .= sprintf("%s %s;%s;%s" . PHP_EOL, $order['firstname'], $order['secondname'], $description, getTypeName($order['type']));
                }
            }
            $csv .= PHP_EOL;
        }

        return $csv;
    }

}
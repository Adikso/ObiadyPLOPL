<?php

class Orders
{

    /**
     * Place an order and charge user
     *
     * Both $pizza and $user param can be provided as Models or their id
     *
     * @param int|Dish $dish
     * @param null Pizza|int $pizza
     * @param null User|int $user
     *
     * @return bool
     */
    public static function order($dish, $pizza = null, $user = null)
    {
        $date = date("Y-m-d H:i:s", time());

        if (!self::valid($dish)) {
            return false;
        }

        if ($dish instanceof Dish) {
            $dish = $dish->id;
        }

        if ($pizza instanceof Pizza) {

            // Creates pizza if do not exist
            if (!isset($pizza->id)) {
                $pizza = $pizza->insert();
            } else {
                $pizza = $pizza->id;
            }

        }

        if (is_null($user)) {
            $user = user();
        }

        $order = new Order($date, $dish, $user->id, $pizza);
        $order->insert();

        Payments::charge(user());

        Log::info(LogType::Order, json_encode($order));
        return true;
    }

    /**
     * Verifies if it is possible to place an order
     *
     * Checks deadlines and if it is possible to join pizza
     *
     * @param int $dish
     * @param null $pizza
     * @return bool
     */
    public static function valid($dish, $pizza = null)
    {
        $dish = Dish::find($dish);

        $isMeetingDeadline = Rules::canOrder($dish->date) || $dish->status == 'UNLOCKED';
        $pizza = (!is_null($pizza) ? self::canOrderPizza($pizza) : true);

        if (!$isMeetingDeadline) {
            Alerts::show(new Alert(AlertType::Danger, 'Nieudane zamówienie', 'Opcja nie istnieje lub czas na zamawianie minął'));
        } else if (!$pizza) {
            Alerts::show(new Alert(AlertType::Danger, 'Nieudane zamówienie', 'Ktoś ubiegł cię przed dołączeniem do połówki pizzy. Zamów pizze jeszcze raz.'));
        }

        return $isMeetingDeadline && $pizza;
    }

    /**
     * Verifies if it is possible to join pizza
     *
     * Single pizza can be ordered only by up to 2 users
     *
     * @param Pizza|int $pizza
     * @return bool
     */
    public static function canOrderPizza($pizza)
    {
        if ($pizza instanceof Pizza) {
            $pizza = $pizza->id;
        }

        $expression = new Expression();
        $expression->setColumns(['COUNT(*) as amount'])
            ->leftJoin('orders', 'id', 'pizza')
            ->leftJoin('users', 'orders.userId', 'users.id')
            ->equals('pizza', $pizza);

        $result = Pizza::findByExpression($expression, false);

        return (!empty($result) && $result['amount'] < 2);
    }

    /**
     * Cancel order placement
     *
     * @param $date
     * @param User|null $user
     */
    public static function cancel($date, User $user = null)
    {

        if (is_null($user)) {
            $user = user();
        }

        $expression = new Expression();

        $expression->setColumns(['orders.id', 'pizza'])
            ->leftJoin('orders', 'dish', 'id')
            ->leftJoin('users', 'orders.id', 'users.id')
            ->leftJoin('pizza', 'pizza.id', 'orders.pizza')
            ->equals('menu.date', $date)
            ->equals('userId', $user->id);

        $data = Dish::findByExpression($expression, false);

        if (!empty($data)) {
            if ($data['pizza'] !== null) {

                $pizzasExp = new Expression();
                $pizzasExp->equals('orders.pizza', $data['pizza']);

                $pizzas = Order::findByExpression($pizzasExp);

                if (count($pizzas) === 1) {
                    Pizza::deleteById($data['pizza']);
                }
            }

            Order::deleteById($data['id']);

            Payments::refund($user);
        }

        Log::info(LogType::Cancel, json_encode([$expression->generateBind(), $data]));
    }

    /**
     * Retrives menu from database
     *
     * If $user param is provided it returns already placed orders
     *
     * @param $from
     * @param $to
     * @param User|null $user
     * @return DatabaseModel|DatabaseModel[]
     */
    public static function getMenu($from, $to, User $user = null)
    {
        $expression = new Expression();
        $expression->between('menu.date', [':from', $from], [':to', $to])
            ->orderBy('menu.date ASC')->orderBy('type ASC');

        $columns = ['menu.id', 'menu.date', 'description', 'type', 'status'];

        if (!is_null($user)) {
            $joinExpression = new Expression();
            $joinExpression->equals('userId', $user->id);

            $expression->leftJoin('orders', 'dish', 'id', $joinExpression)
                ->leftJoin('pizza', 'id', 'orders.pizza');

            array_push($columns, 'userId', 'pizza', 'ingredients');
        }

        $expression->setColumns($columns);

        Models::$fetchAsClass = false;
        $dishes = Dish::findByExpression($expression);
        Models::$fetchAsClass = true;

        return $dishes;
    }


    /**
     * Retrives orders placed by user in a specified period of time
     *
     * @param User $user
     * @param $from
     * @param $to
     * @return DatabaseModel|DatabaseModel[]
     */
    public static function getUserOrders(User $user, $from, $to)
    {
        $expression = new Expression();

        $expression
            ->equals('userId', $user->id)
            ->leftJoin('menu', 'id', 'dish')
            ->leftJoin('pizza', 'id', 'pizza')
            ->orderBy('menu.date DESC')
            ->between('menu.date', [':from', $from], [':to', $to]);

        $orders = Order::findByExpression($expression);

        return $orders;
    }

    /**
     * Retrives orders placed by class in specified period of time
     *
     * @param $class
     * @param $from
     * @param null $to
     * @return array
     */
    public static function getClassOrders($class, $from, $to = null)
    {
        if ($class instanceof SchoolClass) {
            $class = $class->id;
        }

        $expression = new Expression();
        $expression->setColumns(['menu.date', 'menu.description', 'menu.type',
            'users.firstname', 'users.secondname', 'users.id', 'pizza.ingredients'])
            ->leftJoin('menu', 'orders.dish', 'menu.id')
            ->leftJoin('users', 'orders.userId', 'users.id')
            ->leftJoin('pizza', 'pizza.id', 'orders.pizza')
            ->equals('users.classId', $class)
            ->orderBy('menu.date ASC')
            ->orderBy('orders.pizza ASC')
            ->orderBy('menu.type DESC');

        if (!is_null($to)) {
            $expression->between('menu.date', [':from', $from], [':to', $to]);
        } else {
            $expression->greaterEqual('menu.date', $from);
        }

        $orders = Order::findByExpression($expression);

        return group($orders, 'date');
    }


    /**
     * Retrives pizza orders placed by class in specified period of time
     *
     * @param $class
     * @param $from
     * @param $to
     * @return array
     */
    public static function getPizzas($class, $from, $to)
    {

        if ($class instanceof SchoolClass) {
            $class = $class->id;
        }

        $expression = new Expression();
        $expression->setColumns(['pizza.id', 'ingredients', 'firstname', 'secondname', 'menu.date'])
            ->leftJoin('orders', 'pizza.id', 'orders.pizza')
            ->leftJoin('menu', 'orders.dish', 'menu.id')
            ->leftJoin('users', 'orders.userId', 'users.id')
            ->leftJoin('classes', 'classes.id', 'users.classId')
            ->equals('type', "PIZZA")
            ->equals('users.classId', $class)
            ->between('menu.date', [':from', $from], [':to', $to])
            ->appendSQL('HAVING COUNT(pizza) = 1'); // :C

        $pizzas = Pizza::findByExpression($expression);

        return group($pizzas, 'date');
    }

}
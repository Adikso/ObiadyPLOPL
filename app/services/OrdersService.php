<?php


class OrdersService
{

    public static function generateReport($level, $fromDate = null)
    {
        $classesList = Classes::getClasses($level);

        if (is_null($fromDate)) {
            $fromDate = date("Y-m-d H:i:s");
        }

        $toDate = date("Y-m-d", strtotime("Next Sunday", strtotime($fromDate)));

        $resultMsg = "";
        foreach ($classesList as $class) {
            $message = "";
            $orders = Orders::getClassOrders($class, $fromDate, $toDate);

            $pizzas = [];
            foreach ($orders as $date => $day) {
                if (!empty($pizzas)) {
                    $pizzas = groupPizzas($pizzas);

                    foreach ($pizzas as $key => $amount) {
                        $message .= sprintf("%s x%s", $key, $amount) . "<br>" . PHP_EOL;
                    }
                    $message .= PHP_EOL;
                    $pizzas = [];
                }

                $date = strtotime($date);
                $dateFriendly = getTranslatedDayName(date("l", $date)) . " " . date("d.m", $date);

                $type_grouped = group($day, 'type');

                $z1 = (array_key_exists('MEAT', $type_grouped) ? count($type_grouped['MEAT']) : 0);
                $z2 = (array_key_exists('VEGE', $type_grouped) ? count($type_grouped['VEGE']) : 0);
                $pizza = (array_key_exists('PIZZA', $type_grouped) ? count($type_grouped['PIZZA']) : null);

                if (Pages::getCurrentId() !== 'classes::orders::export::pizza') {
                    $message .= "<br>" . $dateFriendly . "<br>" . PHP_EOL;
                }
                if (!is_null($pizza)) {
                    foreach ($day as $p) {
                        if (is_array($p)) {
                            $pizzas[] = $p['ingredients'];
                        }
                    }
                } elseif (Pages::getCurrentId() !== 'classes::orders::export::pizza') {
                    $message .= sprintf("Z1x %s", $z1) . "<br>" . PHP_EOL;
                    $message .= sprintf("Z2x %s", $z2) . "<br>" . PHP_EOL;
                }

            }

            if (!empty($pizzas)) {
                $pizzas = groupPizzas($pizzas);

                foreach ($pizzas as $key => $amount) {
                    $message .= sprintf("%s x%s", $key, $amount) . "<br>" . PHP_EOL;
                }
                $message .= "<br>" . PHP_EOL;

            }

            if (!empty($message)) {
                $resultMsg .= "<br><b>Klasa " . $class->getName() . "</b><br>" . PHP_EOL . $message . PHP_EOL;
            }


        }

        return $resultMsg;
    }

}
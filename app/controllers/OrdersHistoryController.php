<?php


class OrdersHistoryController extends Controller
{

    public function show()
    {
        $from = date('Y-m-d', strtotime('Last Monday', time()));
        $to = date('Y-m-d', strtotime('Next Sunday +1 week', time()));

        $onlyordered = true;
        if (Input::has('onlyordered')){
            $onlyordered = (Input::get('onlyordered') === 'on' ? true : false);
        }

        if (Input::getDate('from') AND Input::getDate('to')) {
            $from = Input::getDate('from');
            $to = Input::getDate('to');
        }

        if ($onlyordered){
            $orders = Orders::getUserOrders(user(), $from, $to);
        }else{
            $orders = Orders::getMenu($from, $to);
        }

        $statisticsExp = new Expression();
        $statisticsExp->equals('userId', user()->id);
        $amount = Models::countByExpression(new Order(), $statisticsExp);

        echo $this->getTemplates()->render("pages/user/history",
            [
                "orders" => $orders,
                "onlyordered" => $onlyordered,
                "from" => $from,
                "to" => $to,
                "amount" => $amount
            ]);

    }

}
<?php


class Order extends DatabaseModel
{

    public $id;
    public $date;
    public $dish;
    public $userId;
    public $pizza;

    function __construct()
    {
        $a = func_get_args();
        $i = func_num_args();

        if ($i >= 4) {
            call_user_func_array([$this, '__constructVars'], $a);
        }
    }

    function __constructVars($date, $dish, $userId, $pizza = null)
    {
        $this->date = $date;
        $this->dish = $dish;
        $this->userId = $userId;
        $this->pizza = $pizza;
    }

    public function getOriginTable()
    {
        return "orders";
    }

}
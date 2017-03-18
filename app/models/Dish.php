<?php


class Dish extends DatabaseModel
{

    public $id;
    public $date;
    public $description;
    public $type;
    public $status;

    function __construct(){
        $args = func_get_args();
        $args_n = func_num_args();

        if ($args_n !== 0) {
            call_user_func_array([$this, "__constructFromParams"], $args);
        }
    }

    function __constructFromParams($date, $description, $type, $status = null)
    {
        $this->date = $date;
        $this->description = $description;
        $this->type = $type;
        $this->status = $status;
    }

    public function getOriginTable()
    {
        return 'menu';
    }

}
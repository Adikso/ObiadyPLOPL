<?php

class Message extends DatabaseModel
{

    public $target;
    public $description;
    public $expire;
    public $url;
    public $sort;

    function __construct()
    {
        $args = func_get_args();
        $args_n = func_num_args();

        if ($args_n === 1) {
            call_user_func_array([$this, "__constructFromArray"], $args);
        } else if ($args_n !== 0) {
            call_user_func_array([$this, "__constructFromParams"], $args);
        }
    }

    function __constructFromParams($target, $description, $url = null, $expire = null, $sort = 0)
    {
        $this->target = $target;
        $this->description = $description;
        $this->expire = $expire;
        $this->url = $url;
        $this->sort = $sort;
    }

    function __constructFromArray($data)
    {
        if (is_array($data)) {
            $this->target = $data['target'];
            $this->description = $data['description'];
            $this->expire = $data['expire'];
            $this->url = $data['url'];
            $this->sort = $data['sort'];
        }
    }

    public function getOriginTable()
    {
        return 'messages';
    }

}
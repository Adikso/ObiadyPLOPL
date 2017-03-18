<?php


class Rule extends DatabaseModel
{

    public $id;
    public $target;
    public $type;
    public $value;

    public function __construct($data = null)
    {
        if (is_array($data)) {
            $this->id = $data['id'];
            $this->target = $data['target'];
            $this->type = $data['type'];
            $this->value = $data['value'];
        }
    }

    public function getOriginTable()
    {
        return 'rules';
    }

}
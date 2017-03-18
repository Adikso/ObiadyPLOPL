<?php


class Pizza extends DatabaseModel
{

    public $id;
    public $ingredients;

    public function __construct($ingredients = null)
    {
        if (!is_null($ingredients)){
            $this->ingredients = implode(',', $ingredients);
        }
    }

    public function getOriginTable()
    {
        return 'pizza';
    }

}
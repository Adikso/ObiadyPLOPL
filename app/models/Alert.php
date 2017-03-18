<?php

/**
 * Can be used as error,success or warning message for user
 *
 * @property DatabaseModel
 */
class Alert
{
    public $title;
    public $message;
    public $type;

    function __construct($type, $title, $message)
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
    }
}
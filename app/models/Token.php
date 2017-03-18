<?php

class Token extends DatabaseModel
{

    public $id;
    public $expire;
    public $type;
    public $userId;
    public $authKey;
    public $useragent;
    public $devicename;
    public $last;
    public $additional;

    public function __construct($data = null)
    {

        if (is_array($data)) {
            $data = array_merge(
                array_fill_keys(
                    ['id', 'expire', 'type', 'userId', 'authKey', 'useragent', 'devicename', 'last', 'additional'], null), $data);

            $this->id = $data['id'];
            $this->expire = $data['expire'];
            $this->type = $data['type'];
            $this->userId = $data['userId'];
            $this->authKey = $data['authKey'];
            $this->useragent = $data['useragent'];
            $this->devicename = $data['devicename'];
            $this->last = $data['last'];
            $this->additional = $data['additional'];
        }
    }

    public function revoke()
    {
        $this->delete();
    }

    public function extend($toDate)
    {
        Tokens::extend($this, $toDate);
    }

    public function getOriginTable()
    {
        return "authorization";
    }

}
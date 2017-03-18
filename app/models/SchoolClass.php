<?php

/**
 * Class Alert
 * @property DatabaseModel
 */
class SchoolClass extends DatabaseModel
{

    public $id;
    public $year;
    public $class;
    public $email;
    public $owner;

    /**
     * Additional variable, not included in model
     */
    public $students;

    public function __construct($data = null)
    {
        if (is_array($data)) {
            $this->id = $data['id'];
            $this->year = $data['year'];
            $this->class = $data['class'];
            $this->email = $data['email'];
            $this->owner = $data['owner'];
        }
    }

    public function getName()
    {
        $start = strtotime($this->year . "-09-01");
        $now = strtotime(date("Y"));

        $classLevel = 1 + floor(($now - $start) / 60 / 60 / 24 / 365);

        return $classLevel . $this->class;
    }

    public function getOriginTable()
    {
        return 'classes';
    }

    public function getExcludedFields(){
        return ['students'];
    }

}
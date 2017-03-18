<?php

class Classes
{

    /**
     * Retrives classes
     *
     * You can specify if classes should include list of students or not.
     * Expired classes are hidden by default
     *
     * @param string $school_level
     * @param bool $retrieve_students
     * @param bool $showExpired
     * @return DatabaseModel|DatabaseModel[]|SchoolClass[]
     */
    public static function getClasses($school_level, $retrieve_students = false, $showExpired = false)
    {

        $expression = new Expression();
        $expression
            ->orderBy('owner ASC')
            ->orderBy('year DESC')
            ->orderBy('class ASC');

        if ($school_level !== SchoolLevel::ALL){
            $expression->equals('owner', $school_level);
        }

        $classes = SchoolClass::findByExpression($expression);

        if ($retrieve_students) {
            /** @var SchoolClass $class */
            foreach ($classes as $class) {
                $expression = new Expression();
                $expression->equals('classId', $class->id);

                $students = User::findByExpression($expression);
                $class->students = $students;
            }
        }

        if (!$showExpired) {
            foreach ($classes as $key => $class) {

                $start = strtotime($class->year . "-09-01");
                $now = strtotime(date("Y"));

                $diff = 1 + floor(($now - $start) / 60 / 60 / 24 / 365);

                if ($diff >= 4) {
                    unset($classes[$key]);
                    continue;
                }
            }
        }

        return $classes;

    }

    /**
     * Returns list of students in given class
     *
     * @param $class
     * @return DatabaseModel[]|null
     */
    public static function getStudents($class)
    {
        $exp = new Expression();

        if ($class instanceof SchoolClass) {
            $classId = $class->id;
        } else if (is_numeric($class)) {
            $classId = (int)$class;
        } else {
            return null;
        }

        $exp->equals('classId', $classId)
            ->different('role', 'REMOVED')
            ->orderBy('secondname ASC');

        return Models::findAllByExpression(new User(), $exp);
    }

}
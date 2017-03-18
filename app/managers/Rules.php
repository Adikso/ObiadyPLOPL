<?php

class Rules
{

    private static $rules = [];

    /**
     * Returns all the user rules for user
     *
     * @param $name
     * @param null $user
     * @return null
     */
    public static function getRule($name, $user = null)
    {
        if (is_null($user)) {
            $user = user();
        }

        if (array_key_exists($user->id, self::$rules)) {
            return self::$rules[$user->id][$name];
        }

        $class = $user->getClass();

        $classOwner = "NONE";
        // User without class ex. admin after install
        if ($class !== false) {
            $classOwner = $class->owner;
        }

        $expression = new Expression();
        $expression->in('target', [$user->id, 'class_' . $user->classId, $classOwner]);

        $rules = Rule::findByExpression($expression);

        foreach ($rules as $rule) {
            /* @var Rule $rule */
            self::$rules[$user->id][$rule->type] = unserialize($rule->value);
        }

        if (count($rules) === 0) {
            self::$rules[$user->id][$name] = null;
        } else {
            Debug::info('Loaded rules:');
            Debug::info(self::$rules[$user->id]);
            return self::$rules[$user->id][$name];
        }

        return null;
    }

    /**
     * Checks is it possible to place an order
     * basing on rule
     *
     * @param $date
     * @param $rule
     * @return bool
     */
    public static function isOrderPossible($date, $rule)
    {
        $targetWeekDay = date('l', strtotime($date));
        $closeWeekDay = self::findCloseDay($targetWeekDay, $rule);

        if (is_null($closeWeekDay)){
            return false;
        }

        $lastCloseDay = strtotime("last $closeWeekDay " . $rule[$closeWeekDay]['time'], strtotime($date));

        return (time() <= $lastCloseDay);
    }

    /**
     * Returns name of a day when
     * ordering time for given day ends
     *
     * @param $day
     * @param $close_config
     * @return null|string
     */
    public static function findCloseDay($day, $close_config)
    {
        foreach ($close_config as $close_day_name => $close_day) {

            if (in_array(strtolower($day), $close_day['days'])) {
                return $close_day_name;
            }

        }

        return null;
    }

    /**
     * Checks if it is possible to place an order
     *
     * @param $date
     * @param null $user
     * @return bool
     */
    public static function canOrder($date, $user = null)
    {

        if (is_null($user)) {
            $user = user();
        }

        $rule = self::getRule('orders', $user);

        if (is_null($rule)) {
            return true;
        }

        return self::isOrderPossible($date, $rule);
    }

}
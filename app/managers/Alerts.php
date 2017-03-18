<?php

class Alerts
{

    private static $alerts = [];

    /**
     * Shows alert
     *
     * @param Alert $alert
     */
    public static function show(Alert $alert)
    {
        self::$alerts[] = $alert;
    }

    /**
     * Returns alerts as array of Alert
     *
     * @return array
     */
    public static function getAlerts(){

        // Loads alerts from session data
        if (($data = Input::session('data')) !== null){
            if (array_key_exists('alerts', $data)){
                self::$alerts = array_merge(self::$alerts, $data['alerts']);
            }
        }

        return self::$alerts;
    }

}
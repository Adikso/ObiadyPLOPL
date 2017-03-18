<?php

class Database
{
    private static $instance = null;

    /**
     * Returns PDO instance
     *
     * New PDO instance is created if there was no previous connection
     * otherwise it uses previous connection
     *
     * @return \DebugBar\DataCollector\PDO\TraceablePDO|null
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            try {
                self::$instance = new DebugBar\DataCollector\PDO\TraceablePDO(
                    new PDO('mysql:host=' . config('db.host') . ';dbname=' . config('db.dbname'), config('db.username'), config('db.password'))
                );

                self::$instance->query('SET NAMES utf8');
            }catch (PDOException $e){
                echo 'Błąd połączenia z bazą danych';

                if (Debug::isDebugMode()){
                    echo $e->getMessage();
                }

                die();
            }

            Debug::$debugBar->addCollector(new DebugBar\DataCollector\PDO\PDOCollector(self::$instance));
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$instance;
    }

    /**
     * Makes query to database
     *
     * It is advised to use Models and Expression class for making requests
     *
     * @param $query
     * @param $bind
     * @return null|PDOStatement
     */
    public static function query($query, $bind = [])
    {
        try {
            $stmt = self::getInstance()->prepare($query);
            $stmt->execute($bind);

            if (is_null($stmt)) {
                Alerts::show(new Alert(AlertType::Danger, 'Błąd systemu', 'Problem z połączeniem z bazą danych'));
            }

            return $stmt;
        } catch (PDOException $e) {
            Debug::$debugBar['exceptions']->addException($e);
            echo $e->getMessage();
            Log::error($e->getMessage());
        }

        return null;
    }

    public static function select($query, $bind = [])
    {
        return self::query($query, $bind);
    }

    public static function insert($query, $bind = [])
    {
        return self::query($query, $bind);
    }

    public static function update($query, $bind = [])
    {
        return self::query($query, $bind);
    }

    public static function delete($query, $bind = [])
    {
        return self::query($query, $bind);
    }
}
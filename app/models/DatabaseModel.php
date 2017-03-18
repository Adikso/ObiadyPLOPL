<?php

abstract class DatabaseModel
{
    abstract public function getOriginTable();

    /**
     * Returns fields that are excluded from database update
     *
     * @return array
     */
    public function getExcludedFields()
    {
        return [];
    }

    /**
     * Returns model from database by id
     *
     * @param $id
     * @return DatabaseModel
     */
    public static function find($id)
    {
        $className = get_called_class();

        $class = new $className();
        return Models::find($class, $id);
    }

    /**
     * Returns model from database by column
     *
     * @param $column
     * @param $value
     * @return DatabaseModel
     */
    public static function findByValue($column, $value){
        $className = get_called_class();

        $class = new $className();
        return Models::findByValue($class, $column, $value);
    }

    /**
     * Returns all models from database by column
     *
     * @param $column
     * @param $value
     * @return DatabaseModel[]
     */
    public static function findAllByValue($column, $value){
        $className = get_called_class();

        $class = new $className();
        return Models::findAllByValue($class, $column, $value);
    }

    /**
     * Returns all models that meets criteria
     *
     * @param $expression
     * @param bool $asArray
     * @return DatabaseModel|DatabaseModel[]
     */
    public static function findByExpression($expression, $asArray = true){
        $className = get_called_class();

        $class = new $className();
        $results = Models::findAllByExpression($class, $expression);

        if (!$asArray && count($results) > 0){
            return $results[0];
        }

        return $results;
    }

    /**
     * Return all models of this type
     *
     * @return DatabaseModel[]
     */
    public static function all()
    {
        $className = get_called_class();

        $class = new $className();
        return Models::findAll($class);
    }

    /**
     * Sends changes to database
     */
    public function update()
    {
        return Models::save($this);
    }

    /**
     * Inserts model to database
     *
     * @return string
     */
    public function insert()
    {
        return Models::insert($this);
    }

    /**
     * Deletes model from database
     */
    public function delete()
    {
        Models::delete($this);
    }

    /**
     * Removes model from database with given column value
     *
     * @param $column
     * @param $value
     */
    public static function deleteByValue($column, $value){
        $className = get_called_class();
        $class = new $className();

        Models::deleteByValue(new $class(), $column, $value);
    }

    /**
     * Removes model from database with given id
     *
     * @param $id
     */
    public static function deleteById($id){
        self::deleteByValue('id', $id);
    }
}
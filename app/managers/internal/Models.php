<?php

class Models
{

    public static $fetchAsClass = true;

    /**
     * Finds single object in database by its type and id
     *
     * It is not recommended to use this function directly
     * Use functions provided by DatabaseModels if possible
     * @deprecated
     *
     * @param DatabaseModel $model - used as type reference and source for table name
     * @param integer $id - object id in database
     * @return DatabaseModel
     */
    public static function find($model, $id)
    {
        $table = $model->getOriginTable();
        $query = 'SELECT * FROM ' . $table . ' WHERE id=:id';

        $stmt = Database::query($query, ["id" => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($model));

        return $stmt->fetch();
    }

    /**
     * Finds all objects in database by their type
     *
     * It is not recommended to use this function directly
     * Use functions provided by DatabaseModels if possible
     * @deprecated
     *
     * @param DatabaseModel $model - used as type reference and source for table name
     * @param Expression $expression
     * @return DatabaseModel[]
     */
    public static function findAll($model, $expression = null)
    {
        return self::_findBy($model, $expression)->fetchAll();
    }


    /**
     * Finds single object in database by value in column
     *
     * It is not recommended to use this function directly
     * Use functions provided by DatabaseModels if possible
     * @deprecated
     *
     * @param DatabaseModel $model - used as type reference and source for table name
     * @param string $column - column name that we compare
     * @param string $value - value that we compare column value with
     * @param
     * @return DatabaseModel
     */
    public static function findByValue($model, $column, $value, $order = null)
    {
        return self::_findByValue($model, $column, $value, $order)->fetch();
    }

    /**
     * Finds all objects in database by value in column
     *
     * It is not recommended to use this function directly
     * Use functions provided by DatabaseModels if possible
     * @deprecated
     *
     * @param DatabaseModel $model - used as type reference and source for table name
     * @param string $column - column name that we compare
     * @param string $value - value that we compare column value with
     * @param
     * @return DatabaseModel[]
     */
    public static function findAllByValue($model, $column, $value, $order = null)
    {
        return self::_findByValue($model, $column, $value, $order)->fetchAll();
    }


    /**
     * Finds single object in database matching expression criteria
     *
     * It is not recommended to use this function directly
     * Use functions provided by DatabaseModels if possible
     * @deprecated
     *
     * @deprecated
     * @param DatabaseModel $model - used as type reference and source for table name
     * @param Expression $expression - criteria that object have to meet to be found
     * @return DatabaseModel
     */
    public static function findByExpression($model, $expression)
    {
        return self::_findBy($model, $expression)->fetch();
    }


    /**
     * Finds all objects in database matching expression criteria
     *
     * It is not recommended to use this function directly
     * Use functions provided by DatabaseModels if possible
     * @deprecated
     *
     * @param DatabaseModel $model - used as type reference and source for table name
     * @param Expression $expression - criteria that object have to meet to be found
     * @return DatabaseModel[]
     */
    public static function findAllByExpression($model, $expression)
    {
        return self::_findBy($model, $expression)->fetchAll();
    }

    /**
     * Counts amound of records that meets the conditions
     *
     * @var DatabaseModel $model
     * @var Expression $expression
     * @return PDOStatement
     */
    public static function countByExpression($model, $expression)
    {
        $table = $model->getOriginTable();
        $query = 'SELECT COUNT(*) AS amount FROM ' . $table . ' WHERE ' . $expression->generateQuery();

        $stmt = Database::query($query, $expression->generateBind());

        return $stmt->fetch()['amount'];
    }

    /**
     * Counts amount of records in database
     *
     * @var DatabaseModel $model
     * @return PDOStatement
     */
    public static function count($model)
    {
        $table = $model->getOriginTable();
        $query = 'SELECT COUNT(*) AS amount FROM ' . $table;

        $stmt = Database::query($query, []);

        return $stmt->fetch()['amount'];
    }


    /**
     * Updates existing model data in database
     *
     * It is not recommended to use this function directly
     * Use functions provided by DatabaseModels if possible
     * @deprecated
     *
     * @param DatabaseModel $model - used as type reference and source for table name
     */
    public static function save($model)
    {
        $variables = get_object_vars($model);
        array_remove_keys($variables, $model->getExcludedFields());

        $table = $model->getOriginTable();
        $params = [];

        foreach ($variables as $key => $value) {
            $params[] = $key . '=:' . $key;
        }

        $query = 'UPDATE ' . $table . '
                  SET ' . implode(',', $params) . ' WHERE id = :id';

        $bind = array_combine(
            array_map(function ($k) {
                return ':' . $k;
            }, array_keys($variables)),
            $variables
        );

        $bind[':id'] = $model->id;

        Database::update($query, $bind);
    }

    /**
     * Saves new model in database
     *
     * It is not recommended to use this function directly
     * Use functions provided by DatabaseModels if possible
     * @deprecated
     *
     * @param DatabaseModel $model - used as type reference and source for table name
     * @return int
     */
    public static function insert($model)
    {
        $variables = get_object_vars($model);
        array_remove_keys($variables, $model->getExcludedFields());

        $table = $model->getOriginTable();

        $query_keys = implode(',', array_keys($variables));

        $bind = array_combine(
            array_map(function ($k) {
                return ':' . $k;
            }, array_keys($variables)),
            $variables
        );

        $query = "INSERT INTO " . $table . " (" . $query_keys . ") VALUES (" . join(',', array_keys($bind)) . ")";

        Database::insert($query, $bind);
        return Database::getInstance()->lastInsertId();
    }

    /**
     * Deletes existing model from database
     *
     * It is not recommended to use this function directly
     * Use functions provided by DatabaseModels if possible
     * @deprecated
     *
     * @param DatabaseModel $model - used as type reference and source for table name
     */
    public static function delete($model)
    {
        $table = $model->getOriginTable();

        $query = "DELETE FROM " . $table . " WHERE id=:id";
        $bind = [':id' => $model->id];

        Database::delete($query, $bind);
    }

    /**
     * Deletes existing model with specified value from database
     *
     * It is not recommended to use this function directly
     * Use functions provided by DatabaseModels if possible
     * @deprecated
     *
     * @param DatabaseModel $model - used as type reference and source for table name
     * @param string $column - column name with which we compare value with
     * @param $value
     */
    public static function deleteByValue($model, $column, $value)
    {
        $exp = new Expression();
        $exp->equals($column, $value);

        self::deleteByExpression($model, $exp);
    }

    /**
     * Deletes existing model meeting the conditions
     *
     * It is not recommended to use this function directly
     * Use functions provided by DatabaseModels if possible
     * @deprecated
     *
     * @param DatabaseModel $model - used as type reference and source for table name
     * @param Expression $expression - criteria that object have to meet to be found
     */
    public static function deleteByExpression($model, $expression)
    {
        self::_deleteBy($model, $expression->generateQuery(), $expression->generateBind());
    }



    // Private

    private static function _deleteBy($model, $expression, $bind)
    {
        $table = $model->getOriginTable();
        $query = "DELETE FROM " . $table . " WHERE " . $expression;

        Database::delete($query, $bind);
    }

    private static function _findBy($model, $expression)
    {
        $table = $model->getOriginTable();

        $columns = (isset($expression) ? $expression->getColumns() : []);

        if (empty($columns)) {
            $columns = '*';
        } else {
            $columns = implode(',', $columns);
        }

        $query = 'SELECT ' . $columns . ' FROM ' . $table;

        $leftJoin = null;
        $bind = [];

        if (!is_null($expression)) {
            $leftJoin = $expression->generateLeftJoin($table);

            if (!is_null($leftJoin)) {
                $query .= ' ' . $leftJoin;
            }

            $queryExp = $expression->generateQuery();

            if (!empty($queryExp)) {
                $query .= ' WHERE ' . $queryExp;
            }

            if (!empty($expression->getGroup())) {
                $query .= ' GROUP BY ' . join(',', $expression->getGroup());
            }

            if (!empty($expression->getOrder())) {
                $query .= ' ORDER BY ' . join(',', $expression->getOrder());
            }

            $bind = $expression->generateBind();
        }

        $stmt = Database::query($query, $bind);

        if (empty($leftJoin) && self::$fetchAsClass) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($model));
        }

        return $stmt;
    }

    private static function _findByValue($model, $column, $value)
    {
        $exp = new Expression();
        $exp->equals($column, $value);

        return self::_findBy($model, $exp);
    }

}
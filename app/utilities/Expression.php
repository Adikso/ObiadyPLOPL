<?php

class Expression
{

    private $query = [];
    private $order = [];
    private $group = [];
    private $leftJoin = [];

    private $columns = [];
    private $additional = [];

    private function addExpression($type, $column = null, $value = null)
    {
        $this->query[] = [
            "type" => $type,
            "column" => $column,
            "value" => $value
        ];
    }

    public function equals($column, $value)
    {
        $this->addExpression('equals', $column, $value);
        return $this;
    }

    public function different($column, $value)
    {
        $this->addExpression('different', $column, $value);
        return $this;
    }

    public function greater($column, $value)
    {
        $this->addExpression('greater', $column, $value);
        return $this;
    }

    public function greaterEqual($column, $value)
    {
        $this->addExpression('greaterequal', $column, $value);
        return $this;
    }

    public function lesser($column, $value)
    {
        $this->addExpression('lesser', $column, $value);
        return $this;
    }

    public function lesserEqual($column, $value)
    {
        $this->addExpression('lesserequal', $column, $value);
        return $this;
    }

    public function isNull($column)
    {
        $this->addExpression('isnull', $column);
        return $this;
    }

    public function isNotNull($column)
    {
        $this->addExpression('isnotnull', $column);
        return $this;
    }

    public function _and()
    {
        $this->addExpression('and');
        return $this;
    }

    public function _or()
    {
        $this->addExpression('or');
        return $this;
    }

    public function in($column, $array)
    {
        $this->addExpression('in', $column, $array);
        return $this;
    }

    public function between($column, $array1, $array2)
    {
        $this->addExpression('between', $column, [$array1, $array2]);
        return $this;
    }

    public function add($expression)
    {
        $this->addExpression('expression', null, $expression);
        return $this;
    }

    public function orderBy($value)
    {
        $this->order[] = $value;
        return $this;
    }

    public function groupBy($value)
    {
        $this->group[] = $value;
        return $this;
    }

    public function appendSQL($query)
    {
        $this->additional[] = ' ' . $query;
        return $this;
    }

    public function setColumns($columns)
    {

        for ($i = 0; $i < count($columns); $i++) {
            if (is_array($columns[$i])) {
                $columns[$i] = key($columns[$i]) . ' AS ' . reset($columns[$i]);
            }
        }

        $this->columns = $columns;
        return $this;
    }

    public function leftJoin($table, $localColumn, $externalColumn, $expression = null)
    {
        $this->leftJoin[$table] = [$localColumn, $externalColumn, $expression];
        return $this;
    }

    private function generateExpression($expression)
    {
        $type = $expression['type'];

        $signs = [
            'equals' => '=',
            'different' => '<>',
            'greater' => '>',
            'greaterequal' => '>=',
            'lesser' => '<',
            'lesserequal' => '<='
        ];

        if ($expression['type'] === 'isnull') {
            return $expression['column'] . ' IS NULL';
        }

        if ($expression['type'] === 'isnotnull') {
            return $expression['column'] . ' IS NOT NULL';
        }

        if ($expression['type'] === 'in') {
            $clause = implode(',', array_fill(0, count($expression['value']), '?'));
            return $expression['column'] . ' IN(' . $clause . ')';
        }

        if ($expression['type'] === 'between') {
            return '(' . $expression['column'] . ' BETWEEN ' . $expression['value'][0][0] . ' AND ' . $expression['value'][1][0] . ')';
        }

        if (in_array($expression['type'], ['or', 'and', 'having'])) {
            return $expression['type'];
        }

        // Replacing dot with underline is mandatory as dot in bind name causes error
        return $expression['column'] . $signs[$type] . ':' . str_replace('.', '_', $expression['column']);
    }

    public function generateLeftJoin($currentTable)
    {
        if (empty($this->leftJoin)) {
            return null;
        }

        $output = '';
        foreach ($this->leftJoin as $table => $value) {
            $localTableName = (!strpos($value[0], '.') ? $table . '.' . $value[0] : $value[0]);
            $externalTableName = (!strpos($value[1], '.') ? $currentTable . '.' . $value[1] : $value[1]);

            $output .= 'LEFT JOIN ' . $table . ' ON (' . $localTableName . '=' . $externalTableName;

            if (!is_null($value[2])) {
                $output .= ' AND ' . $value[2]->generateQuery();
            }

            $output .= ') ';
        }

        return $output;
    }

    public function generateQuery($query = null)
    {
        if ($query === null) {
            $query = $this->query;
        }

        $mysqlQuery = [];
        $previous = null;

        foreach ($query as $exp) {
            if (is_array($previous) && !in_array($previous['type'], ['or', 'and', 'having']) && !in_array($exp['type'], ['or', 'and', 'having'])) {
                $mysqlQuery[] = 'AND';
            }

            $previous = $exp;

            if ($exp['type'] == 'expression') {
                $mysqlQuery[] = '(' . $this->generateQuery($exp['value']->query) . ')';
                continue;
            }

            $mysqlQuery[] = $this->generateExpression($exp);
        }

        return implode(' ', $mysqlQuery) . (!empty($this->additional) ? implode(' ', $this->additional) : '');
    }

    public function _generateBind($expression)
    {
        $bind = [];

        foreach ($expression->query as $exp) {
            if (!in_array($exp['type'], ['and', 'or', 'having'])) {
                if ($exp['type'] == 'expression') {
                    $bind = array_merge($bind, $exp['value']->generateBind());
                    continue;
                }

                if ($exp['type'] == 'in') {
                    $bind = array_merge($bind, $exp['value']);
                    continue;
                }

                if ($exp['type'] == 'between') {
                    $key1 = str_replace('.', '_', $exp['value'][0][0]);
                    $key2 = str_replace('.', '_', $exp['value'][1][0]);

                    $bind[$key1] = $exp['value'][0][1];
                    $bind[$key2] = $exp['value'][1][1];
                    continue;
                }

                if ($exp['type'] == 'isnull') {
                    continue;
                }

                $key = ':' . str_replace('.', '_', $exp['column']);
                $bind[$key] = $exp['value'];
            }
        }

        return $bind;
    }

    public function generateBind()
    {
        $bind = [];

        foreach ($this->leftJoin as $leftJoin) {
            if ($leftJoin[2] === null) {
                continue;
            }

            $bind = array_merge($bind, $this->_generateBind($leftJoin[2]));
        }

        $bind = array_merge($bind, $this->_generateBind($this));

        return $bind;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getExpressions()
    {
        return $this->query;
    }

}
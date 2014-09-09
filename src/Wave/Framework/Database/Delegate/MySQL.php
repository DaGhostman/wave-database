<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 09/09/14
 * Time: 01:03
 */

namespace Wave\Framework\Database\Delegate;


class MySQL extends stdDelegate
{

    const INSERT_PRIORITY_HIGH = 'HIGH_PRIORITY';
    const INSERT_PRIORITY_LOW = 'LOW_PRIORITY';

    /**
     * @var \Wave\Framework\Database\Database
     */
    protected $database;


    public function insert($table, $fields, $priority = 'LOW_PRIORITY') {
        $this->database->query(sprintf(
            "INSERT %s INTO %s (%s) VALUES (:%s)",
            $priority,
            $table,
            implode(', ',$fields),
            implode(", :", $fields)
        ), $table);

        return $this;
    }

    public function limit($limit = 150, $offset = 0) {
        $this->database->query(
            sprintf("%s LIMIT %d, %d", $this->database->getQuery(), $offset, $limit)
        );

        return $this;
    }

    public function beginTransaction()
    {
        $this->inTransaction = true;
        $this->database->query('BEGIN TRANSACTION')->exec();

        return $this;
    }


} 

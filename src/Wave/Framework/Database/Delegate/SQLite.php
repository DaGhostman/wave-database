<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 09/09/14
 * Time: 01:05
 */

namespace Wave\Framework\Database\Delegate;


class SQLite extends stdDelegate {

    const INSERT_OR_FAIL = 'FAIL';
    const INSERT_OR_ROLL = 'ROLLBACK';
    const INSERT_OR_REPLACE = 'REPLACE';
    const INSERT_OR_IGNORE  = 'IGNORE';
    const INSERT_OR_ABORT = 'ABORT';

    public function insert($table, $fields, $or = 'FAIL') {
        $this->database->query(sprintf(
            "INSERT OR %s INTO %s (%s) VALUES (:%s)",
            $or,
            $table,
            implode(', ',$fields),
            implode(", :", $fields)
        ), $table);

        return $this;
    }

    public function beginTransaction()
    {
        $this->inTransaction = true;
        $this->database->query('BEGIN TRANSACTION')->exec();

        return $this;
    }

    public function order($by)
    {
        $this->database->query(
            sprintf("%s ORDER BY %s", $this->database->getQuery(), $by)
        );

        return $this;
    }
} 

<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 09/09/14
 * Time: 22:41
 */

namespace Wave\Framework\Database\Delegate;


class PGSql extends StandardDelegate {
    public function insert($table, $fields) {
        $this->database->query(sprintf(
            "INSERT INTO %s (%s) VALUES (:%s)",
            $table,
            implode(', ',$fields),
            implode(", :", $fields)
        ), $table);

        return $this;
    }

    public function beginTransaction()
    {
        $this->inTransaction = true;
        $this->database->query("START TRANSACTION")->exec();

        return $this;
    }
} 

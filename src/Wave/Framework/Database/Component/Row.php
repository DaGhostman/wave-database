<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 07/09/14
 * Time: 23:59
 */

namespace Wave\Framework\Database\Component;

use \Wave\Framework\Storage\Registry;

class Row implements \ArrayAccess, \IteratorAggregate
{
    private $storage = null;

    public function __construct($fields)
    {
        $this->storage = new Registry(array(
            'mutable' => false,
            'replace' => false,
            'data' => $fields
        ));
    }

    public function __get($field)
    {
        return $this->storage->get($field);
    }

    public function __isset($key)
    {
        return $this->storage->exists($key);
    }

    /**
     * @codeCoverageIgnore
     */
    public function offsetSet($key, $value)
    {

    }

    public function offsetGet($key)
    {
        return $this->$key;
    }
    public function offsetExists($key)
    {
        return $this->storage->exists($key);
    }

    /**
     * @codeCoverageIgnore
     */
    public function offsetUnset($key)
    {

    }

    public function getIterator()
    {
        return $this->storage->getIterator();
    }
}

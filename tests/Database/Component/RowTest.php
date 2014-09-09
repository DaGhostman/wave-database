<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 08/09/14
 * Time: 23:44
 */

namespace Component;


use Wave\Framework\Database\Component\Row;

class RowTest extends \PHPUnit_Framework_TestCase {
    private $fixture = array(
        'id' => 1,
        'name' => 'John Doe'
    );

    public function testRowGetters()
    {
        $row = new Row($this->fixture);
        $this->assertSame(1, $row['id']);
        $this->assertSame(1, $row->id);
    }

    public function testRowIsset()
    {
        $row = new Row($this->fixture);
        $this->assertTrue(isset($row['id']));
        $this->assertTrue(isset($row->id));
    }

    public function testIterator()
    {
        $row = new Row($this->fixture);
        $this->assertInstanceOf('\Traversable', $row->getIterator());
    }
}

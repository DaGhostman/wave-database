<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 08/09/14
 * Time: 23:52
 */

namespace Component;

use Wave\Framework\Database\Component\Table;

class RowStub {
    public $id = null;
    private $name = null;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}

class TableTest extends \PHPUnit_Framework_TestCase {
    private $table = null;


    protected function setUp()
    {
        $this->table = new Table(array(
            new RowStub(1, 'John'),
            new RowStub(3, 'Lilly'),
            new RowStub(4, 'Jenny'),
            new RowStub(2, 'Mike'),
            new RowStub(5, 'Sam')
        ), 'id', 'StubTable');
    }

    protected function tearDown()
    {
        unset($this->table);
    }

    public function testCreation()
    {

        $this->expectOutputString("Jim.Eddy.Chris.");
        $table = new Table(array(
            new RowStub(1, 'Jim'),
            new RowStub(3, 'Eddy'),
            new RowStub(2, 'Chris')
        ), null, 'MethodStubTable');

        $this->assertSame(3, count($table));
        foreach ($table as $row) {
            echo $row->getName() . '.';
        }
    }

    public function testIteration()
    {
        $this->expectOutputString(
            "1: John\n\r" .
            "2: Lilly\n\r" .
            "3: Jenny\n\r" .
            "4: Mike\n\r" .
            "5: Sam\n\r"
        );

        foreach ($this->table as $key => $row)
        {
            echo sprintf("%d: %s\n\r", ++$key, $row->getName());
        }
    }


}

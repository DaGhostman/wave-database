<?php
/**
 * Created by PhpStorm.
 * User: daghostman
 * Date: 09/09/14
 * Time: 23:35
 */

namespace Delegate;


use Wave\Framework\Database\Delegate\SQLite;

class SQLiteTest extends \PHPUnit_Framework_TestCase {
    private $db = null;
    private $fixtures = array(
        array('id' => 1, 'movie_name' => 'Captain America: The Winter Soldier', 'movie_year' => 2014),
        array('id' => 2, 'movie_name' => 'X-Man: Days of the Future Past', 'movie_year' => 2014),
        array('id' => 3, 'movie_name' => 'Her', 'movie_year' => 2013),
        array('id' => 4, 'movie_name' => 'Lucy', 'movie_year' => 2014)
    );

    protected function setUp()
    {
        $db = new \Wave\Framework\Database\Database(new \PDO('sqlite::memory:'));
        $this->db = new SQLite($db);
        $db->query("CREATE TABLE IF NOT EXISTS movie (id TEXT, movie_name TEXT, movie_year TEXT)")
            ->exec();

        foreach ($this->fixtures as $set) {
            $this->db->insert('movie', array_keys($set))->exec($set);
        }

    }

    protected function tearDown()
    {
        $this->db->delete('movie')->exec();
    }

    public function testDatabaseInit()
    {
        $db = $this->db;

        $this->assertInstanceOf(
            '\Wave\Framework\Database\Delegate\SQLite',
            $db->select('movie', array('movie_name'))
        );
    }

    public function testSelectStatements()
    {
        $db = $this->db;

        $result = $db->select('movie', array('movie_year', 'movie_name'))->exec()->fetch();
        $this->assertInstanceOf(
            '\Wave\Framework\Database\Component\Row',
            $result
        );


        $this->assertEquals('2014', $result->movie_year);
        $this->assertEquals('2014', $result[0]);
        $this->assertEquals(
            'Captain America: The Winter Soldier', $result->movie_name
        );
        $this->assertEquals('Captain America: The Winter Soldier', $result[1]);
    }

    public function testSelectWithLimit()
    {
        $db = $this->db;
        $result = $db->select('movie', array('movie_name', 'movie_year'))
            ->order('id')
            ->limit(1, 3)
            ->exec()
            ->fetchAll();


        $this->assertSame(1, count($result));
        $this->assertInstanceOf(
            '\Wave\Framework\Database\Component\Table',
            $result
        );

        $this->assertSame('Lucy', $result->current()->movie_name);
    }

    public function testInsert()
    {
        $db = $this->db;
        $db->insert('movie', array('id', 'movie_name', 'movie_year'))
            ->execute(array('id' => 5, 'movie_name' => 'It', 'movie_year' => '1990'));

        $result = $db->select('movie', array('id', 'movie_name'))
            ->where('movie_name = :mname')
            ->exec(array('mname' => 'It'))
            ->fetch();

        $this->assertEquals(5, $result['id']);
    }

    public function testTransaction()
    {
        $this->assertInstanceOf(
            '\Wave\Framework\Database\Delegate\SQLite',
            $this->db->beginTransaction()
        );
    }
}

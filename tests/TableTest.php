<?php

namespace Studiow\Table\Test;

class TableTest extends \PHPUnit_Framework_TestCase
{

    private function _createTable()
    {
        $table = new \Studiow\Table\Table();
        $table->createColumn("first", "First", ["first"]);
        $table->createColumn("ucfirst", "UCFirst", function($rowData) {
            return ucfirst($rowData["first"]);
        });

        $table->createColumn("secondthird", "Second &amp; third", ["second", "third"]);
        return $table;
    }

    /**
     * @test
     */
    public function can_create_table()
    {
        $table = $this->_createTable();
        $table->addRow(["first" => "first param", "second" => "second param", "third" => "third_param"]);
        $expected = '<table><thead><tr><th>First</th><th>UCFirst</th><th colspan="2">Second &amp; third</th></tr></thead><tbody><tr><td>first param</td><td>First param</td><td>second param</td><td>third_param</td></tr></tbody></table>';
        $this->assertEquals($expected, (string) $table);
    }

    /**
     * @test
     */
    public function can_create_empty_table()
    {
        $table = $this->_createTable();
        $expected = '<table><thead><tr><th>First</th><th>UCFirst</th><th colspan="2">Second &amp; third</th></tr></thead><tbody class="empty"></tbody></table>';
        $this->assertEquals($expected, (string) $table);
    }

    /**
     * @test
     */
    public function can_create_empty_text_table()
    {
        $table = $this->_createTable();
        $table->setEmptyText("no items");
        $expected = '<table><thead><tr><th>First</th><th>UCFirst</th><th colspan="2">Second &amp; third</th></tr></thead><tbody class="empty"><tr class="empty"><td colspan="4">no items</td></tr></tbody></table>';
        $this->assertEquals($expected, (string) $table);
    }

}

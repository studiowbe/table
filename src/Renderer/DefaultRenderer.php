<?php

namespace Studiow\Table\Renderer;

use Studiow\HTML\Element;
use Studiow\Table\Table;
use Studiow\Table\RendererInterface;
use Studiow\Table\ColumnInterface;

class DefaultRenderer implements RendererInterface
{

    /**
     * Render the thead
     * 
     * @param array $columns
     * @return \Studiow\HTML\Element
     */
    public function header(array $columns)
    {
        $cells = [];
        foreach ($columns as $column) {
            $cells[] = $this->headerCell($column);
        }

        return new Element("thead", '<tr>' . implode('', $cells) . '</tr>');
    }

    /**
     * Render a single th header cell
     * 
     * @param \Studiow\Table\ColumnInterface $column
     * @return \Studiow\HTML\Element
     */
    protected function headerCell(ColumnInterface $column)
    {
        $element = new Element("th", $column->getLabel(), (array) $column->getAttributes());
        $colCount = $column->getColumnCount();
        if ($colCount > 1) {
            $element->setAttribute("colspan", $colCount);
        } else {
            $element->removeAttribute("colspan");
        }
        return $element;
    }

    /**
     * Render the tbody
     * 
     * @param array $values
     * @return \Studiow\HTML\Element
     */
    public function body(array $values)
    {
        $rows = [];
        foreach ($values as $index => $rowValues) {
            $rows[] = $this->bodyRow($rowValues, $index);
        }

        return new Element("tbody", implode('', $rows));
    }

    /**
     * Render a single body row
     * 
     * @param array $rowValues
     * @param int $index
     * @return \Studiow\HTML\Element
     */
    protected function bodyRow(array $rowValues, $index)
    {
        $cells = [];
        foreach ($rowValues as $columnId => $cellValues) {
            $cells = array_merge($cells, $this->bodyCells($cellValues, $columnId));
        }
        return new Element("tr", implode('', $cells));
    }

    /**
     * Render all cells for a specific row and column
     * 
     * @param array $cellValues
     * @param string $columnId
     * @return array
     */
    protected function bodyCells(array $cellValues, $columnId)
    {
        $cells = [];
        foreach ($cellValues as $index => $cellValue) {
            $cells[] = $this->bodyCell($cellValue, $columnId, $index);
        }
        return $cells;
    }

    /**
     * Render a single body row
     * 
     * @param string $cellValue
     * @param string $columnId
     * @param int $index
     * @return \Studiow\HTML\Element
     */
    protected function bodyCell($cellValue, $columnId, $index)
    {
        return new Element('td', $cellValue);
    }

    /**
     * Render the tbody when the table is emtpy 
     * 
     * @param string $emptyText
     * @param int $columnCount
     * @return \Studiow\HTML\Element
     */
    public function emptyBody($emptyText, $columnCount)
    {
        $content = empty($emptyText) ? '' : $this->emptyBodyRow($emptyText, $columnCount);
        return new Element('tbody', $content, ["class" => "empty"]);
    }

    /**
     * Render a single row when the table is empty
     * 
     * @param string $emptyText
     * @param int $columnCount
     * @return \Studiow\HTML\Element
     */
    protected function emptyBodyRow($emptyText, $columnCount)
    {
        $content = $this->emptyBodyCell($emptyText, $columnCount);
        return new Element('tr', $content, ["class" => "empty"]);
    }

    /**
     * Render a single cell when the table is empty
     *  
     * @param string $emptyText
     * @param int $columnCount
     * @return \Studiow\HTML\Element
     */
    protected function emptyBodyCell($emptyText, $columnCount)
    {
        return new Element('td', $emptyText, ["colspan" => $columnCount]);
    }

    /**
     * Render the entire table
     * 
     * @param \Studiow\Table\Table $table
     * @return \Studiow\HTML\Element
     */
    public function table(Table $table)
    {
        $content = $table->getHeaderHTML() . $table->getBodyHTML();
        return new Element('table', $content, (array) $table->getAttributes());
    }

}

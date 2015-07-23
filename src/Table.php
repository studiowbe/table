<?php

namespace Studiow\Table;

use Studiow\Table\ColumnInterface;
use Studiow\Table\RendererInterface;
use Studiow\HTML\Attributes;
use Studiow\Table\Renderer\DefaultRenderer;

class Table
{

    use \Studiow\HTML\HasAttributesTrait;

    protected $columns = [];
    protected $rows = [];
    protected $renderer;
    protected $emptyText;

    public function __construct(array $attributes = [], RendererInterface $renderer = null)
    {
        $this->setAttributes(new Attributes($attributes));
        $this->setRenderer(($renderer instanceof RendererInterface) ? $renderer : new DefaultRenderer());
    }

    /**
     * Set the renderer
     * 
     * @param \Studiow\Table\RendererInterface $renderer
     * @return \Studiow\Table\Table
     */
    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }

    /**
     * Get the current renderer
     * 
     * @return \Studiow\Table\RendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * Set the text which will be shown if the table is empty
     * 
     * @param mixed $emptyText
     * @return \Studiow\Table\Table
     */
    public function setEmptyText($emptyText)
    {
        $this->emptyText = $emptyText;
        return $this;
    }

    /**
     * Get the  text which will be shown if the table is empty
     * 
     * @return mixed 
     */
    public function getEmptyText()
    {
        return $this->emptyText;
    }

    /**
     * Create and add a new DefaultColumn
     * 
     * @param string $id
     * @param string $label
     * @param mixed $handlers
     * @param array $attributes
     * @param bool $sortable
     * @return \Studiow\Table\Table
     */
    public function createColumn($id, $label, $handlers, array $attributes = [], $sortable = false)
    {
        $column = new Column\DefaultColumn($id, $label, $handlers, $attributes);
        if ($sortable) {
            $column = new Column\SortableColumn($id, $label, $handlers, $attributes);
        } else {
            $column = new Column\DefaultColumn($id, $label, $handlers, $attributes);
        }
        $this->addColumn($column);
        return $this;
    }

    /**
     * Add a column
     * 
     * @param \Studiow\Table\ColumnInterface $column     
     * @return \Studiow\Table\Table
     */
    public function addColumn(ColumnInterface $column)
    {
        $this->columns[$column->getId()] = $column;
        return $this;
    }

    /**
     * Get a column by id
     * 
     * @param string $id
     * @return \Studiow\Table\ColumnInterface
     */
    public function getColumn($id)
    {
        return array_key_exists($id, $this->columns) ? $this->columns[$id] : null;
    }

    /**
     * Get all columns
     * 
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Add a single row
     * @param mixed $rowData
     * @return \Studiow\Table\Table
     */
    public function addRow($rowData)
    {
        $this->rows[] = $rowData;
        return $this;
    }

    /**
     * Add multiple rows
     * @param array $rowsData
     * @return \Studiow\Table\Table
     */
    public function addRows($rowsData)
    {
        foreach ($rowsData as $rowData) {
            $this->addRow($rowData);
        }
        return $this;
    }

    /**
     * Get calculated values for all rows and all columns
     * 
     * @return array
     */
    public function getValues()
    {
        $rows = [];
        foreach ($this->rows as $rowData) {
            $rows[] = $this->getRowValues($rowData);
        }
        return $rows;
    }

    /**
     * Get calculated values for a single row
     * 
     * @param mixed $rowData
     * @return array
     */
    protected function getRowValues($rowData)
    {
        $row = [];
        foreach ($this->columns as $column) {
            $row[$column->getId()] = $column->getValues($rowData);
        }
        return $row;
    }

    /**
     * Get the total column count
     * 
     * @return int
     */
    public function getColumnCount()
    {
        $count = 0;
        foreach ($this->getColumns() as $column) {
            $count += intval($column->getColumnCount());
        }
        return $count;
    }

    /**
     * Render the tbody
     * 
     * @return string
     */
    public function getBodyHTML()
    {
        if (sizeof($this->rows) > 0) {
            return $this->getRenderer()->body($this->getValues());
        } else {
            return $this->getRenderer()->emptyBody($this->emptyText, $this->getColumnCount());
        }
    }

    /**
     * Render the thead
     * 
     * @return string
     */
    public function getHeaderHTML()
    {
        return $this->getRenderer()->header($this->columns);
    }

    /**
     * Render the table
     * 
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getRenderer()->table($this);
    }

}

<?php

namespace Studiow\Table;

interface ColumnInterface
{

    /**
     * Get the column id
     * 
     * @return string
     */
    public function getId();

    /**
     * Set the column id
     * @param string $id
     * @return \Studiow\Table\ColumnInterface
     */
    public function setId($id);

    /**
     * Get the column label
     * 
     * @return string
     */
    public function getLabel();

    /**
     * Set the column label
     * 
     * @param string $label
     * @return \Studiow\Table\ColumnInterface
     */
    public function setLabel($label);

    /**
     * Set the handlers for all subcolumns
     * 
     * @param mixed $handlers
     * @return \Studiow\Table\ColumnInterface
     */
    public function setHandlers($handlers);

    /**
     * Get the handlers for all subcolumns
     * 
     * @return array
     */
    public function getHandlers();

    /**
     * Calculate the value of all columns for a single row
     * 
     * @param mixed $rowData
     * @return array
     */
    public function getValues($rowData);

    /**
     * Get the total number of columns that will be generated
     * 
     * @return int
     */
    public function getColumnCount();
}

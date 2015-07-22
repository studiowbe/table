<?php

namespace Studiow\Table;

use Studiow\Table\Table;

interface RendererInterface
{

    /**
     * Render the thead
     * 
     * @param array $columns
     * @return string
     */
    public function header(array $columns);

    /**
     * Render the tbody
     * 
     * @param array $values
     * @return string
     */
    public function body(array $values);

    /**
     * Render the tbody when the table is emtpy 
     * 
     * @param string $emptyText
     * @param int $columnCount
     * @return string
     */
    public function emptyBody($emptyText, $columnCount);

    /**
     * Render the entire table
     * 
     * @param \Studiow\Table\Table $table
     * @return string
     */
    public function table(Table $table);
}

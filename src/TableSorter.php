<?php

namespace Studiow\Table;

class TableSorter
{

    /**
     *
     * @var Table 
     */
    protected $table;
    
    /**
     *
     * @var string 
     */
    protected $sortFieldName = 'orderby';
    
    /**
     *
     * @var string 
     */
    protected $sortOrderName = 'order';
    
    /**
     *
     * @var string 
     */
    protected $sortFieldDefault;
    
    /**
     *
     * @var string 
     */
    protected $sortOrderDefault;

    /**
     * 
     * @param \Studiow\Table\Table $table
     * @param string $sortFieldDefault
     * @param string $sortOrderDefault
     */
    public function __construct(Table $table = null, $sortFieldDefault = null, $sortOrderDefault = 'asc')
    {
        $this->setTable(($table instanceof Table) ? $table : new Table())
                ->setFieldDefault($sortFieldDefault)
                ->setOrderDefault($sortOrderDefault);
    }

    /**
     * @param string $sortFieldDefault
     * @return \Studiow\Table\TableSorter
     */
    public function setFieldDefault($sortFieldDefault)
    {
        $this->sortFieldDefault = $sortFieldDefault;
        return $this;
    }

    /**
     * @param string $sortFieldName
     * @return \Studiow\Table\TableSorter
     */
    public function setFieldName($sortFieldName)
    {
        $this->sortFieldName = $sortFieldName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->sortFieldName;
    }

    /**
     * @return mixed
     */
    public function getFieldValue()
    {
        $value = filter_input(INPUT_GET, $this->getFieldName());
        return is_null($value) ? $this->sortFieldDefault : $value;
    }

    /**
     * @param string $sortOrderDefault
     * @return \Studiow\Table\TableSorter
     */
    public function setOrderDefault($sortOrderDefault)
    {
        $this->sortOrderDefault = $sortOrderDefault;
        return $this;
    }

    /**
     * @param string $sortOrderName
     * @return \Studiow\Table\TableSorter
     */
    public function setOrderName($sortOrderName)
    {
        $this->sortOrderName = $sortOrderName;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderName()
    {
        return $this->sortOrderName;
    }

    /**
     * @return string
     */
    public function getOrderValue()
    {
        $value = filter_input(INPUT_GET, $this->getOrderName());
        if (is_null($value)) {
            $value = $this->sortOrderDefault;
        }
        return $value === 'desc' ? 'desc' : 'asc';
    }

    /**
     * 
     * @param \Studiow\Table\Table $table
     * @return \Studiow\Table\TableSorter
     */
    public function setTable(Table $table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * 
     * @return \Studiow\Table\Table
     */
    public function getTable()
    {
        return $this->table;
    }

    private function handleColumns()
    {
        foreach ($this->getTable()->getColumns() as $column) {
            if ($column instanceof Column\SortableColumn) {
                $this->handleColumn($column);
            }
        }
    }

    /**
     * 
     * @param \Studiow\Table\Column\SortableColumn $column
     */
    private function handleColumn(Column\SortableColumn $column)
    {
        $column->addClass('sortable');
        $column->removeClass('sortable-sorted')
                ->removeClass('sortable-sorted-asc')
                ->removeClass('sortable-sorted-desc');

        $fieldname = $this->getFieldName();
        $ordername = $this->getOrderName();
        $link_info = [$fieldname => $column->getId(), $ordername => 'asc'];
        if ($column->getId() === $this->getFieldValue()) {
            $column->addClass('sortable-sorted');
            if ($this->getOrderValue() == 'asc') {
                $link_info[$ordername] = 'desc';
                $column->addClass('sortable-sorted-asc');
            } else {
                $column->addClass('sortable-sorted-desc');
            }
        } else {
            $column->removeClass('sortable-sorted')
                    ->removeClass('sortable-sorted-asc')
                    ->removeClass('sortable-sorted-desc');
        }

        $column->getLabel()->setAttribute('href', '?' . http_build_query(array_merge($_GET, $link_info)));
    }

    public function __toString()
    {
        $this->handleColumns();
        return (string) $this->getTable();
    }

}

<?php

namespace Studiow\Table\Column;

use Studiow\Table\AbstractColumn;
use Studiow\HTML\Element;

class SortableColumn extends AbstractColumn
{

    /**
     * 
     * @param string $id Column ID
     * @param string $label Column label
     * @param string $handlers Handlers for all subcolumns
     * @param array $attributes
     */
    public function __construct($id, $label, $handlers, array $attributes = array())
    {
        parent::__construct($id, $label, $handlers, $attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function setLabel($label)
    {
        if (is_null($this->label)){       
            $this->label = new Element('a', $label, ['class'=>'sortable-link']);
        }
        return $this;
    }

}

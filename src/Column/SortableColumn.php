<?php

namespace Studiow\Table\Column;

use Studiow\Table\AbstractColumn;
use Studiow\HTML\Element;
class SortableColumn extends AbstractColumn
{

    
    
    public function __construct($id, $label, $handlers, array $attributes = array())
    {
        parent::__construct($id, $label, $handlers, $attributes);
    }

    public function setLabel($label)
    {
        if (is_null($this->label)){
            $this->label = new Element('a', $label, ['class'=>'sortable-link']);
        }
        return $this;
    }
   

}

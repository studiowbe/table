<?php

namespace Studiow\Table;

use Studiow\HTML\Attributes;

/**
 * @implements ColumnInterface
 */
abstract class AbstractColumn implements ColumnInterface
{

    use \Studiow\HTML\HasAttributesTrait;

    protected $id, $label, $handlers;

    public function __construct($id, $label, $handlers, array $attributes = [])
    {
        $this->setId($id)
                ->setLabel($label)
                ->setHandlers($handlers)
                ->setAttributes(new Attributes($attributes));
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setHandlers($handlers)
    {
        if (!is_array($handlers) || is_callable($handlers)) {
            $handlers = [$handlers];
        }
        $this->handlers = $handlers;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * {@inheritdoc}
     */
    public function getValues($rowData)
    {
        $cells = [];
        foreach ($this->getHandlers() as $handler) {
            $cells[] = $this->getValue($handler, $rowData);
        }
        return $cells;
    }

    /**
     * Calculate the value of a single column for a single row
     * 
     * @param mixed $handler
     * @param \ArrayAccess $rowData
     * @return mixed
     */
    protected function getValue($handler, $rowData)
    {
        if (is_callable($handler)) {
            return call_user_func($handler, $rowData);
        }
        if (is_string($handler)) {
            if (is_array($rowData) || $rowData instanceof \ArrayAccess) {
                return array_key_exists($handler, $rowData) ? $rowData[$handler] : null;
            }
            if (array_key_exists($handler, get_object_vars($object))) {
                return $rowData->$handler;
            }
            $getter = "get" . implode('', array_map('ucfirst', explode('_', $handler)));
            if (is_callable([$rowData, $getter])) {
                return call_user_func([$rowData, $getter]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnCount()
    {
        return sizeof($this->handlers);
    }

}

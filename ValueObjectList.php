<?php

namespace equicolor\valueObjects;

use yii\base\ArrayAccessTrait;

class ValueObjectList implements \ArrayAccess, \IteratorAggregate, IValueObject
{
    use ArrayAccessTrait;
    
    private $data;

    public static function create($className)
    {
        $class = new class ($className) extends ValueObjectList {
            public $valueObjectClassName;

            public function __construct($className) {
                $this->valueObjectClassName = $className;
            }
        };

        return $class;
    }

    public function setAttributes($data)
    {
        $this->data = [];
        $class = $this->valueObjectClassName;
        foreach ($data as $item) {
            $newItem = new $class;
            $newItem->setAttributes($item);
            $this->data[] = $newItem;
        }
    }

    public function getIterator() {
        return new \ArrayIterator($this->data);
    }

    public function toArray() {
        if (!isset($this->data) || empty($this->data)) {
            return [];
        }
        
        return array_map(function ($item) {
            return $item->toArray();
        }, $this->data);
    }
}

<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Pattern\Composite;

use ArrayObject;

/**
 * Trait CompositeComponentTrait
 * @package Pars\Pattern
 */
trait CompositeComponentTrait
{


    /**
     * @var ArrayObject
     */
    private $arrComponent;

    /**
     * @param $component
     *
     * @return $this
     */
    protected function addComponent($component)
    {
        if (!$this->hasComponent($component)) {
            $this->getComponent_List()->append($component);
        }

        return $this;
    }

    /**
     * @param $component
     *
     * @return bool
     */
    protected function hasComponent($component)
    {
        foreach ($this->getComponent_List() as $key => $val) {
            if ($val === $component) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return ArrayObject
     */
    protected function getComponent_List()
    {
        if (is_null($this->arrComponent)) {
            $this->arrComponent = new ArrayObject();
        }

        return $this->arrComponent;
    }

    /**
     * @param $component
     *
     * @return $this
     */
    protected function removeComponent($component)
    {
        foreach ($this->getComponent_List() as $key => $val) {
            if ($val === $component) {
                unset($this->getComponent_List()[$key]);
                break;
            }
        }

        return $this;
    }
}

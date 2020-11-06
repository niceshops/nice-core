<?php

declare(strict_types=1);

/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Core\Composite;

use ArrayObject;

/**
 * Trait CompositeComponentTrait
 * @package Niceshops\Core
 */
trait CompositeComponentTrait
{


    /**
     * @var ArrayObject
     */
    private $arrComponent;


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

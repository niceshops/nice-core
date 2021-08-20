<?php

namespace Pars\Pattern\Attribute;

use Pars\Pattern\Exception\CoreException;

trait MagicAttributeAwareTrait
{
    use AttributeAwareTrait;

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws CoreException
     */
    public function __call($name, $arguments)
    {
        if (substr($name, 0, 3) === "set" && is_array($arguments) && count($arguments) == 1) {
            return $this->setAttribute(lcfirst(substr($name, 3)), $arguments[0]);
        } elseif (substr($name, 0, 3) === "get" && is_array($arguments) && count($arguments) == 0) {
            return $this->getAttribute(lcfirst(substr($name, 3)));
        }
        return null;
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws CoreException
     */
    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    /**
     * @param $name
     * @param $value
     *
     * @return self
     * @throws CoreException
     */
    public function __set($name, $value)
    {
        return $this->setAttribute($name, $value);
    }
}

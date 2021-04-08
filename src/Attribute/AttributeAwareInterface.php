<?php

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Patterns\Attribute;

/**
 * Interface AttributeAwareInterface
 * @package Pars\Patterns
 */
interface AttributeAwareInterface
{


    /**
     * @param string $key
     * @param        $value
     *
     * @return $this
     */
    public function setAttribute(string $key, $value);


    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasAttribute(string $key): bool;


    /**
     * @param string $key
     *
     * @param bool $hasDefault
     * @param null $default
     * @return mixed
     */
    public function getAttribute(string $key, bool $hasDefault = false, $default = null);


    /**
     * @param string $key
     *
     * @return $this
     */
    public function unsetAttribute(string $key);


    /**
     * @return array
     */
    public function getAttribute_List(): array;
}

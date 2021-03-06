<?php
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\Attribute;

/**
 * Interface AttributeAwareInterface
 * @package NiceshopsDev\NiceCore
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
     * @return mixed
     */
    public function getAttribute(string $key);
    
    
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
<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\Helper\Object;


use ArrayAccess;
use ArrayObject;
use NiceshopsDev\NiceCore\Exception;
use stdClass;

class ObjectPropertyFinder
{
    
    /**
     * @var array|object
     */
    private $object;
    
    
    /**
     * ObjectPropertyFinder constructor.
     *
     * @param array|object $object
     *
     * @throws Exception    passed value is not an object or array
     */
    public function __construct($object)
    {
        if (!is_object($object) && !is_array($object)) {
            throw new Exception("Passed value is not an object or array!");
        }
        
        $this->object = $object;
    }
    
    
    /**
     * @return array|object
     */
    public function getObject()
    {
        return $this->object;
    }
    
    
    /**
     * @return array
     */
    public function getKeys(): array
    {
        $object = $this->getObject();
        if (is_array($object)) {
            $arrKey = array_keys($object);
        } elseif ($object instanceof stdClass) {
            $arrKey = array_keys((array)$object);
        } elseif ($object instanceof ArrayObject) {
            $arrKey = array_keys($object->getArrayCopy());
        } elseif (method_exists($object, "toArray")) {
            $arrKey = array_keys($object->toArray());
        } else {
            $arrKey = array_keys(get_object_vars($object));
        }
        
        return $arrKey;
    }
    
    
    /**
     * @return array
     */
    public function getValues(): array
    {
        $arrValue = [];
        
        foreach ($this->getKeys() as $key) {
            $arrValue[$key] = $this->getValue($key);
        }
        
        return $arrValue;
    }
    
    
    /**
     * @param int|string $key
     *
     * @return bool
     */
    public function hasKey($key): bool
    {
        return in_array($key, $this->getKeys(), true);
    }
    
    
    /**
     * @param int|string $key
     * @param null       $defaultValue
     *
     * @return mixed
     */
    public function getValue($key, $defaultValue = null)
    {
        $object = $this->getObject();
        
        if (!$this->hasKey($key)) {
            return $defaultValue;
        }
        
        if ((is_array($object) || $object instanceof ArrayObject || $object instanceof stdClass)) {
            return ((array)$object)[$key] ?? $defaultValue;
        }
        
        if ($object instanceof ArrayAccess) {
            return $object->offsetGet($key) ?? $defaultValue;
        }
        
        if (method_exists($object, "getData")) {
            return $object->getData($key) ?? $defaultValue;
        }
        
        return get_object_vars($object)[$key] ?? $defaultValue;
    }
}
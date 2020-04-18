<?php
/**
 * @see       https://github.com/niceshops/NiceCore for the canonical source repository
 * @license   https://github.com/niceshops/NiceCore/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\Traits;

use NiceshopsDev\NiceCore\Exception;
use NiceshopsDev\NiceCore\StrictAttributeAwareInterface;

/**
 * Trait AttributeTrait
 * @package NiceshopsDev\NiceCore
 */
trait AttributeTrait
{
    
    
    /**
     * @var bool
     */
    protected $allowMagicSetAttribute = false;
    
    
    /**
     * @var bool
     */
    protected $allowMagicGetAttribute = false;
    
    
    /**
     * @var array
     */
    private $arrAttribute = [];
    
    
    /**
     * @var array
     */
    private $arrLockedAttribute = [];
    
    
    /**
     * @var array
     */
    private $arrAttributeKeyMap = [];
    
    
    /**
     * @var array
     */
    private $arrAttributeNormalizedKeyCount = [];
    
    
    /**
     * @param string $key
     *
     * @return string
     */
    private function normalizeAttributeKey(string $key): string
    {
        // TODO use https://docs.laminas.dev/laminas-filter/word/#camelcasetounderscore
        $key = preg_replace(['#(?<=(?:\p{Lu}))(\p{Lu}\p{Ll})#', '#(?<=(?:\p{Ll}|\p{Nd}))(\p{Lu})#'], ['_' . '\1', '_' . '\1'], trim($key));
        
        return strtolower($key);
    }
    
    
    /**
     * @param $normalizedKey
     *
     * @return bool
     */
    protected function isAttributeLocked($normalizedKey): bool
    {
        return isset($this->arrLockedAttribute[$normalizedKey]);
    }
    
    
    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     * @throws Exception
     */
    public function setAttribute(string $key, $value): self
    {
        $normalizedKey = $this->normalizeAttributeKey($key);
        if (!$this->isAttributeLocked($normalizedKey)) {
            $this->arrAttributeKeyMap[$key] = $normalizedKey;
            
            if ($key !== $normalizedKey && array_key_exists($normalizedKey, $this->arrAttribute)) {
                if (array_count_values($this->arrAttributeKeyMap)[$normalizedKey] > 1) {
                    $message = "Try to set the attribute '$normalizedKey' with ambiguous keys ('$key', ...)!";
                    throw new Exception($message);
                }
            }
            
            $this->arrAttribute[$normalizedKey] = $value;
        }
        
        return $this;
    }
    
    
    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasAttribute(string $key): bool
    {
        $normalizedKey = array_key_exists($key, $this->arrAttributeKeyMap) ? $this->arrAttributeKeyMap[$key] : $this->normalizeAttributeKey($key);
        return $this->hasAttributeByNormalizedKey($normalizedKey);
    }
    
    
    /**
     * @param string $normalizedKey
     *
     * @return mixed
     */
    protected function hasAttributeByNormalizedKey(string $normalizedKey)
    {
        return array_key_exists($normalizedKey, $this->arrAttribute);
    }
    
    
    /**
     * @param string $key
     *
     * @return mixed
     * @throws Exception    if attribute not found and StrictAttributeAwareInterface interface is implemented
     */
    public function getAttribute(string $key)
    {
        $result = null;
        $normalizedKey = array_key_exists($key, $this->arrAttributeKeyMap) ? $this->arrAttributeKeyMap[$key] : $this->normalizeAttributeKey($key);
        if ($this->hasAttributeByNormalizedKey($normalizedKey)) {
            $result = $this->arrAttribute[$normalizedKey];
        } elseif ($this instanceof StrictAttributeAwareInterface) {
            throw new Exception("Attribute '$key' not found!");
        }
        return $result;
    }
    
    
    /**
     * @param string $key
     *
     * @return self
     */
    public function unsetAttribute(string $key): self
    {
        $normalizedKey = array_key_exists($key, $this->arrAttributeKeyMap) ? $this->arrAttributeKeyMap[$key] : $this->normalizeAttributeKey($key);
        if ($this->hasAttributeByNormalizedKey($normalizedKey)) {
            unset($this->arrAttributeKeyMap[$key]);
            unset($this->arrAttribute[$normalizedKey]);
            unset($this->arrLockedAttribute[$normalizedKey]);
        }
        return $this;
    }
    
    
    /**
     * @param string $key
     *
     * @return self
     */
    public function lockAttribute(string $key): self
    {
        $this->arrLockedAttribute[$this->normalizeAttributeKey($key)] = true;
        
        return $this;
    }
    
    
    /**
     * @param string $key
     *
     * @return self
     */
    public function unlockAttribute(string $key): self
    {
        unset($this->arrLockedAttribute[$this->normalizeAttributeKey($key)]);
        
        return $this;
    }
    
    
    /**
     * @return array
     */
    public function getAttribute_List(): array
    {
        $arrAttribute = [];
        foreach ($this->arrAttributeKeyMap as $key => $normalizedKey) {
            $arrAttribute[$key] = $this->arrAttribute[$normalizedKey];
        }
        
        return $arrAttribute;
    }
    
    
    /**
     * Alias for AttributeTrait::getAttribute_List()
     *
     * @return array
     * @see AttributeTrait::getAttribute_List()
     */
    public function getAttributes(): array
    {
        return $this->getAttribute_List();
    }
    
    
    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        if ($this->allowMagicSetAttribute && substr($name, 0, 3) === "set" && is_array($arguments) && count($arguments) == 1) {
            return $this->setAttribute(lcfirst(substr($name, 3)), $arguments[0]);
        } elseif ($this->allowMagicGetAttribute && substr($name, 0, 3) === "get" && is_array($arguments) && count($arguments) == 0) {
            return $this->getAttribute(lcfirst(substr($name, 3)));
        }
        
        if (get_parent_class()) {
            /** @noinspection PhpUndefinedClassInspection */
            return parent::__call($name, $arguments);
        }
        
        return null;
    }
    
    
    /**
     * @param $name
     * @param $value
     *
     * @return self|static
     * @throws Exception
     */
    public function __set($name, $value)
    {
        if ($this->allowMagicSetAttribute) {
            return $this->setAttribute($name, $value);
        }
        
        if (get_parent_class()) {
            /** @noinspection PhpUndefinedClassInspection */
            return parent::__set($name, $value);
        }
        
        return $this;
    }
    
    
    /**
     * @param $name
     *
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        if ($this->allowMagicGetAttribute) {
            return $this->getAttribute($name);
        }
        
        if (get_parent_class()) {
            /** @noinspection PhpUndefinedClassInspection */
            return parent::__get($name);
        }
        
        return null;
    }
    
    
}
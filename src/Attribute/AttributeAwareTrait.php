<?php

declare(strict_types=1);

/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Core\Attribute;

use Niceshops\Core\Exception\AttributeExistsException;
use Niceshops\Core\Exception\AttributeLockException;
use Niceshops\Core\Exception\AttributeNotFoundException;
use Niceshops\Core\Normalizer\Normalizer;

/**
 * Trait AttributeTrait
 * @package Niceshops\Core
 */
trait AttributeAwareTrait
{
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
     * @var bool
     */
    protected $enableNormalization = false;

    /**
     * @param string $key
     *
     * @return string
     */
    private function normalizeAttributeKey(string $key): string
    {
        if ($this->enableNormalization) {
            $normalizedKey = (new Normalizer())->normalize($key);
        } else {
            $normalizedKey = $key;
        }
        $this->arrAttributeKeyMap[$key] = $normalizedKey;
        return $normalizedKey;
    }


    /**
     * @param $normalizedKey
     *
     * @return bool
     */
    private function isAttributeLocked($normalizedKey): bool
    {
        return isset($this->arrLockedAttribute[$normalizedKey]);
    }


    /**
     * @param string $key
     * @param mixed $value
     *
     * @return self
     * @throws AttributeExistsException|AttributeLockException
     */
    public function setAttribute(string $key, $value): self
    {
        if ($this->enableNormalization) {
            $normalizedKey = $this->getNormalizedKey($key);
        } else {
            $normalizedKey = $key;
        }
        if (!$this->isAttributeLocked($normalizedKey)) {
            if ($key !== $normalizedKey && $this->hasAttributeByNormalizedKey($normalizedKey)) {
                if ($this->hasNormalizedKey($normalizedKey)) {
                    throw new AttributeExistsException("Try to set the attribute '$normalizedKey' with ambiguous keys ('$key', ...)!");
                }
            }
            $this->arrAttribute[$normalizedKey] = $value;
        } else {
            throw new AttributeLockException("Try to set locked attribute '$normalizedKey'");
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
        if ($this->enableNormalization) {
            $normalizedKey = $this->getNormalizedKey($key);
        } else {
            $normalizedKey = $key;
        }
        return $this->hasAttributeByNormalizedKey($normalizedKey);
    }


    /**
     * @param string $normalizedKey
     *
     * @return mixed
     */
    private function hasAttributeByNormalizedKey(string $normalizedKey)
    {
        return isset($this->arrAttribute[$normalizedKey]);
    }

    /**
     * @param string $normalizedKey
     * @return bool
     */
    private function hasNormalizedKey(string $normalizedKey): bool
    {
        return isset($this->arrAttributeKeyMap[$normalizedKey]);
    }

    /**
     * @param string $key
     * @return string
     */
    private function getNormalizedKey(string $key): string
    {
        return $this->hasNormalizedKey($key) ? $this->arrAttributeKeyMap[$key] : $this->normalizeAttributeKey($key);
    }

    /**
     * @param string $key
     *
     * @param bool $hasDefault
     * @param null $default
     * @return mixed
     * @throws AttributeNotFoundException if attribute not found and StrictAttributeAwareInterface interface is implemented
     */
    public function getAttribute(string $key, bool $hasDefault = false, $default = null)
    {
        $result = $default;
        if ($this->enableNormalization) {
            $normalizedKey = $this->getNormalizedKey($key);
        } else {
            $normalizedKey = $key;
        }
        if ($this->hasAttributeByNormalizedKey($normalizedKey)) {
            $result = $this->arrAttribute[$normalizedKey];
        } elseif (!$hasDefault) {
            throw new AttributeNotFoundException("Attribute '$key' not found!");
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
        if ($this->enableNormalization) {
            $normalizedKey = $this->getNormalizedKey($key);
        } else {
            $normalizedKey = $key;
        }
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
        if (!$this->enableNormalization) {
            return $this->arrAttribute;
        }
        $arrAttribute = [];
        foreach ($this->arrAttributeKeyMap as $key => $normalizedKey) {
            if ($this->hasAttributeByNormalizedKey($normalizedKey)) {
                $arrAttribute[$key] = $this->arrAttribute[$normalizedKey];
            }
        }

        return $arrAttribute;
    }


    /**
     * Alias for AttributeTrait::getAttribute_List()
     *
     * @return array
     * @see AttributeAwareTrait::getAttribute_List()
     */
    public function getAttributes(): array
    {
        return $this->getAttribute_List();
    }
}

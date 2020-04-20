<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/NiceCore for the canonical source repository
 * @license   https://github.com/niceshops/NiceCore/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\Traits;

/**
 * Trait OptionTrait
 * @package NiceshopsDev\NiceCore
 */
trait OptionTrait
{
    
    /**
     * @var array
     */
    private $arrOption = [];
    
    
    /**
     * @param string $option
     *
     * @return string
     */
    private function normalizeOption(string $option): string
    {
        // TODO use https://docs.laminas.dev/laminas-filter/word/#camelcasetounderscore
        $option = preg_replace(['#(?<=(?:\p{Lu}))(\p{Lu}\p{Ll})#', '#(?<=(?:\p{Ll}|\p{Nd}))(\p{Lu})#'], ['_' . '\1', '_' . '\1'], trim($option));
        
        return strtolower($option);
    }
    
    
    /**
     * May be overwritten by class using this trait for customized validation.
     *
     * @param string $option
     *
     * @return bool
     */
    protected function validateOption(string $option): bool
    {
        return strlen($option) > 0;
    }
    
    
    /**
     * @return $this
     */
    public function clearOptions(): self
    {
        $this->arrOption = [];
        return $this;
    }
    
    
    /**
     * @return array
     */
    public function getOptions(): array
    {
        return array_keys(
            array_filter(
                $this->arrOption, function ($value) {
                return $value === true;
            }
            )
        );
    }
    
    
    /**
     * @return array
     */
    public function getRemovedOptions(): array
    {
        return array_keys(
            array_filter(
                $this->arrOption, function ($value) {
                return $value === false;
            }
            )
        );
    }
    
    
    /**
     * @param string $option
     *
     * @return $this
     */
    public function addOption(string $option): self
    {
        if ($this->validateOption($option)) {
            $this->arrOption[$this->normalizeOption($option)] = true;
        }
        return $this;
    }
    
    
    /**
     * @param array $arrOption
     *
     * @return $this
     */
    public function addOptions(array $arrOption): self
    {
        foreach ($arrOption as $option) {
            $this->addOption($option);
        }
        
        return $this;
    }
    
    
    /**
     * @param string $option
     *
     * @return self
     */
    public function removeOption(string $option): self
    {
        if ($this->validateOption($option)) {
            $this->arrOption[$this->normalizeOption($option)] = false;
        }
        
        return $this;
    }
    
    
    /**
     * @param string $option
     *
     * @return $this
     */
    public function unsetOption(string $option): self
    {
        if ($this->validateOption($option)) {
            unset($this->arrOption[$this->normalizeOption($option)]);
        }
        
        return $this;
    }
    
    
    /**
     * @param string $normalizedOption
     *
     * @return bool
     */
    private function hasNormalizedOption(string $normalizedOption): bool
    {
        return array_key_exists($normalizedOption, $this->arrOption) && $this->arrOption[$normalizedOption] === true;
    }
    
    
    /**
     * @param string $option
     *
     * @return bool
     */
    public function hasOption(string $option): bool
    {
        return $this->hasNormalizedOption($this->normalizeOption($option));
    }
    
    
}


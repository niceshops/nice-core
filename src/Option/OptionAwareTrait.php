<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Pattern\Option;

use Pars\Pattern\Normalizer\Normalizer;

/**
 * Trait OptionTrait
 * @package Pars\Pattern
 */
trait OptionAwareTrait
{
    /**
     * @var array
     */
    private $arrOption = [];

    /**
     * @var array
     */
    private $arrOptionKeyMap = [];

    protected $enableNormalization = false;

    /**
     * @param string $option
     *
     * @return string
     */
    private function normalizeOption(string $option): string
    {
        if ($this->enableNormalization) {
            $normalizedKey = (new Normalizer())->normalize($option);
        } else {
            $normalizedKey = $option;
        }
        $this->arrOptionKeyMap[$option] = $normalizedKey;
        return $normalizedKey;
    }

    /**
     * @param string $option
     * @return string
     */
    private function getNormalizedOptionKey(string $option): string
    {
        return $this->hasNormalizedOptionKey($option) ? $this->arrOptionKeyMap[$option] : $this->normalizeOption($option);
    }

    /**
     * @param string $option
     * @return bool
     */
    private function hasNormalizedOptionKey(string $option): bool
    {
        return isset($this->arrOptionKeyMap[$option]);
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
        return strlen(trim($option)) > 0;
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
    public function getOption_List(): array
    {
        return $this->getOption_List_By_Value(true);
    }


    /**
     * @return array
     */
    public function getRemovedOption_List(): array
    {
        return $this->getOption_List_By_Value(false);
    }

    /**
     * @param $value
     * @return array
     */
    private function getOption_List_By_Value($value): array
    {
        $option_List = [];
        foreach ($this->arrOption as $key => $option) {
            if ($option === $value) {
                if ($this->enableNormalization) {
                    $option_List[] = $this->getOptionByNormalizedKey((string) $key);
                } else {
                    $option_List[] = $key;
                }
            }
        }
        return $option_List;
    }

    /**
     * @param string $normalizedKey
     * @return string
     */
    private function getOptionByNormalizedKey(string $normalizedKey): string
    {
        return array_flip($this->arrOptionKeyMap)[$normalizedKey];
    }


    /**
     * @param string $option
     *
     * @return $this
     */
    public function addOption(string $option): self
    {
        $this->addNormalizedOption($this->getNormalizedOptionKey($option));
        return $this;
    }

    /**
     * @param string $option
     * @return $this
     */
    private function addNormalizedOption(string $option)
    {
        if ($this->validateOption($option)) {
            $this->arrOption[$option] = true;
        }
        return $this;
    }


    /**
     * @param array $arrOption
     *
     * @return $this
     */
    public function addOption_List(array $arrOption): self
    {
        $arrOption = array_filter($arrOption, function ($value) {
            return $this->validateOption($value);
        });
        if ($this->enableNormalization) {
            $arrNormOptions = (new Normalizer())->normalize($arrOption);
        } else {
            $arrNormOptions = $arrOption;
        }
        $this->arrOptionKeyMap = $this->arrOptionKeyMap + array_combine($arrOption, $arrNormOptions);
        $this->arrOption = $this->arrOption + array_fill_keys($arrNormOptions, true);
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
            $this->arrOption[$this->getNormalizedOptionKey($option)] = false;
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
            $normalizedKey = $this->getNormalizedOptionKey($option);
            unset($this->arrOption[$normalizedKey]);
            unset($this->arrOptionKeyMap[$normalizedKey]);
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
        return $this->hasNormalizedOptionKey($normalizedOption) && isset($this->arrOption[$normalizedOption]) && $this->arrOption[$normalizedOption] === true;
    }


    /**
     * @param string $option
     *
     * @return bool
     */
    public function hasOption(string $option): bool
    {
        return $this->hasNormalizedOption($this->getNormalizedOptionKey($option));
    }
}

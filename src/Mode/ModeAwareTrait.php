<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Pattern\Mode;

/**
 * Class ModeAwareTrait
 * @package Pars\Pattern\Mode
 */
trait ModeAwareTrait
{
    /**
     * @var string
     */
    private $mode = null;

    /**
    * @return string
    */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
    * @param string $mode
    *
    * @return $this
    */
    public function setMode(?string $mode)
    {
        $this->mode = $mode;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasMode(): bool
    {
        return $this->mode !== null;
    }
}

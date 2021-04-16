<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Pattern\Mode;

/**
 * Interface ModeAwareInterface
 * @package Pars\Pattern\Mode
 */
interface ModeAwareInterface
{
    /**
     * @return string
     */
    public function getMode(): string;

    /**
     * @param string $mode
     *
     * @return $this
     */
    public function setMode(?string $mode);

    /**
     * @return bool
     */
    public function hasMode(): bool;
}

<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Core\Mode;

/**
 * Interface ModeAwareInterface
 * @package Niceshops\Core\Mode
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
    public function setMode(string $mode);

    /**
     * @return bool
     */
    public function hasMode(): bool;
}

<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\Runner;

use Generator;

/**
 * Interface RunnerInterface
 * @package NiceshopsDev\NiceCore
 */
interface RunnerInterface
{
    
    
    /**
     * @param int  $from
     * @param null $length
     * @param int  $stepWidth
     *
     * @return Generator
     */
    public function runFrom($from = 0, $length = null, $stepWidth = 1);
    
    
    /**
     * @param int  $from
     * @param null $to
     * @param int  $stepWidth
     *
     * @return Generator
     */
    public function runFromTo($from = 0, $to = null, $stepWidth = 1);
}
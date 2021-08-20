<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Pattern\Runner;

use Generator;

/**
 * Interface RunnerInterface
 * @package Pars\Pattern
 */
interface RunnerInterface
{


    /**
     * @param int $from
     * @param null $length
     * @param int $stepWidth
     *
     * @return Generator
     */
    public function runFrom($from = 0, $length = null, $stepWidth = 1);


    /**
     * @param int $from
     * @param null $to
     * @param int $stepWidth
     *
     * @return Generator
     */
    public function runFromTo($from = 0, $to = null, $stepWidth = 1);
}

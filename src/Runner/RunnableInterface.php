<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Patterns\Runner;

/**
 * Interface RunnableInterface
 * @package Pars\Patterns
 */
interface RunnableInterface
{


    /**
     * @return RunnerInterface
     */
    public function getRunner();
}

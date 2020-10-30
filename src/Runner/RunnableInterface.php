<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Core\Runner;

/**
 * Interface RunnableInterface
 * @package Niceshops\Core
 */
interface RunnableInterface
{


    /**
     * @return RunnerInterface
     */
    public function getRunner();
}

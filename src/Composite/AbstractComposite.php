<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Patterns\Composite;

/**
 * Class AbstractComposite
 * @package Pars\Patterns
 */
abstract class AbstractComposite implements CompositeInterface
{
    use CompositeComponentTrait;
}

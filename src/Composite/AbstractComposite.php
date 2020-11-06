<?php

declare(strict_types=1);

/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Core\Composite;

/**
 * Class AbstractComposite
 * @package Niceshops\Core
 */
abstract class AbstractComposite implements CompositeInterface
{
    use CompositeComponentTrait;
}

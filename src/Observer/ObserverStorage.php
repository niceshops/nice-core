<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Patterns\Observer;

use Generator;
use Pars\Patterns\Composite\AbstractComposite;
use SplObserver;

/**
 * Class ObserverStorage
 * @package Pars\Patterns\Observer
 */
class ObserverStorage extends AbstractComposite
{


    /**
     * @param SplObserver $observer
     *
     * @return $this
     */
    public function addObserver(SplObserver $observer)
    {
        return $this->addComponent($observer);
    }


    /**
     * @param SplObserver $observer
     *
     * @return $this
     */
    public function removeObserver(SplObserver $observer)
    {
        return $this->removeComponent($observer);
    }


    /**
     * @return Generator   for all \SplObserver instances
     */
    public function runObserver()
    {
        foreach ($this->getComponent_List() as $observer) {
            yield $observer;
        }
    }
}

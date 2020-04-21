<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\Observer;


use Generator;
use NiceshopsDev\NiceCore\Composite\AbstractComposite;
use SplObserver;

/**
 * Class ObserverStorage
 * @package NiceshopsDev\NiceCore\Observer
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
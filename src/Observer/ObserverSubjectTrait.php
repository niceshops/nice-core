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
 * Trait ObserverSubjectTrait
 * @package NiceshopsDev\NiceCore\Observer
 * @todo UnitTests
 */
trait ObserverSubjectTrait
{
    
    /**
     * @var AbstractComposite
     */
    private $observerStorage;
    
    
    private function getObserverStorage()
    {
        if (!$this->observerStorage) {
            $this->observerStorage = new class extends AbstractComposite {
                
                
                /**
                 * @param SplObserver $observer
                 *
                 * @return AbstractComposite
                 */
                public function addObserver(SplObserver $observer)
                {
                    return $this->addComponent($observer);
                }
                
                
                /**
                 * @param SplObserver $observer
                 *
                 * @return AbstractComposite
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
            };
        }
        
        return $this->observerStorage;
    }
    
    
    /**
     * @param SplObserver $observer
     *
     * @return $this
     */
    public function attach(SplObserver $observer)
    {
        $this->getObserverStorage()->addObserver($observer);
        
        return $this;
    }
    
    
    /**
     * @param SplObserver $observer
     *
     * @return $this
     */
    public function detach(SplObserver $observer)
    {
        $this->getObserverStorage()->removeObserver($observer);
        
        return $this;
    }
    
    
    /**
     * @return $this
     */
    public function notify()
    {
        /**
         * @var SplObserver $observer
         */
        foreach ($this->getObserverStorage()->runObserver() as $observer) {
            /** @noinspection PhpParamsInspection */
            $observer->update($this);
        }
        
        return $this;
    }
}
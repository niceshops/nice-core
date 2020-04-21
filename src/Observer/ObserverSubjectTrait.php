<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\Observer;


use NiceshopsDev\NiceCore\Composite\AbstractComposite;
use SplObserver;

/**
 * Trait ObserverSubjectTrait
 * @package NiceshopsDev\NiceCore\Observer
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
            $this->observerStorage = new ObserverStorage();
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
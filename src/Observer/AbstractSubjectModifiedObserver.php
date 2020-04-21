<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\Observer;


use NiceshopsDev\NiceCore\Composite\CompositeComponentTrait;
use SplObserver;
use SplSubject;

/**
 * Class SubjectModifiedObserver
 * @package Niceshops\Library\Core
 */
abstract class AbstractSubjectModifiedObserver implements SplObserver
{
    
    
    use CompositeComponentTrait;
    
    
    /**
     * @param SplSubject $subject
     *
     * @return AbstractSubjectModifiedObserver
     */
    public function update(SplSubject $subject)
    {
        /**
         * TODO: Should be add a clone of the subject?
         */
        return $this->addComponent($subject);
    }
    
    
    /**
     * @return $this
     */
    public function reset()
    {
        $this->getComponent_List()->exchangeArray([]);
        
        return $this;
    }
    
    
    /**
     * @return SplSubject[]
     */
    protected function getModifiedSubject_List()
    {
        return $this->getComponent_List()->getArrayCopy();
    }
}
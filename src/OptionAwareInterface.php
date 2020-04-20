<?php
/**
 * @see       https://github.com/niceshops/NiceCore for the canonical source repository
 * @license   https://github.com/niceshops/NiceCore/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore;

/**
 * Interface OptionAwareInterface
 * @package NiceshopsDev\NiceCore
 */
interface OptionAwareInterface
{
    
    
    /**
     * @return $this
     */
    public function clearOptions();
    
    
    /**
     * @return array
     */
    public function getOptions(): array;
    
    
    /**
     * @return array
     */
    public function getRemovedOptions(): array;
    
    
    /**
     * @param string $option
     *
     * @return $this
     */
    public function addOption(string $option);
    
    
    /**
     * @param string $option
     *
     * @return $this
     */
    public function removeOption(string $option);
    
    
    /**
     * @param string $option
     *
     * @return $this
     */
    public function unsetOption(string $option);
    
    
    /**
     * @param string $option
     *
     * @return bool
     */
    public function hasOption(string $option): bool;
}
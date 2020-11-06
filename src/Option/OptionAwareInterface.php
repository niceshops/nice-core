<?php

/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Core\Option;

/**
 * Interface OptionAwareInterface
 * @package Niceshops\Core
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
    public function getOption_List(): array;


    /**
     * @return array
     */
    public function getRemovedOption_List(): array;


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

<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\PHPUnit;

use PHPUnit\Framework\TestCase;

class DefaultTestCase extends TestCase
{
    
    
    /**
     * @param        $object
     * @param string $trait
     *
     * @return bool
     */
    protected static function classUseTrait($object, string $trait): bool
    {
        $classUseTrait = false;
        $arrClassName = class_parents($object);
        array_unshift($arrClassName, get_class($object));
        foreach ($arrClassName as $className) {
            $arrTrait = class_uses($className);
            if (in_array($trait, $arrTrait)) {
                $classUseTrait = true;
                break;
            }
        }
        
        return $classUseTrait;
    }
    
    
    /**
     * @param string        $expected trait classname
     * @param string|object $actual   object or classname
     * @param string        $message
     */
    public static function assertUseTrait(string $expected, $actual, string $message = '')
    {
        self::assertTrue(self::classUseTrait($actual, $expected), $message);
    }
}
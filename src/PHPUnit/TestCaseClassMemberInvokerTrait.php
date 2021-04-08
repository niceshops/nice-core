<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Pattern\PHPUnit;

use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

trait TestCaseClassMemberInvokerTrait
{


    /**
     * @param object $object
     * @param string $methodName Private or protected method
     * @param array  $parameter
     *
     * @param array  $anotherParams
     *
     * @return mixed
     */
    protected function invokeMethod(&$object, string $methodName, $parameter = [], ...$anotherParams)
    {
        if (!is_object($object)) {
            $this->fail('Can not invoke method on an invalid object');
        }

        if (count($anotherParams)) {
            array_unshift($anotherParams, $parameter);
            $parameter = $anotherParams;
        } elseif (!is_array($parameter)) {
            $parameter = [$parameter];
        }

        try {
            $reflection = new ReflectionClass($object);
            if (!$reflection->hasMethod($methodName)) {
                $this->fail('Method name is not available: ' . $methodName);
            }
            $method = $reflection->getMethod($methodName);
            $method->setAccessible(true);

            return $method->invokeArgs($object, $parameter);
        } catch (ReflectionException $e) {
            $this->fail('ReflectionException is thrown on invoking method: ' . $e->getMessage());
        }
        return null;
    }


    /**
     * @param object $object
     *
     * @param string $name
     *
     * @return ReflectionProperty
     * @throws ReflectionException
     */
    protected function getReflectionProperty_for_Object(object $object, string $name): ReflectionProperty
    {
        if ($object instanceof MockObject) {
            $property = new ReflectionProperty(get_parent_class($object), $name);
        } else {
            $property = new ReflectionProperty($object, $name);
        }

        return $property;
    }


    /**
     * @param        $object
     * @param string $name
     * @param        $value
     */
    protected function invokeSetProperty($object, string $name, $value)
    {
        if (!is_object($object)) {
            $this->fail('Can not invoke set property on an invalid object');
        }

        try {
            $property = $this->getReflectionProperty_for_Object($object, $name);

            $property->setAccessible(true);
            $property->setValue($object, $value);
        } catch (ReflectionException $e) {
            $this->fail(sprintf('ReflectionException is thrown on invoking property set - %s', $e->getMessage()));
        }
    }


    /**
     * @param        $object
     * @param string $name
     *
     * @return mixed
     */
    protected function invokeGetProperty($object, string $name)
    {
        if (!is_object($object)) {
            $this->fail('Can not invoke get property on an invalid object');
        }

        try {
            $property = $this->getReflectionProperty_for_Object($object, $name);

            $property->setAccessible(true);

            return $property->getValue($object);
        } catch (ReflectionException $e) {
            $this->fail(sprintf('ReflectionException is thrown on invoking property get - %s', $e->getMessage()));
        }

        return null;
    }
}

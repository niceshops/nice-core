<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Pattern\PHPUnit;

use Generator;
use PHPUnit\Framework\AssertionFailedError;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Class TestCaseClassMemberInvokerTraitTest
 * @coversDefaultClass \Pars\Pattern\PHPUnit\TestCaseClassMemberInvokerTrait
 * @uses               \Pars\Pattern\PHPUnit\TestCaseClassMemberInvokerTrait
 * @package            Pars\Pattern
 */
class TestCaseClassMemberInvokerTraitTest extends DefaultTestCase
{


    /**
     * @var TestCaseClassMemberInvokerTrait
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(TestCaseClassMemberInvokerTrait::class)->getMockForTrait();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }


    /**
     * @group integration
     * @small
     */
    public function testTestClassExists()
    {
        $this->assertTrue(trait_exists(TestCaseClassMemberInvokerTrait::class), "Class Exists");
        $this->assertUseTrait(TestCaseClassMemberInvokerTrait::class, $this->object, "Mock Object uses " . TestCaseClassMemberInvokerTrait::class);
    }


    /**
     * @return Generator
     */
    public function invokeMethodDataProvider()
    {
        $obj = new class {

            /**
             * @param string $message
             *
             * @param array  $tails
             *
             * @return string
             * @noinspection PhpUnused
             */
            public function hello($message = "", ...$tails)
            {
                if (is_array($message)) {
                    $message = implode(" ", $message);
                }
                $message = strtoupper($message);

                return "hello" . (strlen($message) ? " " . $message : "") . ($tails ? " " . implode(" ", $tails) : "");
            }
        };

        yield[$obj, "hello", [], "hello"];
        yield[$obj, "hello", [["world"]], "hello WORLD"];
        yield[$obj, "hello", [["world", "!"]], "hello WORLD !"];
        yield[$obj, "hello", ["world", "!"], "hello WORLD !"];
        yield[$obj, "hello", ["world", "foo", "bar", "!"], "hello WORLD foo bar !"];
        yield[$obj, "hello", [["world", "foo"], "bar", "baz", "!"], "hello WORLD FOO bar baz !"];
        yield[$obj, "hello", [["world"], "foo", "bar", "baz", "!"], "hello WORLD foo bar baz !"];
        yield[$obj, "hello", [["world"], "!"], "hello WORLD !"];
    }


    /**
     * @group        unit
     * @small
     * @dataProvider invokeMethodDataProvider
     *
     * @covers       \Pars\Pattern\PHPUnit\TestCaseClassMemberInvokerTrait::invokeMethod
     *
     * @param        $obj
     * @param string $methodName
     * @param array  $arrParam
     * @param        $expectedValue
     *
     * @throws ReflectionException
     */
    public function testInvokeMethod($obj, string $methodName, array $arrParam, $expectedValue)
    {
        $invokeMethod = new ReflectionMethod($this->object, "invokeMethod");
        $invokeMethod->setAccessible(true);

        $arrInvokeArgParam = [&$obj, $methodName];
        foreach ($arrParam as $param) {
            $arrInvokeArgParam[] = $param;
        }

        $this->assertSame($expectedValue, $invokeMethod->invokeArgs($this->object, $arrInvokeArgParam));
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Pattern\PHPUnit\TestCaseClassMemberInvokerTrait::invokeSetProperty
     * @throws ReflectionException
     */
    public function testInvokeSetProperty_invalidObject()
    {
        $this->object = new class extends DefaultTestCase {
            use TestCaseClassMemberInvokerTrait;
        };

        $object = 'notAnObject';
        $name = "foo";
        $value = "bar";
        $message = 'Can not invoke set property on an invalid object';
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage($message);

        $invokeMethod = new ReflectionMethod($this->object, "invokeSetProperty");
        $invokeMethod->setAccessible(true);

        $invokeMethod->invoke($this->object, $object, $name, $value);
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Pattern\PHPUnit\TestCaseClassMemberInvokerTrait::invokeSetProperty
     * @throws ReflectionException
     */
    public function testInvokeSetProperty_propertyNotFound()
    {
        $this->object = new class extends DefaultTestCase {
            use TestCaseClassMemberInvokerTrait;
        };

        $object = new class {
        };

        $name = "foo";
        $value = "bar";
        $message = '/ReflectionException is thrown on invoking property set \- Property .+::\$foo does not exist/i';
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessageRegExp($message);

        $invokeMethod = new ReflectionMethod($this->object, "invokeSetProperty");
        $invokeMethod->setAccessible(true);

        $invokeMethod->invoke($this->object, $object, $name, $value);
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Pattern\PHPUnit\TestCaseClassMemberInvokerTrait::invokeSetProperty
     * @throws ReflectionException
     */
    public function testInvokeSetProperty()
    {
        $this->object = $this->getMockBuilder(TestCaseClassMemberInvokerTrait::class)->disableOriginalConstructor()->setMethods(
            ["getReflectionProperty_for_Object"]
        )->getMockForTrait();
        $property = $this->getMockBuilder(ReflectionProperty::class)->disableOriginalConstructor()->setMethods(["setAccessible", "setValue"])->getMock();

        $object = new class {
            protected $foo;
        };
        $name = "foo";
        $value = "bar";

        $this->object->expects($this->once())->method("getReflectionProperty_for_Object")->with(...[$object, $name])->willReturn($property);
        $property->expects($this->once())->method("setAccessible")->with(...[true]);
        $property->expects($this->once())->method("setValue")->with(...[$object, $value]);

        $invokeMethod = new ReflectionMethod($this->object, "invokeSetProperty");
        $invokeMethod->setAccessible(true);

        $invokeMethod->invoke($this->object, $object, $name, $value);
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Pattern\PHPUnit\TestCaseClassMemberInvokerTrait::invokeGetProperty
     * @throws ReflectionException
     */
    public function testInvokeGetProperty_invalidObject()
    {
        $this->object = new class extends DefaultTestCase {
            use TestCaseClassMemberInvokerTrait;
        };

        $object = 'notAnObject';
        $name = "foo";
        $value = "bar";
        $message = 'Can not invoke get property on an invalid object';
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage($message);

        $invokeMethod = new ReflectionMethod($this->object, "invokeGetProperty");
        $invokeMethod->setAccessible(true);

        $invokeMethod->invoke($this->object, $object, $name, $value);
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Pattern\PHPUnit\TestCaseClassMemberInvokerTrait::invokeSetProperty
     * @throws ReflectionException
     */
    public function testInvokeGetProperty_propertyNotFound()
    {
        $this->object = new class extends DefaultTestCase {
            use TestCaseClassMemberInvokerTrait;
        };

        $object = new class {
        };

        $name = "foo";
        $value = "bar";
        $message = '/ReflectionException is thrown on invoking property get \- Property .+::\$foo does not exist/i';

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessageRegExp($message);

        $invokeMethod = new ReflectionMethod($this->object, "invokeGetProperty");
        $invokeMethod->setAccessible(true);

        $invokeMethod->invoke($this->object, $object, $name, $value);
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Pattern\PHPUnit\TestCaseClassMemberInvokerTrait::invokeGetProperty
     * @throws ReflectionException
     */
    public function testInvokeGetProperty()
    {
        $this->object = $this->getMockBuilder(TestCaseClassMemberInvokerTrait::class)->disableOriginalConstructor()->setMethods(
            ["getReflectionProperty_for_Object"]
        )->getMockForTrait();
        $property = $this->getMockBuilder(ReflectionProperty::class)->disableOriginalConstructor()->setMethods(["setAccessible", "getValue"])->getMock();

        $object = new class {
            protected $foo = "bar";
        };
        $name = "foo";
        $value = "bar";

        $this->object->expects($this->once())->method("getReflectionProperty_for_Object")->with(...[$object, $name])->willReturn($property);
        $property->expects($this->once())->method("setAccessible")->with(...[true]);
        $property->expects($this->once())->method("getValue")->with(...[$object])->willReturn($value);

        $invokeMethod = new ReflectionMethod($this->object, "invokeGetProperty");
        $invokeMethod->setAccessible(true);

        $this->assertSame($value, $invokeMethod->invoke($this->object, $object, $name));
    }
}

<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\Helper\Object;

use ArrayAccess;
use ArrayObject;
use Generator;
use NiceshopsDev\NiceCore\PHPUnit\DefaultTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ObjectPropertyFinderTest extends DefaultTestCase
{
    
    
    /**
     * @var ObjectPropertyFinder|MockObject
     */
    protected $object;
    
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(ObjectPropertyFinder::class)->disableOriginalConstructor()->getMockForAbstractClass();
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
        $this->assertTrue(class_exists(ObjectPropertyFinder::class), "Class Exists");
        $this->assertTrue(is_a($this->object, ObjectPropertyFinder::class), "Mock Object is set");
    }
    
    
    /**
     * @return Generator
     */
    public function getKeysDataProvider()
    {
        $toArrayObject = new class {
            /** @noinspection PhpUnused */
            public function toArray(): array
            {
                return ["foo" => null, "bar" => "baz", 100];
            }
        };
        
        $object = new class {
            public $foo;
            public $bar = "baz";
            protected $baz = "100";
        };
        
        yield [[], []];
        yield [["foo" => null, "bar" => "baz", 100], ["foo", "bar", 0]];
        yield [(object)["foo" => null, "bar" => "baz", 100], ["foo", "bar", 0]];
        yield [new ArrayObject(["foo" => null, "bar" => "baz", 100]), ["foo", "bar", 0]];
        yield [$toArrayObject, ["foo", "bar", 0]];
        yield [$object, ["foo", "bar"]];
    }
    
    
    /**
     * @group        unit
     * @small
     *
     * @dataProvider getKeysDataProvider
     *
     * @covers       \NiceshopsDev\NiceCore\Helper\Object\ObjectPropertyFinder::getKeys
     *
     * @param       $object
     * @param array $arrExpectedKey
     */
    public function testGetKeys($object, array $arrExpectedKey)
    {
        $this->object = $this->getMockBuilder(ObjectPropertyFinder::class)->disableOriginalConstructor()->setMethods(["getObject"])->getMockForAbstractClass();
        $this->object->expects($this->once())->method("getObject")->with()->willReturn($object);
        
        $this->assertSame($arrExpectedKey, $this->object->getKeys());
    }
    
    
    /**
     * @return Generator
     */
    public function hasKeyDataProvider()
    {
        yield [[], "", false];
        yield [["foo", 0, "99"], "foo", true];
        yield [["foo", 0, "99"], 0, true];
        yield [["foo", 0, "99"], "99", true];
        yield [["foo", 0, "99"], "0", false];
        yield [["foo", 0, "99"], 99, false];
        yield [["foo", 0, "99"], "", false];
    }
    
    
    /**
     * @group        unit
     * @small
     *
     * @dataProvider hasKeyDataProvider
     *
     * @covers       \NiceshopsDev\NiceCore\Helper\Object\ObjectPropertyFinder::hasKey
     *
     * @param array $arrKey
     * @param       $key
     * @param       $expectedValue
     */
    public function testHasKey(array $arrKey, $key, $expectedValue)
    {
        $this->object = $this->getMockBuilder(ObjectPropertyFinder::class)->disableOriginalConstructor()->setMethods(["getKeys"])->getMockForAbstractClass();
        $this->object->expects($this->once())->method("getKeys")->willReturn($arrKey);
        $this->assertSame($expectedValue, $this->object->hasKey($key));
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Helper\Object\ObjectPropertyFinder::getValue
     */
    public function testGetValue_KeyNotFound()
    {
        $this->object = $this->getMockBuilder(ObjectPropertyFinder::class)->disableOriginalConstructor()->setMethods(
            ["getObject", "hasKey"]
        )->getMockForAbstractClass();
        $object = new ArrayObject(["foo" => "bar"]);
        $key = "baz";
        $defaultValue = "bat";
        
        $this->object->expects($this->any())->method("getObject")->with()->willReturn($object);
        $this->object->expects($this->any())->method("hasKey")->with(...[$key])->willReturn(false);
        
        $this->assertSame(null, $this->object->getValue($key));
        $this->assertSame($defaultValue, $this->object->getValue($key, $defaultValue));
    }
    
    
    /**
     * @return Generator
     */
    public function getValueDataProvider_ArrayLikeObjects()
    {
        yield [["foo" => null, "bar" => "baz", 100], "foo", null, null];
        yield [["foo" => null, "bar" => "baz", 100], "foo", "bar", "bar"];
        yield [["foo" => null, "bar" => "baz", 100], "bar", null, "baz"];
        yield [["foo" => null, "bar" => "baz", 100], "bar", "bar", "baz"];
        
        yield [(object)["foo" => null, "bar" => "baz", 100], "foo", null, null];
        yield [(object)["foo" => null, "bar" => "baz", 100], "foo", "bar", "bar"];
        yield [(object)["foo" => null, "bar" => "baz", 100], "bar", null, "baz"];
        yield [(object)["foo" => null, "bar" => "baz", 100], "bar", "bar", "baz"];
        
        yield [new ArrayObject(["foo" => null, "bar" => "baz", 100]), "foo", null, null];
        yield [new ArrayObject(["foo" => null, "bar" => "baz", 100]), "foo", "bar", "bar"];
        yield [new ArrayObject(["foo" => null, "bar" => "baz", 100]), "bar", null, "baz"];
        yield [new ArrayObject(["foo" => null, "bar" => "baz", 100]), "bar", "bar", "baz"];
    }
    
    
    /**
     * @group        unit
     * @small
     *
     * @dataProvider getValueDataProvider_ArrayLikeObjects
     *
     * @covers       \NiceshopsDev\NiceCore\Helper\Object\ObjectPropertyFinder::getValue
     *
     * @param $object
     * @param $key
     * @param $defaultValue
     * @param $expectedValue
     */
    public function testGetValue_ArrayLikeObjects($object, $key, $defaultValue, $expectedValue)
    {
        $this->object = $this->getMockBuilder(ObjectPropertyFinder::class)->disableOriginalConstructor()->setMethods(
            ["getObject", "hasKey"]
        )->getMockForAbstractClass();
        $this->object->expects($this->once())->method("getObject")->with()->willReturn($object);
        $this->object->expects($this->once())->method("hasKey")->with(...[$key])->willReturn(true);
        
        if (null === $defaultValue) {
            $this->assertSame($expectedValue, $this->object->getValue($key));
        } else {
            $this->assertSame($expectedValue, $this->object->getValue($key, $defaultValue));
        }
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Helper\Object\ObjectPropertyFinder::getValue
     */
    public function testGetValue_ArrayAccess()
    {
        $this->object = $this->getMockBuilder(ObjectPropertyFinder::class)->disableOriginalConstructor()->setMethods(
            ["getObject", "hasKey"]
        )->getMockForAbstractClass();
        $object = $this->getMockBuilder(ArrayAccess::class)->setMethods(["offsetGet"])->getMockForAbstractClass();
        $key = "foo";
        $defaultValue = "bat";
        
        $this->object->expects($this->any())->method("getObject")->with()->willReturn($object);
        $this->object->expects($this->any())->method("hasKey")->with(...[$key])->willReturn(true);
        $object->expects($this->exactly(2))->method("offsetGet")->withConsecutive(...[[$key], [$key]])->willReturn(...["bar", null]);
        
        $this->assertSame("bar", $this->object->getValue($key));
        $this->assertSame($defaultValue, $this->object->getValue($key, $defaultValue));
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Helper\Object\ObjectPropertyFinder::getValue
     */
    public function testGetValue_ObjectWithGetDataMethod()
    {
        $this->object = $this->getMockBuilder(ObjectPropertyFinder::class)->disableOriginalConstructor()->setMethods(
            ["getObject", "hasKey"]
        )->getMockForAbstractClass();
        $object = new class {
            /** @noinspection PhpUnused */
            public function getData($key)
            {
                if ($key === "foo") {
                    return "bar";
                }
                
                return null;
            }
        };
        
        $key = "foo";
        $defaultValue = "bat";
        
        $this->object->expects($this->any())->method("getObject")->with()->willReturn($object);
        $this->object->expects($this->any())->method("hasKey")->willReturn(true);
        
        $this->assertSame("bar", $this->object->getValue($key));
        $this->assertSame($defaultValue, $this->object->getValue("__KEY_NOT_DEFINED__", $defaultValue));
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Helper\Object\ObjectPropertyFinder::getValue
     */
    public function testGetValue_GetObjectVars()
    {
        $this->object = $this->getMockBuilder(ObjectPropertyFinder::class)->disableOriginalConstructor()->setMethods(
            ["getObject", "hasKey"]
        )->getMockForAbstractClass();
        $object = new class {
            public $foo = "bar";
            public $bar = null;
            protected $baz = "baz";
        };
        $defaultValue = "bat";
        
        $this->object->expects($this->any())->method("getObject")->with()->willReturn($object);
        $this->object->expects($this->any())->method("hasKey")->willReturn(true);
        
        $this->assertSame("bar", $this->object->getValue("foo"));
        $this->assertSame(null, $this->object->getValue("bar"));
        $this->assertSame($defaultValue, $this->object->getValue("bar", $defaultValue));
        $this->assertSame($defaultValue, $this->object->getValue("baz", $defaultValue));
    }
    
    
    /**
     * @group unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Helper\Object\ObjectPropertyFinder::getValues
     */
    public function testGetValues()
    {
        $this->object = $this->getMockBuilder(ObjectPropertyFinder::class)->disableOriginalConstructor()->setMethods(["getKeys", "getValue"])->getMockForAbstractClass();
        $arrData = ["foo" => "bar", "baz" => "bat", "bak" => "bam"];
        $arrKey = array_keys($arrData);
        $arrGetValue_Param = [];
        $arrGetValue_Return = array_values($arrData);
        foreach($arrData as $key => $val) {
            $arrGetValue_Param[] = [$key];
        }
        
        $this->object->expects($this->once())->method("getKeys")->willReturn($arrKey);
        $this->object->expects($this->exactly(count($arrData)))->method("getValue")->withConsecutive(...$arrGetValue_Param)->willReturn(...$arrGetValue_Return);
        
        $this->assertSame($arrData, $this->object->getValues());
    }
}

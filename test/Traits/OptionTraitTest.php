<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\Traits;

use NiceshopsDev\NiceCore\OptionAwareInterface;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;

/**
 * Class OptionTraitTest
 * @coversDefaultClass OptionTrait
 * @uses               \NiceshopsDev\NiceCore\Traits\OptionTrait
 * @package            Niceshops\Library\Core\Traits
 */
class OptionTraitTest extends TestCase
{
    
    
    /**
     * @var OptionTrait
     */
    protected $object;
    
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(OptionTrait::class)->getMockForTrait();
    }
    
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    
    /**
     * @return array    ( <KEY>, <EXPECTED_KEY> )
     */
    public function normalizeAttributeKeyDataProvider()
    {
        return [
            ["foo", "foo"],
            [" foo ", "foo"],
            ["FOO", "foo"],
            ["Foo", "foo"],
            ["fooBar", "foo_bar"],
            ["FOOBar", "foo_bar"],
            ["fooBAR", "foo_bar"],
            ["foo_Bar", "foo_bar"],
            ["foo_BAR", "foo_bar"],
            ["foo_bar", "foo_bar"],
            ["foo_barBaz", "foo_bar_baz"],
            ["fooBar_Baz", "foo_bar_baz"],
            ["foobar_Baz", "foobar_baz"],
            ["0", "0"],
            ["0foo", "0foo"],
            ["0Foo", "0_foo"],
            ["foo1", "foo1"],
            ["FOO1", "foo1"],
            ["__foo", "__foo"],
            ["__foo__", "__foo__"],
            ["__FooBar__", "__foo_bar__"],
        ];
    }
    
    
    /**
     * @group        unit
     * @small
     *
     * @dataProvider normalizeAttributeKeyDataProvider
     *
     * @covers       \NiceshopsDev\NiceCore\Traits\OptionTrait::normalizeOption()
     *
     * @param string $option
     * @param string $expectedOption
     *
     * @throws ReflectionException
     */
    public function testNormalizeAttributeKey(string $option, string $expectedOption)
    {
        $method = new ReflectionMethod(get_class($this->object), "normalizeOption");
        $method->setAccessible(true);
        
        $this->assertSame($expectedOption, $method->invoke($this->object, $option));
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Traits\OptionTrait::getOptions()
     *
     * @uses   \NiceshopsDev\NiceCore\Traits\OptionTrait::addOption()
     * @uses   \NiceshopsDev\NiceCore\Traits\OptionTrait::removeOption()
     * @uses   \NiceshopsDev\NiceCore\Traits\OptionTrait::unsetOption()
     */
    public function testGetOptions()
    {
        $this->assertEquals([], $this->object->getOptions());
        
        $this->object->addOption("foo");
        $this->object->addOption("bar");
        $this->assertEquals(["foo", "bar"], $this->object->getOptions());
        
        $this->object->removeOption("foo");
        $this->assertEquals(["bar"], $this->object->getOptions());
        
        $this->object->unsetOption("bar");
        $this->assertEquals([], $this->object->getOptions());
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Traits\OptionTrait::getRemovedOptions()
     *
     * @uses   \NiceshopsDev\NiceCore\Traits\OptionTrait::addOption()
     * @uses   \NiceshopsDev\NiceCore\Traits\OptionTrait::removeOption()
     * @uses   \NiceshopsDev\NiceCore\Traits\OptionTrait::unsetOption()
     */
    public function testGetRemovedOptions()
    {
        $this->assertEquals([], $this->object->getRemovedOptions());
        
        $this->object->addOption("foo");
        $this->object->addOption("bar");
        $this->assertEquals([], $this->object->getRemovedOptions());
        
        $this->object->removeOption("foo");
        $this->assertEquals(["foo"], $this->object->getRemovedOptions());
        
        $this->object->unsetOption("bar");
        $this->assertEquals(["foo"], $this->object->getRemovedOptions());
        
        $this->object->unsetOption("foo");
        $this->assertEquals([], $this->object->getRemovedOptions());
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Traits\OptionTrait::clearOptions()
     * @uses   \NiceshopsDev\NiceCore\Traits\OptionTrait::addOption()
     */
    public function testClearOptions()
    {
        $this->assertCount(0, $this->object->getOptions());
        
        $this->object->addOption("foo");
        $this->object->addOption("bar");
        $this->assertCount(2, $this->object->getOptions());
        
        $this->object->clearOptions();
        $this->assertCount(0, $this->object->getOptions());
    }
    
    
    /**
     * @return array    array( <ARRAY_OF_OPTIONS>, <EXPECTED_COUNT>[, <EXPECTED_EXCEPTION>] )
     */
    public function addOptionDataProvider()
    {
        return [
            [
                ["foo"],
                1,
            ],
            [
                ["foo", "bar"],
                2,
            ],
            [
                ["foo", "bar", "FOO", "Bar", " bar", "foo "],
                2,
            ],
            [
                ["fooBar", "foo_bar", "Foo_Bar", "FooBar"],
                1,
            ],
            [
                ["", ""],
                0,
            ],
            [
                ["  ", " ", ""],
                1,
            ],
            [
                ["123", " 123", "123 "],
                1,
            ],
            [
                [123, "123", " 123", "123 "],
                1,
                "Error",
            ],
            [
                [true, 1, "1"],
                1,
                "Error",
            ],
            [
                [false, ""],
                0,
                "Error",
            ],
            [
                [null],
                0,
                "Error"
            ],
            [
                [[]],
                0,
                "Error"
            ],
            [
                [(object)["foo" => "bar"]],
                0,
                "Error"
            ],
        ];
    }
    
    
    /**
     * @group        unit
     * @small
     *
     * @dataProvider addOptionDataProvider
     *
     * @covers       \NiceshopsDev\NiceCore\Traits\OptionTrait::addOption()
     *
     * @param array  $arrOption
     * @param        $expectedCount
     * @param null   $expectedException
     *
     * @uses         \NiceshopsDev\NiceCore\Traits\OptionTrait::getOptions()
     */
    public function testAddOption(array $arrOption, $expectedCount, $expectedException = null)
    {
        if ($expectedException) {
            $this->expectException($expectedException);
        }
        $this->assertCount(0, $this->object->getOptions());
        
        foreach ($arrOption as $option) {
            $this->object->addOption($option);
        }
        
        $this->assertCount($expectedCount, $this->object->getOptions());
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Traits\OptionTrait::removeOption()
     * @uses   \NiceshopsDev\NiceCore\Traits\OptionTrait::addOption()
     * @uses   \NiceshopsDev\NiceCore\Traits\OptionTrait::getOptions()
     */
    public function testRemoveOption()
    {
        $this->assertCount(0, $this->object->getOptions());
        
        $this->object->addOption("foo");
        $this->assertCount(1, $this->object->getOptions());
        
        $this->object->removeOption("foo");
        $this->assertCount(0, $this->object->getOptions());
        
        $this->object->addOption("foo");
        $this->assertCount(1, $this->object->getOptions());
        
        $this->object->removeOption("bar");
        $this->assertCount(1, $this->object->getOptions());
        
        $this->object->removeOption("foo");
        $this->assertCount(0, $this->object->getOptions());
        
        $this->object->removeOption("foo");
        $this->assertCount(0, $this->object->getOptions());
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Traits\OptionTrait::unsetOption()
     * @uses   \NiceshopsDev\NiceCore\Traits\OptionTrait::addOption()
     * @uses   \NiceshopsDev\NiceCore\Traits\OptionTrait::getOptions()
     */
    public function testResetOption()
    {
        $this->assertCount(0, $this->object->getOptions());
        
        $this->object->addOption("foo");
        $this->assertCount(1, $this->object->getOptions());
        
        $this->object->unsetOption("foo");
        $this->assertCount(0, $this->object->getOptions());
        
        $this->object->addOption("foo");
        $this->assertCount(1, $this->object->getOptions());
        
        $this->object->unsetOption("bar");
        $this->assertCount(1, $this->object->getOptions());
        
        $this->object->unsetOption("foo");
        $this->assertCount(0, $this->object->getOptions());
        
        $this->object->unsetOption("foo");
        $this->assertCount(0, $this->object->getOptions());
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Traits\OptionTrait::hasOption()
     * @uses   \NiceshopsDev\NiceCore\Traits\OptionTrait::addOption()
     * @uses   \NiceshopsDev\NiceCore\Traits\OptionTrait::removeOption()
     * @uses   \NiceshopsDev\NiceCore\Traits\OptionTrait::unsetOption()
     */
    public function testHasOption()
    {
        $this->assertFalse($this->object->hasOption("foo"));
        
        $this->object->addOption("foo");
        $this->assertTrue($this->object->hasOption("foo"));
        $this->assertTrue($this->object->hasOption("FOO"));
        $this->assertFalse($this->object->hasOption("bar"));
        
        $this->object->addOption("bar");
        $this->assertTrue($this->object->hasOption("foo"));
        $this->assertTrue($this->object->hasOption("FOO"));
        $this->assertTrue($this->object->hasOption("bar"));
        
        $this->object->removeOption("foo");
        $this->assertFalse($this->object->hasOption("foo"));
        $this->assertFalse($this->object->hasOption("FOO"));
        $this->assertTrue($this->object->hasOption("bar"));
        
        $this->object->removeOption("baz");
        $this->assertFalse($this->object->hasOption("foo"));
        $this->assertFalse($this->object->hasOption("FOO"));
        $this->assertTrue($this->object->hasOption("bar"));
        
        $this->object->removeOption("bar");
        $this->assertFalse($this->object->hasOption("foo"));
        $this->assertFalse($this->object->hasOption("FOO"));
        $this->assertFalse($this->object->hasOption("bar"));
        
        $this->object->addOption("foo");
        $this->assertTrue($this->object->hasOption("foo"));
        $this->assertTrue($this->object->hasOption("FOO"));
        $this->assertFalse($this->object->hasOption("bar"));
        
        $this->object->unsetOption("foo");
        $this->assertFalse($this->object->hasOption("foo"));
        $this->assertFalse($this->object->hasOption("FOO"));
        $this->assertFalse($this->object->hasOption("bar"));
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Traits\OptionTrait::clearOptions()
     * @covers \NiceshopsDev\NiceCore\Traits\OptionTrait::getOptions()
     * @covers \NiceshopsDev\NiceCore\Traits\OptionTrait::getRemovedOptions()
     * @covers \NiceshopsDev\NiceCore\Traits\OptionTrait::addOption()
     * @covers \NiceshopsDev\NiceCore\Traits\OptionTrait::removeOption()
     * @covers \NiceshopsDev\NiceCore\Traits\OptionTrait::unsetOption()
     * @covers \NiceshopsDev\NiceCore\Traits\OptionTrait::hasOption()
     */
    public function testOptionTraitAwareInterface()
    {
        $this->object = new class implements OptionAwareInterface {
            use OptionTrait;
        };
        
        $this->assertSame([], $this->object->getOptions());
        $this->assertSame([], $this->object->getRemovedOptions());
        $this->assertFalse($this->object->hasOption("foo"));
        $this->assertFalse($this->object->hasOption("bar"));
        $this->assertFalse($this->object->hasOption("baz"));
        
        
        /**
         * add some options
         */
        $this->object->addOption("foo");
        $this->object->addOption("bar");
        $this->object->addOption("baz");
        $this->assertSame(["foo", "bar", "baz"], $this->object->getOptions());
        $this->assertSame([], $this->object->getRemovedOptions());
        $this->assertTrue($this->object->hasOption("foo"));
        $this->assertTrue($this->object->hasOption("bar"));
        $this->assertTrue($this->object->hasOption("baz"));
        
        
        /**
         * remove and unset some options
         */
        $this->object->removeOption("foo");
        $this->object->unsetOption("bar");
        $this->assertSame(["baz"], $this->object->getOptions());
        $this->assertSame(["foo"], $this->object->getRemovedOptions());
        $this->assertFalse($this->object->hasOption("foo"));
        $this->assertFalse($this->object->hasOption("bar"));
        $this->assertTrue($this->object->hasOption("baz"));
        
        
        /**
         * clear all options
         */
        $this->object->clearOptions();
        $this->assertSame([], $this->object->getOptions());
        $this->assertSame([], $this->object->getRemovedOptions());
        $this->assertFalse($this->object->hasOption("foo"));
        $this->assertFalse($this->object->hasOption("bar"));
        $this->assertFalse($this->object->hasOption("baz"));
    }
}
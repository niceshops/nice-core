<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Patterns\Option;

use Pars\Patterns\PHPUnit\DefaultTestCase;

/**
 * Class OptionTraitTest
 * @coversDefaultClass OptionAwareTrait
 * @uses               \Pars\Patterns\Option\OptionAwareTrait
 * @package            Pars\Patterns
 */
class OptionAwareTraitTest extends DefaultTestCase
{


    /**
     * @var OptionAwareTrait
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(OptionAwareTrait::class)->getMockForTrait();
        $this->invokeSetProperty($this->object, 'enableNormalization', true);
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
        $this->assertTrue(trait_exists(OptionAwareTrait::class), "Class Exists");
        $this->assertUseTrait(OptionAwareTrait::class, $this->object, "Mock Object uses " . OptionAwareTrait::class);
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
     * @covers       \Pars\Patterns\Option\OptionAwareTrait::normalizeOption()
     *
     * @param string $option
     * @param string $expectedOption
     *
     */
    public function testNormalizeAttributeKey(string $option, string $expectedOption)
    {
        $this->assertSame($expectedOption, $this->invokeMethod($this->object, "normalizeOption", $option));
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Patterns\Option\OptionAwareTrait::getOption_List()
     *
     * @uses   \Pars\Patterns\Option\OptionAwareTrait::addOption()
     * @uses   \Pars\Patterns\Option\OptionAwareTrait::removeOption()
     * @uses   \Pars\Patterns\Option\OptionAwareTrait::unsetOption()
     */
    public function testGetOptions()
    {
        $this->assertEquals([], $this->object->getOption_List());

        $this->object->addOption("foo");
        $this->object->addOption("bar");
        $this->assertEquals(["foo", "bar"], $this->object->getOption_List());

        $this->object->removeOption("foo");
        $this->assertEquals(["bar"], $this->object->getOption_List());

        $this->object->unsetOption("bar");
        $this->assertEquals([], $this->object->getOption_List());
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Patterns\Option\OptionAwareTrait::getRemovedOption_List()
     *
     * @uses   \Pars\Patterns\Option\OptionAwareTrait::addOption()
     * @uses   \Pars\Patterns\Option\OptionAwareTrait::removeOption()
     * @uses   \Pars\Patterns\Option\OptionAwareTrait::unsetOption()
     */
    public function testGetRemovedOptions()
    {
        $this->assertEquals([], $this->object->getRemovedOption_List());

        $this->object->addOption("foo");
        $this->object->addOption("bar");
        $this->assertEquals([], $this->object->getRemovedOption_List());

        $this->object->removeOption("foo");
        $this->assertEquals(["foo"], $this->object->getRemovedOption_List());

        $this->object->unsetOption("bar");
        $this->assertEquals(["foo"], $this->object->getRemovedOption_List());

        $this->object->unsetOption("foo");
        $this->assertEquals([], $this->object->getRemovedOption_List());
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Patterns\Option\OptionAwareTrait::clearOptions()
     * @uses   \Pars\Patterns\Option\OptionAwareTrait::addOption()
     */
    public function testClearOptions()
    {
        $this->assertCount(0, $this->object->getOption_List());

        $this->object->addOption("foo");
        $this->object->addOption("bar");
        $this->assertCount(2, $this->object->getOption_List());

        $this->object->clearOptions();
        $this->assertCount(0, $this->object->getOption_List());
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
                0,
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
     * @covers       \Pars\Patterns\Option\OptionAwareTrait::addOption()
     *
     * @param array  $arrOption
     * @param        $expectedCount
     * @param null   $expectedException
     *
     * @uses         \Pars\Patterns\Option\OptionAwareTrait::getOption_List()
     */
    public function testAddOption(array $arrOption, $expectedCount, $expectedException = null)
    {
        if ($expectedException) {
            $this->expectException($expectedException);
        }
        $this->assertCount(0, $this->object->getOption_List());

        foreach ($arrOption as $option) {
            $this->object->addOption($option);
        }

        $this->assertCount($expectedCount, $this->object->getOption_List());
    }

    /**
     * @group        unit
     * @small
     *
     * @dataProvider addOptionDataProvider
     *
     * @covers       \Pars\Patterns\Option\OptionAwareTrait::addOption()
     *
     * @param array  $arrOption
     * @param        $expectedCount
     * @param null   $expectedException
     *
     * @uses         \Pars\Patterns\Option\OptionAwareTrait::getOption_List()
     */
    public function testAddOption_List(array $arrOption, $expectedCount, $expectedException = null)
    {
        if ($expectedException) {
            $this->expectException($expectedException);
        }
        $this->assertCount(0, $this->object->getOption_List());
        $this->object->addOption_List($arrOption);
        $this->assertCount($expectedCount, $this->object->getOption_List());
        foreach ($arrOption as $item) {
            if (strlen(trim($item)) > 0) {
                $this->assertTrue($this->object->hasOption($item));
            }
        }
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Patterns\Option\OptionAwareTrait::removeOption()
     * @uses   \Pars\Patterns\Option\OptionAwareTrait::addOption()
     * @uses   \Pars\Patterns\Option\OptionAwareTrait::getOption_List()
     */
    public function testRemoveOption()
    {
        $this->assertCount(0, $this->object->getOption_List());

        $this->object->addOption("foo");
        $this->assertCount(1, $this->object->getOption_List());

        $this->object->removeOption("foo");
        $this->assertCount(0, $this->object->getOption_List());

        $this->object->addOption("foo");
        $this->assertCount(1, $this->object->getOption_List());

        $this->object->removeOption("bar");
        $this->assertCount(1, $this->object->getOption_List());

        $this->object->removeOption("foo");
        $this->assertCount(0, $this->object->getOption_List());

        $this->object->removeOption("foo");
        $this->assertCount(0, $this->object->getOption_List());
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Patterns\Option\OptionAwareTrait::unsetOption()
     * @uses   \Pars\Patterns\Option\OptionAwareTrait::addOption()
     * @uses   \Pars\Patterns\Option\OptionAwareTrait::getOption_List()
     */
    public function testResetOption()
    {
        $this->assertCount(0, $this->object->getOption_List());

        $this->object->addOption("foo");
        $this->assertCount(1, $this->object->getOption_List());

        $this->object->unsetOption("foo");
        $this->assertCount(0, $this->object->getOption_List());

        $this->object->addOption("foo");
        $this->assertCount(1, $this->object->getOption_List());

        $this->object->unsetOption("bar");
        $this->assertCount(1, $this->object->getOption_List());

        $this->object->unsetOption("foo");
        $this->assertCount(0, $this->object->getOption_List());

        $this->object->unsetOption("foo");
        $this->assertCount(0, $this->object->getOption_List());
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Patterns\Option\OptionAwareTrait::hasOption()
     * @uses   \Pars\Patterns\Option\OptionAwareTrait::addOption()
     * @uses   \Pars\Patterns\Option\OptionAwareTrait::removeOption()
     * @uses   \Pars\Patterns\Option\OptionAwareTrait::unsetOption()
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
     * @covers \Pars\Patterns\Option\OptionAwareTrait::clearOptions()
     * @covers \Pars\Patterns\Option\OptionAwareTrait::getOption_List()
     * @covers \Pars\Patterns\Option\OptionAwareTrait::getRemovedOption_List()
     * @covers \Pars\Patterns\Option\OptionAwareTrait::addOption()
     * @covers \Pars\Patterns\Option\OptionAwareTrait::removeOption()
     * @covers \Pars\Patterns\Option\OptionAwareTrait::unsetOption()
     * @covers \Pars\Patterns\Option\OptionAwareTrait::hasOption()
     */
    public function testOptionTraitAwareInterface()
    {
        $this->object = new class implements OptionAwareInterface {
            use OptionAwareTrait;
        };

        $this->assertSame([], $this->object->getOption_List());
        $this->assertSame([], $this->object->getRemovedOption_List());
        $this->assertFalse($this->object->hasOption("foo"));
        $this->assertFalse($this->object->hasOption("bar"));
        $this->assertFalse($this->object->hasOption("baz"));


        /**
         * add some options
         */
        $this->object->addOption("foo");
        $this->object->addOption("bar");
        $this->object->addOption("baz");
        $this->assertSame(["foo", "bar", "baz"], $this->object->getOption_List());
        $this->assertSame([], $this->object->getRemovedOption_List());
        $this->assertTrue($this->object->hasOption("foo"));
        $this->assertTrue($this->object->hasOption("bar"));
        $this->assertTrue($this->object->hasOption("baz"));


        /**
         * remove and unset some options
         */
        $this->object->removeOption("foo");
        $this->object->unsetOption("bar");
        $this->assertSame(["baz"], $this->object->getOption_List());
        $this->assertSame(["foo"], $this->object->getRemovedOption_List());
        $this->assertFalse($this->object->hasOption("foo"));
        $this->assertFalse($this->object->hasOption("bar"));
        $this->assertTrue($this->object->hasOption("baz"));


        /**
         * clear all options
         */
        $this->object->clearOptions();
        $this->assertSame([], $this->object->getOption_List());
        $this->assertSame([], $this->object->getRemovedOption_List());
        $this->assertFalse($this->object->hasOption("foo"));
        $this->assertFalse($this->object->hasOption("bar"));
        $this->assertFalse($this->object->hasOption("baz"));
    }
}

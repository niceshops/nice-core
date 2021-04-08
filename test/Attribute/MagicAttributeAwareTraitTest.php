<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Pattern\Attribute;

use Pars\Pattern\Exception\CoreException;
use Pars\Pattern\PHPUnit\DefaultTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionException;

/**
 * Class AttributeTraitTest
 * @coversDefaultClass AttributeAwareTrait
 * @uses               \Pars\Pattern\Attribute\AttributeAwareTrait
 * @package            Pars\Pattern
 */
class MagicAttributeAwareTraitTest extends DefaultTestCase
{


    /**
     * @var AttributeAwareTrait|MockObject
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(MagicAttributeAwareTrait::class)->getMockForTrait();
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
        $this->assertTrue(trait_exists(MagicAttributeAwareTrait::class), "Class Exists");
        $this->assertUseTrait(MagicAttributeAwareTrait::class, $this->object, "Mock Object uses " . MagicAttributeAwareTrait::class);
    }


   /**
     * @group        unit
     * @small
     *
     * @covers       \Pars\Pattern\Attribute\MagicAttributeAwareTrait::__call()
     * @throws ReflectionException
     * @throws CoreException
     * @uses         \Pars\Pattern\Attribute\AttributeAwareTrait::getAttribute()
     * @noinspection PhpUndefinedMethodInspection
     */
    public function testMagicAttributeAccess_with_Call()
    {
        $this->object->setFoo("bar");
        $this->assertSame("bar", $this->object->getAttribute("foo"));
        $this->assertSame("bar", $this->object->getFoo());
    }


    /**
     * @group        unit
     * @small
     *
     * @covers       \Pars\Pattern\Attribute\MagicAttributeAwareTrait::__get()
     * @throws CoreException
     * @uses         \Pars\Pattern\Attribute\AttributeAwareTrait::getAttribute()
     * @uses         \Pars\Pattern\Attribute\AttributeAwareTrait::setAttribute()
     * @noinspection PhpUndefinedFieldInspection
     */
    public function testMagicAttributeAccess_with_Get()
    {
        $this->object->setAttribute("foo", "bar");
        $this->assertSame("bar", $this->object->getAttribute("foo"));
        $this->assertSame("bar", $this->object->foo);
    }


    /**
     * @group        unit
     * @small
     *
     * @covers       \Pars\Pattern\Attribute\MagicAttributeAwareTrait::__set()
     * @throws CoreException
     * @uses         \Pars\Pattern\Attribute\AttributeAwareTrait::getAttribute()
     * @noinspection PhpUndefinedFieldInspection
     */
    public function testMagicAttributeAccess_with_Set()
    {
        $this->object->foo = "bar";
        $this->assertSame("bar", $this->object->getAttribute("foo"));
    }
}

<?php

declare(strict_types=1);

/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Core\Attribute;

use Niceshops\Core\Exception\CoreException;
use Niceshops\Core\PHPUnit\DefaultTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionException;

/**
 * Class AttributeTraitTest
 * @coversDefaultClass AttributeAwareTrait
 * @uses               \Niceshops\Core\Attribute\AttributeAwareTrait
 * @package            Niceshops\Core
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
     * @covers       \Niceshops\Core\Attribute\MagicAttributeAwareTrait::__call()
     * @throws ReflectionException
     * @throws CoreException
     * @uses         \Niceshops\Core\Attribute\AttributeAwareTrait::getAttribute()
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
     * @covers       \Niceshops\Core\Attribute\MagicAttributeAwareTrait::__get()
     * @throws CoreException
     * @uses         \Niceshops\Core\Attribute\AttributeAwareTrait::getAttribute()
     * @uses         \Niceshops\Core\Attribute\AttributeAwareTrait::setAttribute()
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
     * @covers       \Niceshops\Core\Attribute\MagicAttributeAwareTrait::__set()
     * @throws CoreException
     * @uses         \Niceshops\Core\Attribute\AttributeAwareTrait::getAttribute()
     * @noinspection PhpUndefinedFieldInspection
     */
    public function testMagicAttributeAccess_with_Set()
    {
        $this->object->foo = "bar";
        $this->assertSame("bar", $this->object->getAttribute("foo"));
    }
}

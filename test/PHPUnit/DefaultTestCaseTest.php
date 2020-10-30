<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Core\PHPUnit;

use Niceshops\Core\Attribute\AttributeAwareTrait;
use Niceshops\Core\Option\OptionAwareTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class DefaultTestCaseTest
 * @package Core
 */
class DefaultTestCaseTest extends TestCase
{

    use TestCaseClassMemberInvokerTrait;


    /**
     * @var DefaultTestCase|MockObject
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(DefaultTestCase::class)->disableOriginalConstructor()->getMockForAbstractClass();
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
        $this->assertTrue(class_exists(DefaultTestCase::class), "Class Exists");
        $this->assertTrue(is_a($this->object, DefaultTestCase::class), "Mock Object is set");
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Niceshops\Core\PHPUnit\DefaultTestCase::classUseTrait
     */
    public function testAssertUseTrait()
    {
        $objUseAttributeTrait = new class {
            use AttributeAwareTrait;
        };

        $objUseOptionAndAttributeTrait = new class {
            use AttributeAwareTrait;
            use OptionAwareTrait;
        };

        $objUseNoTrait = new class() {
        };

        $this->assertTrue($this->invokeMethod($this->object, "classUseTrait", $objUseAttributeTrait, AttributeAwareTrait::class));
        $this->assertFalse($this->invokeMethod($this->object, "classUseTrait", $objUseAttributeTrait, OptionAwareTrait::class));
        $this->assertTrue($this->invokeMethod($this->object, "classUseTrait", $objUseOptionAndAttributeTrait, OptionAwareTrait::class));
        $this->assertFalse($this->invokeMethod($this->object, "classUseTrait", $objUseNoTrait, AttributeAwareTrait::class));
    }

}

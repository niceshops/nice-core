<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\PHPUnit;

use NiceshopsDev\NiceCore\Attribute\AttributeTrait;
use NiceshopsDev\NiceCore\Option\OptionTrait;
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
     * @covers DefaultTestCase::classUseTrait
     */
    public function testAssertUseTrait()
    {
        $objUseAttributeTrait = new class {
            use AttributeTrait;
        };
        
        $objUseOptionAndAttributeTrait = new class {
            use AttributeTrait;
            use OptionTrait;
        };
        
        $objUseNoTrait = new class() {
        };
        
        $this->assertTrue($this->invokeMethod($this->object, "classUseTrait", $objUseAttributeTrait, AttributeTrait::class));
        $this->assertFalse($this->invokeMethod($this->object, "classUseTrait", $objUseAttributeTrait, OptionTrait::class));
        $this->assertTrue($this->invokeMethod($this->object, "classUseTrait", $objUseOptionAndAttributeTrait, OptionTrait::class));
        $this->assertFalse($this->invokeMethod($this->object, "classUseTrait", $objUseNoTrait, AttributeTrait::class));
    }
    
}
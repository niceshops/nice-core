<?php
/**
 * @see       https://github.com/niceshops-dev/NiceCore for the canonical source repository
 * @license   https://github.com/niceshops-dev/NiceCore/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ExceptionTest
 * @package Core
 */
class ExceptionTest extends TestCase
{
    
    
    /**
     * @var Exception|MockObject
     */
    protected $object;
    
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(Exception::class)->disableOriginalConstructor()->getMockForAbstractClass();
    }
    
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Exception::setMessage()
     * @uses   \NiceshopsDev\NiceCore\Exception::getMessage()
     */
    public function testSetMessage()
    {
        $message = "foo bar baz";
        
        $this->assertSame("", $this->object->getMessage());
        
        $this->object->setMessage($message);
        $this->assertSame($message, $this->object->getMessage());
        
        try {
            throw $this->object;
        } catch (Exception $e) {
            $this->assertSame($message, $e->getMessage());
        }
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Exception::appendToMessage()
     * @uses   \NiceshopsDev\NiceCore\Exception::getMessage()
     */
    public function testAppendToMessage()
    {
        $this->assertSame("", $this->object->getMessage());
        
        $this->object->appendToMessage("foo");
        $this->assertSame("foo", $this->object->getMessage());
        
        $this->object->appendToMessage("bar");
        $this->assertSame("foo bar", $this->object->getMessage());
        
        $this->object->appendToMessage("baz", " - ");
        $this->assertSame("foo bar - baz", $this->object->getMessage());
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Exception::prependToMessage()
     * @uses   \NiceshopsDev\NiceCore\Exception::getMessage()
     */
    public function testPrependToMessage()
    {
        $this->assertSame("", $this->object->getMessage());
        
        $this->object->prependToMessage("foo");
        $this->assertSame("foo", $this->object->getMessage());
        
        $this->object->prependToMessage("bar");
        $this->assertSame("bar foo", $this->object->getMessage());
        
        $this->object->prependToMessage("baz", " - ");
        $this->assertSame("baz - bar foo", $this->object->getMessage());
    }
    
}
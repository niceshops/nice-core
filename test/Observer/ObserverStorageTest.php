<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\Observer;

use Generator;
use NiceshopsDev\NiceCore\PHPUnit\DefaultTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use SplObserver;

/**
 * UnitTest class for TraversableRunner
 * @coversDefaultClass  ObserverStorage
 * @uses                ObserverStorage
 * @package             NiceshopsDev\NiceCore
 */
class ObserverStorageTest extends DefaultTestCase
{
    
    
    /**
     * @var ObserverStorage|MockObject
     */
    protected $object;
    
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(ObserverStorage::class)->disableOriginalConstructor()->getMockForAbstractClass();
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
        $this->assertTrue(class_exists(ObserverStorage::class), "Class Exists");
        $this->assertTrue(is_a($this->object, ObserverStorage::class), "Mock Object is set");
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Observer\ObserverStorage::addObserver
     */
    public function testAddObserver()
    {
        $this->object = $this->getMockBuilder(ObserverStorage::class)->disableOriginalConstructor()->setMethods(["addComponent"])->getMockForAbstractClass();
        /**
         * @var SplObserver|MockObject $observer
         */
        $observer = $this->getMockBuilder(SplObserver::class)->getMock();
        
        $this->object->expects($this->once())->method("addComponent")->with(...[$observer])->willReturn($this->object);
        
        $this->assertSame($this->object, $this->object->addObserver($observer));
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Observer\ObserverStorage::removeObserver
     */
    public function testRemoveObserver()
    {
        $this->object = $this->getMockBuilder(ObserverStorage::class)->disableOriginalConstructor()->setMethods(["removeObserver"])->getMockForAbstractClass();
        /**
         * @var SplObserver|MockObject $observer
         */
        $observer = $this->getMockBuilder(SplObserver::class)->getMock();
        
        $this->object->expects($this->once())->method("removeObserver")->with(...[$observer])->willReturn($this->object);
        
        $this->assertSame($this->object, $this->object->removeObserver($observer));
    }
    
    
    /**
     * @return Generator
     */
    public function runObserverDataProvider()
    {
        yield [[], []];
        yield [["foo", "bar", "baz"], ["foo", "bar", "baz"]];
        yield [["foo" => "bar", "baz" => "bat"], ["bar", "bat"]];
    }
    
    
    /**
     * @group        unit
     * @small
     *
     * @dataProvider runObserverDataProvider
     *
     * @covers       \NiceshopsDev\NiceCore\Observer\ObserverStorage::runObserver
     *
     * @param array $arrComponent_List
     * @param array $arrExpectedComponent_List
     */
    public function testRunObserver(array $arrComponent_List, array $arrExpectedComponent_List)
    {
        $this->object = $this->getMockBuilder(ObserverStorage::class)->disableOriginalConstructor()->setMethods(["getComponent_List"])->getMockForAbstractClass(
        );
        
        $this->object->expects($this->once())->method("getComponent_List")->willReturn($arrComponent_List);
        
        $arrActualComponent_List = [];
        foreach ($this->object->runObserver() as $observer) {
            $arrActualComponent_List[] = $observer;
        }
        
        $this->assertSame($arrExpectedComponent_List, $arrActualComponent_List);
    }
}
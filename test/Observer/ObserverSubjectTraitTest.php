<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\Observer;

use NiceshopsDev\NiceCore\PHPUnit\DefaultTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use SplObserver;
use SplSubject;

/**
 * UnitTest class for TraversableRunner
 * @coversDefaultClass  ObserverSubjectTrait
 * @uses                ObserverSubjectTrait
 * @package             NiceshopsDev\NiceCore
 */
class ObserverSubjectTraitTest extends DefaultTestCase
{
    
    
    /**
     * @var ObserverSubjectTrait|MockObject
     */
    protected $object;
    
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(ObserverSubjectTrait::class)->disableOriginalConstructor()->getMockForTrait();
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
        $this->assertTrue(trait_exists(ObserverSubjectTrait::class), "Class Exists");
        $this->assertUseTrait(ObserverSubjectTrait::class, $this->object, "Mock Object uses " . ObserverSubjectTrait::class);
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers ObserverSubjectTrait::getObserverStorage
     */
    public function testGetObserverStorage()
    {
        $observerStorage = $this->invokeMethod($this->object, "getObserverStorage");
        $this->assertInstanceOf(ObserverStorage::class, $observerStorage);
        $this->assertSame($observerStorage, $this->invokeMethod($this->object, "getObserverStorage"));
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers ObserverSubjectTrait::attach
     */
    public function testAttach()
    {
        $observerStorage = $this->getMockBuilder(ObserverStorage::class)->disableOriginalConstructor()->setMethods(["addObserver"])->getMock();
        
        /**
         * @var SplObserver $observer
         */
        $observer = $this->getMockBuilder(SplObserver::class)->getMock();
        
        $this->invokeSetProperty($this->object, "observerStorage", $observerStorage);
        $observerStorage->expects($this->once())->method("addObserver")->with(...[$observer]);
        
        $this->assertSame($this->object, $this->object->attach($observer));
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers ObserverSubjectTrait::c
     */
    public function testDetach()
    {
        $observerStorage = $this->getMockBuilder(ObserverStorage::class)->disableOriginalConstructor()->setMethods(["removeObserver"])->getMock();
        
        /**
         * @var SplObserver|MockObject $observer
         */
        $observer = $this->getMockBuilder(SplObserver::class)->getMock();
        
        $this->invokeSetProperty($this->object, "observerStorage", $observerStorage);
        $observerStorage->expects($this->once())->method("removeObserver")->with(...[$observer]);
        
        $this->assertSame($this->object, $this->object->detach($observer));
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers ObserverSubjectTrait::notify
     */
    public function testNotify()
    {
        $observerStorage = $this->getMockBuilder(ObserverStorage::class)->disableOriginalConstructor()->setMethods(["runObserver"])->getMock();
        
        $this->object = new class implements SplSubject {
            use ObserverSubjectTrait;
        };
        $this->invokeSetProperty($this->object, "observerStorage", $observerStorage);
        
        /**
         * @var SplObserver[]|MockObject[] $arrObserver
         */
        $arrObserver = [
            $this->getMockBuilder(SplObserver::class)->setMethods(["update"])->getMock(),
            $this->getMockBuilder(SplObserver::class)->setMethods(["update"])->getMock(),
            $this->getMockBuilder(SplObserver::class)->setMethods(["update"])->getMock(),
        ];
        
        foreach ($arrObserver as $observer) {
            $observer->expects($this->once())->method("update")->with(...[$this->object]);
        }
        
        $observerStorage->expects($this->once())->method("runObserver")->willReturn($arrObserver);
        
        $this->assertSame($this->object, $this->object->notify());
    }
}
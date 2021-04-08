<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Pattern\Observer;

use ArrayObject;
use Pars\Pattern\PHPUnit\DefaultTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use SplSubject;

/**
 * UnitTest class for TraversableRunner
 * @coversDefaultClass  \Pars\Pattern\Observer\AbstractSubjectModifiedObserver
 * @uses                \Pars\Pattern\Observer\AbstractSubjectModifiedObserver
 * @package             Pars\Pattern
 */
class AbstractSubjectModifiedObserverTest extends DefaultTestCase
{


    /**
     * @var AbstractSubjectModifiedObserver|MockObject
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(AbstractSubjectModifiedObserver::class)->disableOriginalConstructor()->getMockForAbstractClass();
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
        $this->assertTrue(class_exists(AbstractSubjectModifiedObserver::class), "Class Exists");
        $this->assertTrue(is_a($this->object, AbstractSubjectModifiedObserver::class), "Mock Object is set");
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Pattern\Observer\AbstractSubjectModifiedObserver::update
     */
    public function testUpdate()
    {
        $this->object = $this->getMockBuilder(AbstractSubjectModifiedObserver::class)->disableOriginalConstructor()->setMethods(
            ["addComponent"]
        )->getMockForAbstractClass();

        /**
         * @var SplSubject|MockObject $subject
         */
        $subject = $this->getMockBuilder(SplSubject::class)->getMock();

        $this->object->expects($this->once())->method("addComponent")->with(...[$subject])->willReturn($this->object);

        $this->assertSame($this->object, $this->object->update($subject));
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Pattern\Observer\AbstractSubjectModifiedObserver::reset
     */
    public function testReset()
    {
        $this->object = $this->getMockBuilder(AbstractSubjectModifiedObserver::class)->disableOriginalConstructor()->setMethods(
            ["getComponent_List"]
        )->getMockForAbstractClass();
        $componentList = $this->getMockBuilder(ArrayObject::class)->setMethods(["exchangeArray"])->getMock();

        $this->object->expects($this->once())->method("getComponent_List")->willReturn($componentList);
        $componentList->expects($this->once())->method("exchangeArray")->with(...[[]]);

        $this->assertSame($this->object, $this->object->reset());
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Pattern\Observer\AbstractSubjectModifiedObserver::getModifiedSubject_List
     */
    public function testGetModifiedSubject_List()
    {
        $this->object = $this->getMockBuilder(AbstractSubjectModifiedObserver::class)->disableOriginalConstructor()->setMethods(
            ["getComponent_List"]
        )->getMockForAbstractClass();
        $componentList = $this->getMockBuilder(ArrayObject::class)->setMethods(["getArrayCopy"])->getMock();
        $arrComponentList = ["foo", "bar", "baz"];

        $this->object->expects($this->once())->method("getComponent_List")->willReturn($componentList);
        $componentList->expects($this->once())->method("getArrayCopy")->willReturn($arrComponentList);

        $this->assertSame($arrComponentList, $this->invokeMethod($this->object, "getModifiedSubject_List"));
    }
}

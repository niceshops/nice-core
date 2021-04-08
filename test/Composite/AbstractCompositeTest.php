<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Patterns\Composite;

use ArrayObject;
use Countable;
use Pars\Patterns\PHPUnit\DefaultTestCase;
use ReflectionException;
use ReflectionMethod;
use stdClass;

/**
 * Class AbstractCompositeTest
 * @coversDefaultClass AbstractComposite
 * @uses               \Pars\Patterns\Composite\AbstractComposite
 * @package            Pars\Patterns
 */
class AbstractCompositeTest extends DefaultTestCase
{

    /**
     * @var AbstractComposite
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(AbstractComposite::class)->disableOriginalConstructor()->getMockForAbstractClass();
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
        $this->assertTrue(class_exists(AbstractComposite::class), "Class Exists");
        $this->assertTrue(is_a($this->object, AbstractComposite::class), "Mock Object is set");
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Patterns\Composite\AbstractComposite::getComponent_List()
     */
    public function testGetComponent_List()
    {
        $this->assertInstanceOf(ArrayObject::class, $this->invokeMethod($this->object, "getComponent_List"));
        $this->assertSame(0, count($this->invokeMethod($this->object, "getComponent_List")));
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Patterns\Composite\AbstractComposite::hasComponent()
     * @throws ReflectionException
     * @uses   \Pars\Patterns\Composite\AbstractComposite::getComponent_List()
     */
    public function testHasComponent()
    {
        $hasComponent = new ReflectionMethod(get_class($this->object), "hasComponent");
        $hasComponent->setAccessible(true);

        $component = new stdClass();
        $component->foo = "bar";

        $this->assertFalse($hasComponent->invoke($this->object, $component));
        $this->invokeMethod($this->object, "getComponent_List")->append($component);
        $this->assertTrue($this->invokeMethod($this->object, "hasComponent", $component));
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Patterns\Composite\AbstractComposite::addComponent()
     * @uses   \Pars\Patterns\Composite\AbstractComposite::getComponent_List()
     */
    public function testAddComponent()
    {
        $component = new stdClass();
        $component->foo = "bar";

        $this->assertSame(0, count($this->invokeMethod($this->object, "getComponent_List")));
        $this->invokeMethod($this->object, "addComponent", $component);
        $this->assertSame(1, count($this->invokeMethod($this->object, "getComponent_List")));
        $this->invokeMethod($this->object, "addComponent", $component);
        $this->assertSame(1, count($this->invokeMethod($this->object, "getComponent_List")));
        $this->invokeMethod($this->object, "addComponent", clone $component);
        $this->assertSame(2, count($this->invokeMethod($this->object, "getComponent_List")));
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Patterns\Composite\AbstractComposite::removeComponent()
     * @uses   \Pars\Patterns\Composite\AbstractComposite::getComponent_List()
     */
    public function testRemoveComponent()
    {
        $component = new stdClass();
        $component->foo = "bar";

        $component2 = new stdClass();
        $component2->bar = "baz";

        $this->assertSame(0, count($this->invokeMethod($this->object, "getComponent_List")));
        $this->invokeMethod($this->object, "getComponent_List")->append($component);
        $this->invokeMethod($this->object, "getComponent_List")->append($component2);
        $this->assertSame(2, count($this->invokeMethod($this->object, "getComponent_List")));
        $this->assertSame($component, $this->invokeMethod($this->object, "getComponent_List")->offsetGet(0));
        $this->assertSame($component2, $this->invokeMethod($this->object, "getComponent_List")->offsetGet(1));

        $this->invokeMethod($this->object, "removeComponent", $component);
        $this->assertSame(1, count($this->invokeMethod($this->object, "getComponent_List")));
        $this->assertArrayNotHasKey(0, $this->invokeMethod($this->object, "getComponent_List"));
        $this->assertArrayHasKey(1, $this->invokeMethod($this->object, "getComponent_List"));
        $this->assertSame($component2, $this->invokeMethod($this->object, "getComponent_List")->offsetGet(1));

        $this->invokeMethod($this->object, "removeComponent", $component);
        $this->assertSame(1, count($this->invokeMethod($this->object, "getComponent_List")));
        $this->assertArrayNotHasKey(0, $this->invokeMethod($this->object, "getComponent_List"));
        $this->assertArrayHasKey(1, $this->invokeMethod($this->object, "getComponent_List"));
        $this->assertSame($component2, $this->invokeMethod($this->object, "getComponent_List")->offsetGet(1));

        $this->invokeMethod($this->object, "removeComponent", $component2);
        $this->assertSame(0, count($this->invokeMethod($this->object, "getComponent_List")));
        $this->assertArrayNotHasKey(0, $this->invokeMethod($this->object, "getComponent_List"));
        $this->assertArrayNotHasKey(1, $this->invokeMethod($this->object, "getComponent_List"));
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Pars\Patterns\Composite\AbstractComposite::addComponent()
     * @covers \Pars\Patterns\Composite\AbstractComposite::hasComponent()
     * @covers \Pars\Patterns\Composite\AbstractComposite::removeComponent()
     */
    public function testConcreteImplementation()
    {
        $defaultBean = new class () implements Countable {

            /**
             * @var array
             */
            private $arrData = [];


            /**
             * @param string $key
             * @param        $value
             */
            public function setData(string $key, $value)
            {
                $this->arrData[$key] = $value;
            }


            /**
             * @return int
             */
            public function count(): int
            {
                return count($this->arrData);
            }
        };


        $beanComposite = new class extends AbstractComposite implements Countable {
            /**
             * @return Countable[]
             */
            protected function getComponent_List()
            {
                return parent::getComponent_List();
            }


            /**
             * @param Countable $bean
             *
             * @return AbstractComposite
             */
            public function addBean(Countable $bean)
            {
                return $this->addComponent($bean);
            }


            /**
             * @param Countable $bean
             *
             * @return bool
             */
            public function hasBean(Countable $bean)
            {
                return $this->hasComponent($bean);
            }


            /**
             * @param Countable $bean
             *
             * @return $this|AbstractComposite
             */
            public function removeBean(Countable $bean)
            {
                return $this->removeComponent($bean);
            }


            /**
             * @param string $name
             * @param mixed  $value
             *
             * @return $this|AbstractComposite
             */
            public function setData(string $name, $value)
            {
                foreach ($this->getComponent_List() as $bean) {
                    if (method_exists($bean, "setData")) {
                        $bean->setData($name, $value);
                    }
                }

                return $this;
            }


            /**
             * @return int
             */
            public function count(): int
            {
                $count = 0;
                foreach ($this->getComponent_List() as $bean) {
                    $count += $bean->count();
                }
                return $count;
            }
        };


        $bean = clone $defaultBean;
        $bean->setData("foo", "bar");

        $bean2 = clone $bean;
        $bean2->setData("foo", "baz");
        $bean2->setData("bar", "bat");

        $this->assertFalse($beanComposite->hasBean($bean));
        $this->assertFalse($beanComposite->hasBean($bean2));

        $beanComposite->addBean($bean);
        $this->assertTrue($beanComposite->hasBean($bean));
        $this->assertFalse($beanComposite->hasBean($bean2));

        $beanComposite->addBean($bean2);
        $this->assertTrue($beanComposite->hasBean($bean));
        $this->assertTrue($beanComposite->hasBean($bean2));

        //  Countable interface
        $this->assertCount(3, $beanComposite);

        $beanComposite->removeBean($bean);
        $this->assertFalse($beanComposite->hasBean($bean));
        $this->assertTrue($beanComposite->hasBean($bean2));

        //  Countable interface
        $this->assertCount(2, $beanComposite);

        $beanComposite->removeBean($bean2);
        $this->assertFalse($beanComposite->hasBean($bean));
        $this->assertFalse($beanComposite->hasBean($bean2));

        //  Countable interface
        $this->assertCount(0, $beanComposite);


        $bean2->setData("baz", "bak");
        $beanComposite->addBean($bean);
        $beanComposite->addBean($bean2);

        //  Countable interface
        $this->assertCount(4, $beanComposite);

        $beanComposite->setData("bar", "baz");

        //  Countable interface
        $this->assertCount(5, $beanComposite);

        $beanComposite->removeBean($bean2);

        //  Countable interface
        $this->assertCount(2, $beanComposite);
    }
}

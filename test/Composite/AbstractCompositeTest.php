<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\Composite;

use ArrayObject;
use Countable;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;
use stdClass;

/**
 * Class AbstractCompositeTest
 * @coversDefaultClass AbstractComposite
 * @uses               \NiceshopsDev\NiceCore\Composite\AbstractComposite
 * @package            Niceshops\Library\Core
 */
class AbstractCompositeTest extends TestCase
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
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Composite\AbstractComposite::getComponent_List()
     * @throws ReflectionException
     */
    public function testGetComponent_List()
    {
        $getComponent_List = new ReflectionMethod(get_class($this->object), "getComponent_List");
        $getComponent_List->setAccessible(true);
        
        $this->assertInstanceOf(ArrayObject::class, $getComponent_List->invoke($this->object));
        $this->assertSame(0, count($getComponent_List->invoke($this->object)));
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Composite\AbstractComposite::hasComponent()
     * @throws ReflectionException
     * @uses   \NiceshopsDev\NiceCore\Composite\AbstractComposite::getComponent_List()
     */
    public function testHasComponent()
    {
        $getComponent_List = new ReflectionMethod(get_class($this->object), "getComponent_List");
        $getComponent_List->setAccessible(true);
        
        $hasComponent = new ReflectionMethod(get_class($this->object), "hasComponent");
        $hasComponent->setAccessible(true);
        
        $component = new stdClass();
        $component->foo = "bar";
        
        $this->assertFalse($hasComponent->invoke($this->object, $component));
        $getComponent_List->invoke($this->object)->append($component);
        $this->assertTrue($hasComponent->invoke($this->object, $component));
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Composite\AbstractComposite::addComponent()
     * @throws ReflectionException
     * @uses   \NiceshopsDev\NiceCore\Composite\AbstractComposite::getComponent_List()
     */
    public function testAddComponent()
    {
        $getComponent_List = new ReflectionMethod(get_class($this->object), "getComponent_List");
        $getComponent_List->setAccessible(true);
        
        $addComponent = new ReflectionMethod(get_class($this->object), "addComponent");
        $addComponent->setAccessible(true);
        
        
        $component = new stdClass();
        $component->foo = "bar";
        
        $this->assertSame(0, count($getComponent_List->invoke($this->object)));
        $addComponent->invoke($this->object, $component);
        $this->assertSame(1, count($getComponent_List->invoke($this->object)));
        $addComponent->invoke($this->object, $component);
        $this->assertSame(1, count($getComponent_List->invoke($this->object)));
        $addComponent->invoke($this->object, clone $component);
        $this->assertSame(2, count($getComponent_List->invoke($this->object)));
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Composite\AbstractComposite::removeComponent()
     * @throws ReflectionException
     * @uses   \NiceshopsDev\NiceCore\Composite\AbstractComposite::getComponent_List()
     */
    public function testRemoveComponent()
    {
        $getComponent_List = new ReflectionMethod(get_class($this->object), "getComponent_List");
        $getComponent_List->setAccessible(true);
        
        $removeComponent = new ReflectionMethod(get_class($this->object), "removeComponent");
        $removeComponent->setAccessible(true);
        
        $component = new stdClass();
        $component->foo = "bar";
        
        $component2 = new stdClass();
        $component2->bar = "baz";
        
        $this->assertSame(0, count($getComponent_List->invoke($this->object)));
        $getComponent_List->invoke($this->object)->append($component);
        $getComponent_List->invoke($this->object)->append($component2);
        $this->assertSame(2, count($getComponent_List->invoke($this->object)));
        $this->assertSame($component, $getComponent_List->invoke($this->object)->offsetGet(0));
        $this->assertSame($component2, $getComponent_List->invoke($this->object)->offsetGet(1));
        
        $removeComponent->invoke($this->object, $component);
        $this->assertSame(1, count($getComponent_List->invoke($this->object)));
        $this->assertArrayNotHasKey(0, $getComponent_List->invoke($this->object));
        $this->assertArrayHasKey(1, $getComponent_List->invoke($this->object));
        $this->assertSame($component2, $getComponent_List->invoke($this->object)->offsetGet(1));
        
        $removeComponent->invoke($this->object, $component);
        $this->assertSame(1, count($getComponent_List->invoke($this->object)));
        $this->assertArrayNotHasKey(0, $getComponent_List->invoke($this->object));
        $this->assertArrayHasKey(1, $getComponent_List->invoke($this->object));
        $this->assertSame($component2, $getComponent_List->invoke($this->object)->offsetGet(1));
        
        $removeComponent->invoke($this->object, $component2);
        $this->assertSame(0, count($getComponent_List->invoke($this->object)));
        $this->assertArrayNotHasKey(0, $getComponent_List->invoke($this->object));
        $this->assertArrayNotHasKey(1, $getComponent_List->invoke($this->object));
    }
    
    
    /**
     * @group  unit
     * @small
     *
     * @covers \NiceshopsDev\NiceCore\Composite\AbstractComposite::addComponent()
     * @covers \NiceshopsDev\NiceCore\Composite\AbstractComposite::hasComponent()
     * @covers \NiceshopsDev\NiceCore\Composite\AbstractComposite::removeComponent()
     */
    public function testConcreteImplementation()
    {
        $defaultBean = new class() implements Countable {
            
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
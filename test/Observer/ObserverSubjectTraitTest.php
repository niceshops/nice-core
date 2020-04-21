<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace NiceshopsDev\NiceCore\Observer;

use NiceshopsDev\NiceCore\PHPUnit\DefaultTestCase;
use PHPUnit\Framework\MockObject\MockObject;

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
    
}
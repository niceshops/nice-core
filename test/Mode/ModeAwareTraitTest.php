<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Pattern\Mode;

use Pars\Pattern\PHPUnit\DefaultTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class DefaultTestCaseTest
 * @package Pattern
 */
class ModeAwareTraitTest extends DefaultTestCase
{

    /**
     * @var ModeAwareTrait|MockObject
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(ModeAwareTrait::class)->getMockForTrait();
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
        $this->assertTrue(trait_exists(ModeAwareTrait::class), "Class Exists");
        $this->assertUseTrait(ModeAwareTrait::class, $this->object, "Mock Object uses " . ModeAwareTrait::class);
    }

    /**
     * @group integration
     * @small
     */
    public function testMode()
    {
        $this->assertFalse($this->object->hasMode());
        $this->assertUseTrait(ModeAwareTrait::class, $this->object->setMode('test'));
        $this->assertTrue($this->object->hasMode());
        $this->assertEquals('test', $this->object->getMode());
        $this->assertUseTrait(ModeAwareTrait::class, $this->object->setMode('test2'));
        $this->assertTrue($this->object->hasMode());
        $this->assertEquals('test2', $this->object->getMode());
        $this->assertNotEquals('test', $this->object->getMode());
    }
}

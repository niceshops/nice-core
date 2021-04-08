<?php

declare(strict_types=1);

/**
 * @see       https://github.com/Pars/pars-patterns for the canonical source repository
 * @license   https://github.com/Pars/pars-patterns/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Patterns\Runner;

use Generator;
use Pars\Patterns\PHPUnit\DefaultTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * UnitTest class for TraversableRunner
 * @coversDefaultClass  \Pars\Patterns\Runner\TraversableRunner
 * @uses                \Pars\Patterns\Runner\TraversableRunner
 * @package             Pars\Patterns
 */
class TraversableRunnerTest extends DefaultTestCase
{


    /**
     * @var TraversableRunner|MockObject
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(TraversableRunner::class)->disableOriginalConstructor()->getMockForAbstractClass();
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
        $this->assertTrue(class_exists(TraversableRunner::class), "Class Exists");
        $this->assertTrue(is_a($this->object, TraversableRunner::class), "Mock Object is set");
    }


    /**
     * @param            $data
     *
     * @param array|null $arrMethod
     *
     * @return TraversableRunner|MockObject
     */
    protected function createRunnerMock(&$data, array $arrMethod = null)
    {
        return $this->getMockBuilder(TraversableRunner::class)->setConstructorArgs([&$data])->setMethods($arrMethod)->getMockForAbstractClass();
    }


    /**
     * @return Generator
     */
    public function runFromToDataProvider()
    {
        $arrData = [
            ["key" => 0, "odd" => true],
            ["key" => 1, "odd" => false],
            ["key" => 2, "odd" => true],
            ["key" => 3, "odd" => false],
            ["key" => 4, "odd" => true],
        ];

        yield [$arrData, null, null, 1, $arrData];
        yield [$arrData, 2, null, 1, array_slice($arrData, 2)];
        yield [$arrData, 7, null, 1, array_slice($arrData, 2)];
        yield [$arrData, -2, null, 1, array_slice($arrData, -2)];
        yield [$arrData, -7, null, 1, array_slice($arrData, -2)];
        yield [$arrData, null, 2, 1, array_slice($arrData, 0, 3)];
        yield [$arrData, null, -1, 1, array_slice($arrData, 0, 4)];
        yield [$arrData, null, -3, 1, array_slice($arrData, 0, 2)];
        yield [$arrData, null, -8, 1, array_slice($arrData, 0, 2)];
        yield [$arrData, 0, 4, 1, $arrData];      //  same as [null, null]
        yield [$arrData, 1, 2, 1, array_slice($arrData, 1, 2)];
        yield [$arrData, 2, 1, 1, array_slice($arrData, 1, 2)];       //  switch from and to
        yield [$arrData, 0, 10, 1, array_slice($arrData, 0, 1)];
        yield [$arrData, -1, -1, 1, array_slice($arrData, -2)];       //  from=4, to=3 => switch from and to
        yield [$arrData, 0, -1, 1, array_slice($arrData, 0, 4)];
        yield [$arrData, -5, 1, 1, array_slice($arrData, 0, 2)];
        yield [$arrData, -100, 1, 1, array_slice($arrData, 0, 2)];
        yield [$arrData, 0, 0, 1, array_slice($arrData, 0, 1)];       //  just the first element
        yield [$arrData, 5, -5, 1, array_slice($arrData, 0, 1)];      //  just the first element
        yield [$arrData, 4, 4, 1, array_slice($arrData, -1)];         //  just the last element
        yield [$arrData, -1, null, 1, array_slice($arrData, -1)];     //  just the last element

        // stepWith = 2
        yield [$arrData, null, null, 2, [$arrData[0], $arrData[2], $arrData[4]]];
        yield [$arrData, 2, null, 2, [$arrData[2], $arrData[4]]];
        yield [$arrData, null, 2, 2, [$arrData[0], $arrData[2]]];
        yield [$arrData, 2, 2, 2, [$arrData[2]]];
        yield [$arrData, 2, 4, 2, [$arrData[2], $arrData[4]]];

        // stepWith = 3
        yield [$arrData, null, null, 3, [$arrData[0], $arrData[3]]];
        yield [$arrData, 2, null, 3, [$arrData[2]]];
        yield [$arrData, null, 2, 3, [$arrData[0]]];
        yield [$arrData, 2, 2, 3, [$arrData[2]]];
        yield [$arrData, 2, 4, 3, [$arrData[2]]];
    }


    /**
     * @group        unit
     * @small
     *
     * @dataProvider runFromToDataProvider
     *
     * @covers       \Pars\Patterns\Runner\TraversableRunner::runFromTo
     *
     * @param array $arrData
     * @param int|null        $from
     * @param int|null        $to
     * @param int             $stepWidth
     * @param                 $expectedValue
     */
    public function testRunFromTo(array $arrData, ?int $from, ?int $to, int $stepWidth, $expectedValue)
    {
        $this->object = $this->createRunnerMock($arrData);

        $arrActual = [];

        foreach ($this->object->runFromTo($from, $to, $stepWidth) as $key => $bean) {
            $arrActual[$key] = $bean;
        }

        $this->assertSame($expectedValue, $arrActual);
    }


    /**
     * @return Generator
     */
    public function runFromDataProvider()
    {
        $arrData = [
            ["key" => 0, "odd" => true],
            ["key" => 1, "odd" => false],
            ["key" => 2, "odd" => true],
            ["key" => 3, "odd" => false],
            ["key" => 4, "odd" => true],
        ];

        yield [$arrData, null, null, 1, $arrData];                //  all elements;
        yield [$arrData, 0, null, 1, $arrData];                   //  all elements
        yield [$arrData, 5, null, 1, $arrData];                   //  all elements
        yield [$arrData, 1, null, 1, array_slice($arrData, 1)];   //  all elements
        yield [$arrData, -1, null, 1, array_slice($arrData, -1)];
        yield [$arrData, 0, 2, 1, array_slice($arrData, 0, 2)];
        yield [$arrData, 0, -2, 1, array_slice($arrData, 0, -2)];
        yield [$arrData, 0, -7, 1, array_slice($arrData, 0, -2)];
        yield [$arrData, 0, 7, 1, $arrData];
        yield [$arrData, 0, 10, 1, $arrData];

        yield [$arrData, null, null, 2, [$arrData[0], $arrData[2], $arrData[4]]];
        yield [$arrData, null, 2, 2, [$arrData[0], $arrData[2]]];
        yield [$arrData, null, 20, 2, [$arrData[0], $arrData[2], $arrData[4]]];
        yield [$arrData, 1, null, 2, [$arrData[1], $arrData[3]]];
        yield [$arrData, 1, 1, 2, [$arrData[1]]];
        yield [$arrData, 0, -1, 2, [$arrData[0], $arrData[2], $arrData[4]]];
        yield [$arrData, 0, -2, 2, [$arrData[0], $arrData[2], $arrData[4]]];
        yield [$arrData, 0, -3, 2, [$arrData[0], $arrData[2]]];
    }


    /**
     * @group        unit
     * @small
     *
     * @dataProvider runFromDataProvider
     *
     * @covers       \Pars\Patterns\Runner\TraversableRunner::runFrom
     *
     * @param array    $arrData
     * @param int|null $from
     * @param int|null $length
     * @param int|null $stepWidth
     * @param          $expectedValue
     */
    public function testRunFrom(array $arrData, ?int $from, ?int $length, ?int $stepWidth, $expectedValue)
    {
        $this->object = $this->createRunnerMock($arrData);

        $arrActual = [];

        foreach ($this->object->runFrom($from, $length, $stepWidth) as $key => $bean) {
            $arrActual[$key] = $bean;
        }

        $this->assertSame($expectedValue, $arrActual);
    }


    /**
     * @group unit
     * @small
     *
     * @covers \Pars\Patterns\Runner\TraversableRunner::runFrom
     */
    public function testDataPassedByReference()
    {
        $arrData = [
            ["name" => "foo"],
            ["name" => "bar"],
        ];
        $this->object = $this->createRunnerMock($arrData);

        //  add an entry
        $arrData[] = ["name" => "baz"];

        $arrActual = [];
        foreach ($this->object->runFrom() as $key => $val) {
            $arrActual[$key] = $val;
        }
        $this->assertCount(count($arrData), $arrActual);

        //  remove an entry
        array_shift($arrData);
        $arrActual = [];
        foreach ($this->object->runFrom() as $key => $val) {
            $arrActual[$key] = $val;
        }
        $this->assertCount(count($arrData), $arrActual);
    }
}

<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-core for the canonical source repository
 * @license   https://github.com/niceshops/nice-core/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Core\Runner;

use Countable;
use Generator;
use Niceshops\Core\Exception;
use Traversable;

/**
 * Class TraversableRunner
 * @package Niceshops\Core
 */
class TraversableRunner implements RunnerInterface
{

    /**
     * @var Traversable|array
     */
    protected $traversable;


    /**
     * TraversableRunner constructor.
     *
     * @param Traversable|array $traversable
     *
     * @throws Exception    passed parameter is not an array nor Traversable
     */
    public function __construct(&$traversable)
    {
        if (!is_array($traversable) && (!$traversable instanceof Traversable)) {
            throw new Exception("Passed object is not traversable (Array or instance implements the 'Traversable' interface required)!");
        }
        $this->traversable =& $traversable;
    }


    /**
     * @return int
     */
    protected function getCount()
    {
        if ($this->traversable instanceof Countable || is_array($this->traversable)) {
            $count = count($this->traversable);
        } else {
            if (method_exists($this->traversable, "count")) {
                $count = $this->traversable->count();
            } else {
                if (method_exists($this->traversable, "toArray")) {
                    $count = count($this->traversable->toArray());
                } else {
                    $count = 0;
                    foreach ($this->traversable as $val) {
                        ++$count;
                    }
                    unset($val);
                }
            }
        }

        return $count;
    }


    /**
     * @param $from
     *
     * @return mixed
     */
    protected function normalizeRunFrom($from)
    {
        $count = $this->getCount();

        if (is_null($from)) {
            $from = 0;
        } else {
            if ($count > 0) {
                $from = $from % $count;
            }
        }

        if ($from < 0) {
            $from = $count + $from;
        } else {
            if ($from > $count - 1) {
                $from = $count - 1;
            }
        }

        return $from;
    }


    /**
     * @param int $from
     * @param int $to
     * @param int $stepWidth
     *
     * @return Generator
     */
    public function runFromTo($from = 0, $to = null, $stepWidth = 1)
    {
        $count = $this->getCount();

        $from = $this->normalizeRunFrom($from);

        if ($stepWidth < 1) {
            $stepWidth = 1;
        }

        if (is_null($to)) {
            $to = $count - 1;
        } else {
            if ($count > 0) {
                $to = $to % $count;
            }
        }

        if ($to < 0) {
            $to = ($count + $to) - 1;
        } else {
            if ($to > $count - 1) {
                $to = $count - 1;
            }
        }

        if ($to < $from) {
            $_from = $from;
            $from = $to;
            $to = $_from;
        }

        foreach ($this->traversable as $key => $val) {
            if ($key < $from) {
                continue;
            }
            if ($key > $to) {
                break;
            }
            if ($stepWidth > 1 && ($key - $from) % $stepWidth != 0) {
                continue;
            }

            yield $val;
        }
    }


    /**
     * @param int $from
     * @param int $length
     * @param int $stepWidth
     *
     * @return Generator
     */
    public function runFrom($from = 0, $length = null, $stepWidth = 1)
    {
        $count = $this->getCount();
        $from = $this->normalizeRunFrom($from);
        if ($stepWidth < 1) {
            $stepWidth = 1;
        }

        if (is_null($length)) {
            $length = $count;
        } else {
            if ($length < 0) {
                $length = $count + ($length % $count);
            }
        }
        if ($length > $count - $from) {
            $length = $count - $from;
        }

        $to = $from + ($length * $stepWidth - 1);
        if ($stepWidth > 1 && $to > $count - 1) {
            $to = null;
        }

        return $this->runFromTo($from, $to, $stepWidth);
    }
}

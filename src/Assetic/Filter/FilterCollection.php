<?php

/*
 * This file is part of the Assetic package, an OpenSky project.
 *
 * (c) 2010-2011 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Assetic\Filter;

use Assetic\Asset\AssetInterface;

/**
 * A collection of filters.
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 */
class FilterCollection implements FilterInterface, \IteratorAggregate
{
    private $filters = array();
    private $sortedFilters = array();
    private $sorted = false;

    public function __construct($filters = array())
    {
        foreach ($filters as $filter) {
            $this->ensure($filter);
        }
    }

    /**
     * Checks that the current collection contains the supplied filter.
     *
     * If the supplied filter is another filter collection, each of its
     * child filters will be ensured individually.
     */
    public function ensure(FilterInterface $filter)
    {
        if ($filter instanceof \Traversable) {
            foreach ($filter as $f) {
                $this->ensure($f);
            }
        } elseif (!in_array($filter, $this->sortedFilters, true)) {
            $this->filters[$filter->getPriority()][] = $filter;
            $this->sortedFilters[] = $filter;
            $this->sorted = false;
        }
    }

    public function all()
    {
        if (!$this->sorted) {
            $this->sort();
        }

        return $this->sortedFilters;
    }

    public function filterLoad(AssetInterface $asset)
    {
        if (!$this->sorted) {
            $this->sort();
        }

        foreach ($this->sortedFilters as $filter) {
            $filter->filterLoad($asset);
        }
    }

    public function filterDump(AssetInterface $asset)
    {
        if (!$this->sorted) {
            $this->sort();
        }

        foreach (array_reverse($this->sortedFilters) as $filter) {
            $filter->filterDump($asset);
        }
    }

    static public function getPriority()
    {
        return FilterInterface::MANTLE;
    }

    public function getIterator()
    {
        if (!$this->sorted) {
            $this->sort();
        }

        return new \ArrayIterator($this->sortedFilters);
    }

    private function sort()
    {
        if ($filters = $this->filters) {
            ksort($filters);
            $this->sortedFilters = call_user_func_array('array_merge', $filters);
        } else {
            $this->sortedFilters = array();
        }

        $this->sorted = true;
    }
}

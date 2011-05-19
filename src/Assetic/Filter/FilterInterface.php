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
 * A filter manipulates an asset at load and dump.
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 */
interface FilterInterface
{
    const CRUST  = -1;
    const MANTLE = 0;
    const CORE   = 1;

    /**
     * Filters an asset after it has been loaded.
     *
     * @param AssetInterface $asset An asset
     */
    function filterLoad(AssetInterface $asset);

    /**
     * Filters an asset just before it's dumped.
     *
     * @param AssetInterface $asset An asset
     */
    function filterDump(AssetInterface $asset);

    /**
     * Returns the current filter's priority.
     *
     * This method should return one of the FilterInterface priority
     * constants:
     *
     *  * FilterInterface::CRUST
     *  * FilterInterface::MANTLE
     *  * FilterInterface::CORE
     *
     * Filters with a "crust" priority should be run first during load and
     * last during dump.
     *
     * @return integer A FilterInterface priority constant
     */
    static function getPriority();
}

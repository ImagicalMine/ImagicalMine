<?php
/**
 * src/pocketmine/metadata/MetadataValue.php
 *
 * @package default
 */


/*
 *
 *  _                       _           _ __  __ _
 * (_)                     (_)         | |  \/  (_)
 *  _ _ __ ___   __ _  __ _ _  ___ __ _| | \  / |_ _ __   ___
 * | | '_ ` _ \ / _` |/ _` | |/ __/ _` | | |\/| | | '_ \ / _ \
 * | | | | | | | (_| | (_| | | (_| (_| | | |  | | | | | |  __/
 * |_|_| |_| |_|\__,_|\__, |_|\___\__,_|_|_|  |_|_|_| |_|\___|
 *                     __/ |
 *                    |___/
 *
 * This program is a third party build by ImagicalMine.
 *
 * PocketMine is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author ImagicalMine Team
 * @link http://forums.imagicalcorp.ml/
 *
 *
*/

namespace pocketmine\metadata;

use pocketmine\plugin\Plugin;

abstract class MetadataValue
{
    /** @var \WeakRef<Plugin> */
    protected $owningPlugin;

    /**
     *
     * @param Plugin  $owningPlugin
     */
    protected function __construct(Plugin $owningPlugin)
    {
        /** WeakRef dependency lock */
        //$this->owningPlugin = new \WeakRef($owningPlugin);
        $this->owningPlugin = $owningPlugin;
    }


    /**
     *
     * @return Plugin
     */
    public function getOwningPlugin()
    {
        /** WeakRef dependency lock */
        //return $this->owningPlugin->get();
        return $this->owningPlugin;
    }


    /**
     * Fetches the value of this metadata item.
     *
     * @return mixed
     */
    abstract public function value();

    /**
     * Invalidates this metadata item, forcing it to recompute when next
     * accessed.
     */
    abstract public function invalidate();
}
